<?php

namespace App\Http\Controllers\Faculty;

use App\Models\User;
use App\Models\Department;
use App\Models\DeanCalibration;
use App\Models\DeanDirectorSummaryOverride;
use App\Models\IpcrSubmission;
use App\Models\OpcrSubmission;
use App\Services\ActivityLogService;
use App\Services\DeanDirectorSummaryExportService;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;

class SummaryReportController extends Controller
{
    /**
     * Display the summary reports page with faculty data.
     */
    public function index(Request $request)
    {
        $activeDepartment = $request->query('department', 'all');
        $requestedCategory = $request->query('category', 'faculty');
        $activeCategory = in_array($requestedCategory, ['faculty', 'staff', 'dean-director'], true) ? $requestedCategory : 'faculty';
        $regularStaffStatusOptions = ['Permanent', 'Casual', 'Contractual'];
        $emergencyStaffStatus = 'Emergency Laborer';
        $partTimeStatus = 'Part Time';

        // Staff and dean/director views are always campus-wide.
        if (in_array($activeCategory, ['staff', 'dean-director'], true)) {
            $activeDepartment = 'all';
        }

        // Get all departments for the filter tabs
        $departments = Department::orderBy('code')->get();

        $users = collect();
        $emergencyUsers = collect();
        $partTimeUsers = collect();
        $deanDirectorRows = collect();

        if ($activeCategory === 'dean-director') {
            $deanDirectorRows = $this->buildDeanDirectorRows();

            return view('dashboard.faculty.summary-reports', compact(
                'users',
                'emergencyUsers',
                'partTimeUsers',
                'deanDirectorRows',
                'departments',
                'activeDepartment',
                'activeCategory'
            ));
        }

        // Query users: exclude dean, admin, hr, director roles
        $excludedRoles = ['dean', 'admin', 'hr', 'director'];

        $baseQuery = User::with(['department', 'designation', 'userRoles'])
            ->where('is_active', true)
            ->whereHas('userRoles', function ($q) {
                $q->where('role', 'faculty');
            })
            ->whereDoesntHave('userRoles', function ($q) use ($excludedRoles) {
                $q->whereIn('role', $excludedRoles);
            });

        // Filter by department if not "all"
        if ($activeDepartment !== 'all') {
            $baseQuery->whereHas('department', function ($q) use ($activeDepartment) {
                $q->where('code', $activeDepartment);
            });
        }

        if ($activeCategory === 'staff') {
            $users = (clone $baseQuery)
                ->whereIn('employment_status', $regularStaffStatusOptions)
                ->orderBy('name')
                ->get();

            $emergencyUsers = (clone $baseQuery)
                ->where('employment_status', $emergencyStaffStatus)
                ->orderBy('name')
                ->get();

            $partTimeUsers = collect();
        } else {
            $users = (clone $baseQuery)
                ->whereNull('employment_status')
                ->orderBy('name')
                ->get();

            $partTimeUsers = (clone $baseQuery)
                ->where('employment_status', $partTimeStatus)
                ->orderBy('name')
                ->get();

            $emergencyUsers = collect();
        }

        $this->appendRatings($users);
        $this->appendRatings($emergencyUsers);
        $this->appendRatings($partTimeUsers);

        return view('dashboard.faculty.summary-reports', compact(
            'users',
            'emergencyUsers',
            'partTimeUsers',
            'deanDirectorRows',
            'departments',
            'activeDepartment',
            'activeCategory'
        ));
    }

    /**
     * Build the campus-wide summary rows for director and all deans.
     */
    private function buildDeanDirectorRows(): Collection
    {
        $leaders = User::with(['department', 'designation', 'userRoles'])
            ->where('is_active', true)
            ->whereHas('userRoles', function ($q) {
                $q->whereIn('role', ['dean', 'director']);
            })
            ->get();

        $overrides = DeanDirectorSummaryOverride::whereIn('user_id', $leaders->pluck('id'))
            ->get()
            ->keyBy('user_id');

        return $leaders
            ->map(function (User $user) use ($overrides) {
                $submission = $this->resolveLatestPerformanceSubmission($user);
                $calculatedScores = $this->computeWeightedSectionScores($submission?->table_body_html ?? '');
                $scores = $this->applyDeanDirectorOverride($calculatedScores, $overrides->get($user->id));

                $isDirector = $user->hasRole('director');
                $departmentName = $user->department?->name ?? 'Unassigned Department';
                $departmentCode = $user->department?->code;
                $roleLabel = $isDirector
                    ? 'Campus Director'
                    : 'Dean, ' . $departmentName;
                $roleLabelShort = $isDirector
                    ? 'Campus Director'
                    : 'Dean, ' . ($departmentCode ?: $departmentName);

                return [
                    'user_id' => $user->id,
                    'employee_label' => $roleLabel,
                    'employee_label_short' => $roleLabelShort,
                    'employee_name' => $user->name,
                    'employee_id' => $user->employee_id,
                    'department_code' => $departmentCode,
                    'is_director' => $isDirector,
                    'strategic_score' => $scores['strategic_score'],
                    'core_score' => $scores['core_score'],
                    'support_score' => $scores['support_score'],
                    'total_score' => $scores['total_score'],
                    'adjectival_rating' => $scores['adjectival_rating'],
                    'is_manual' => $scores['is_manual'],
                ];
            })
            ->sortBy([
                fn(array $row) => $row['is_director'] ? 0 : 1,
                fn(array $row) => $row['department_code'] ?? 'ZZZ',
                fn(array $row) => $row['employee_name'],
            ])
            ->values();
    }

    /**
     * Save manual 35/55/10 weighted percentage-point values for a dean/director row.
     */
    public function updateDeanDirectorScores(Request $request, User $user)
    {
        if (!$user->hasAnyRole(['dean', 'director'])) {
            abort(404);
        }

        if ($request->boolean('clear_scores')) {
            DeanDirectorSummaryOverride::where('user_id', $user->id)->delete();

            return redirect()
                ->route('faculty.summary-reports', ['category' => 'dean-director', 'department' => 'all'])
                ->with('success', 'Dean and director scores cleared successfully.');
        }

        $validated = $request->validate([
            'strategic_score' => ['nullable', 'numeric', 'min:0', 'max:35'],
            'core_score' => ['nullable', 'numeric', 'min:0', 'max:55'],
            'support_score' => ['nullable', 'numeric', 'min:0', 'max:10'],
        ]);

        $normalized = collect(['strategic_score', 'core_score', 'support_score'])
            ->mapWithKeys(function (string $field) use ($validated) {
                $value = $validated[$field] ?? null;

                if ($value === null || $value === '') {
                    return [$field => null];
                }

                return [$field => round((float) $value, 2)];
            })
            ->toArray();

        $hasAnyValue = collect($normalized)->contains(fn($value) => $value !== null);

        if (!$hasAnyValue) {
            DeanDirectorSummaryOverride::where('user_id', $user->id)->delete();
        } else {
            DeanDirectorSummaryOverride::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'strategic_score' => $normalized['strategic_score'],
                    'core_score' => $normalized['core_score'],
                    'support_score' => $normalized['support_score'],
                    'updated_by' => $request->user()->id,
                ]
            );
        }

        return redirect()
            ->route('faculty.summary-reports', ['category' => 'dean-director', 'department' => 'all'])
            ->with('success', 'Dean and director scores updated successfully.');
    }

    /**
     * Export the dean/director summary as XLSX.
     */
    public function exportDeanDirectorXlsx(Request $request, DeanDirectorSummaryExportService $exportService)
    {
        try {
            $rows = $this->buildDeanDirectorRows();

            $preparedByUser = $request->user()->loadMissing('designation');
            $director = User::with(['designation', 'userRoles'])
                ->where('is_active', true)
                ->whereHas('userRoles', function ($q) {
                    $q->where('role', 'director');
                })
                ->orderBy('name')
                ->first();

            $preparedBy = [
                'name' => $preparedByUser?->name,
                'position' => 'Campus HRMO',
            ];

            $notedBy = [
                'name' => $director?->name ?? '',
                'position' => 'Campus Director',
            ];

            $filePath = $exportService->export($rows, $preparedBy, $notedBy);

            ActivityLogService::log(
                'dean_director_summary_exported',
                'Exported Dean and Director summary report to Excel.',
                null,
                ['record_count' => $rows->count()]
            );

            $downloadName = 'Dean_Director_Summary_' . now()->format('Ymd_His') . '.xlsx';

            return response()->download($filePath, $downloadName)->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            \Log::error('Dean/Director summary export error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('faculty.summary-reports', ['category' => 'dean-director', 'department' => 'all'])
                ->withErrors(['export' => 'Failed to export Dean and Director summary report.']);
        }
    }

    /**
     * Resolve the latest submitted performance document for a user.
     * Prefers active submissions, then latest submitted record.
     */
    private function resolveLatestPerformanceSubmission(User $user): IpcrSubmission|OpcrSubmission|null
    {
        $latestOpcr = OpcrSubmission::where('user_id', $user->id)
            ->whereNotNull('submitted_at')
            ->orderByDesc('is_active')
            ->orderByDesc('submitted_at')
            ->first();

        $latestIpcr = IpcrSubmission::where('user_id', $user->id)
            ->whereNotNull('submitted_at')
            ->orderByDesc('is_active')
            ->orderByDesc('submitted_at')
            ->first();

        if (!$latestOpcr) {
            return $latestIpcr;
        }

        if (!$latestIpcr) {
            return $latestOpcr;
        }

        return $latestOpcr->submitted_at >= $latestIpcr->submitted_at ? $latestOpcr : $latestIpcr;
    }

    /**
     * Apply manual override values to calculated row scores.
     */
    private function applyDeanDirectorOverride(array $calculatedScores, ?DeanDirectorSummaryOverride $override): array
    {
        if (!$override) {
            $calculatedScores['is_manual'] = false;

            return $calculatedScores;
        }

        $hasOverrideValue = $override->strategic_score !== null
            || $override->core_score !== null
            || $override->support_score !== null;

        if (!$hasOverrideValue) {
            $calculatedScores['is_manual'] = false;

            return $calculatedScores;
        }

        $strategic = $override->strategic_score !== null
            ? (float) $override->strategic_score
            : $calculatedScores['strategic_score'];
        $core = $override->core_score !== null
            ? (float) $override->core_score
            : $calculatedScores['core_score'];
        $support = $override->support_score !== null
            ? (float) $override->support_score
            : $calculatedScores['support_score'];

        $hasAnyScore = $strategic !== null || $core !== null || $support !== null;
        $totalPercentage = $hasAnyScore
            ? round(($strategic ?? 0) + ($core ?? 0) + ($support ?? 0), 2)
            : null;
        $total = $totalPercentage !== null
            ? round($totalPercentage / 20, 2)
            : null;

        return [
            'strategic_score' => $strategic,
            'core_score' => $core,
            'support_score' => $support,
            'total_score' => $total,
            'adjectival_rating' => $total !== null ? $this->getAdjectivalRating($total) : null,
            'is_manual' => true,
        ];
    }

    /**
     * Compute 35/55/10 weighted percentage points, then convert total to 1-5 scale.
     */
    private function computeWeightedSectionScores(string $tableBodyHtml): array
    {
        if (empty(trim($tableBodyHtml))) {
            return [
                'strategic_score' => null,
                'core_score' => null,
                'support_score' => null,
                'total_score' => null,
                'adjectival_rating' => null,
            ];
        }

        $ratings = [
            'strategic-objectives' => [],
            'core-functions' => [],
            'support-function' => [],
        ];

        $wrappedHtml = '<table><tbody>' . $tableBodyHtml . '</tbody></table>';

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $rows = $xpath->query('//tr');
        $currentSection = null;

        foreach ($rows as $row) {
            $className = $row->getAttribute('class') ?? '';

            if (str_contains($className, 'bg-green-100')) {
                $currentSection = 'strategic-objectives';
                continue;
            }

            if (str_contains($className, 'bg-purple-100')) {
                $currentSection = 'core-functions';
                continue;
            }

            if (str_contains($className, 'bg-orange-100')) {
                $currentSection = 'support-function';
                continue;
            }

            if (!$currentSection || str_contains($className, 'bg-blue-100') || str_contains($className, 'bg-gray-100')) {
                continue;
            }

            $cells = $xpath->query('.//td', $row);
            if ($cells->length < 7) {
                continue;
            }

            $scoreValue = $this->extractCellValue($cells->item(6), $xpath);
            if (is_numeric($scoreValue)) {
                $numeric = (float) $scoreValue;
                if ($numeric > 0) {
                    $ratings[$currentSection][] = $numeric;
                }
            }
        }

        $strategicAverage = $this->average($ratings['strategic-objectives']);
        $coreAverage = $this->average($ratings['core-functions']);
        $supportAverage = $this->average($ratings['support-function']);

        // Convert section averages from 1-5 into weighted percentage points.
        $strategicWeighted = $strategicAverage !== null ? round(($strategicAverage / 5) * 35, 2) : null;
        $coreWeighted = $coreAverage !== null ? round(($coreAverage / 5) * 55, 2) : null;
        $supportWeighted = $supportAverage !== null ? round(($supportAverage / 5) * 10, 2) : null;

        $hasAnyScore = $strategicWeighted !== null || $coreWeighted !== null || $supportWeighted !== null;
        $totalPercentage = $hasAnyScore
            ? round(($strategicWeighted ?? 0) + ($coreWeighted ?? 0) + ($supportWeighted ?? 0), 2)
            : null;
        $total = $totalPercentage !== null
            ? round($totalPercentage / 20, 2)
            : null;

        return [
            'strategic_score' => $strategicWeighted,
            'core_score' => $coreWeighted,
            'support_score' => $supportWeighted,
            'total_score' => $total,
            'adjectival_rating' => $total !== null ? $this->getAdjectivalRating($total) : null,
        ];
    }

    /**
     * Extract value from td input/textarea/plain text.
     */
    private function extractCellValue($td, DOMXPath $xpath): string
    {
        $textareas = $xpath->query('.//textarea', $td);
        if ($textareas->length > 0) {
            return trim($textareas->item(0)->textContent);
        }

        $inputs = $xpath->query('.//input', $td);
        if ($inputs->length > 0) {
            return trim($inputs->item(0)->getAttribute('value'));
        }

        return trim($td->textContent);
    }

    /**
     * Get average value or null when array is empty.
     */
    private function average(array $values): ?float
    {
        if (count($values) === 0) {
            return null;
        }

        return array_sum($values) / count($values);
    }

    /**
     * Append calibrated and adjectival ratings to each user collection item.
     */
    private function appendRatings(Collection $users): void
    {
        $users->each(function ($user) {
            // Get the latest finalized calibration for this user
            $calibration = DeanCalibration::where('status', 'calibrated')
                ->whereHas('ipcrSubmission', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->latest()
                ->first();

            $user->calibrated_rating = $calibration?->overall_score;
            $user->adjectival_rating = $this->getAdjectivalRating($calibration?->overall_score);
        });
    }

    /**
     * Convert a numeric rating to its adjectival equivalent.
     */
    private function getAdjectivalRating(?float $score): ?string
    {
        if ($score === null) {
            return null;
        }

        if ($score >= 4.50) return 'Outstanding';
        if ($score >= 3.50) return 'Very Satisfactory';
        if ($score >= 2.50) return 'Satisfactory';
        if ($score >= 1.50) return 'Unsatisfactory';
        return 'Poor';
    }
}
