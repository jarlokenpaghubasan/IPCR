<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\OpcrSavedCopy;
use App\Models\OpcrSubmission;
use App\Models\OpcrTemplate;
use App\Services\ActivityLogService;
use App\Services\OpcrExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpcrExportController extends Controller
{
    /**
     * Export a submitted OPCR to .xlsx.
     */
    public function export(Request $request, $id, OpcrExportService $exportService)
    {
        $submission = OpcrSubmission::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->applyExportPeriodOverrides($submission, $request);

        return $this->doExport($exportService, $submission, 'OPCR Submission');
    }

    /**
     * Export an OPCR saved copy to .xlsx.
     */
    public function exportSavedCopy(Request $request, $id, OpcrExportService $exportService)
    {
        $savedCopy = OpcrSavedCopy::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->applyExportPeriodOverrides($savedCopy, $request);

        return $this->doExport($exportService, $savedCopy, 'OPCR Draft');
    }

    /**
     * Export an OPCR template to .xlsx.
     */
    public function exportTemplate(Request $request, $id, OpcrExportService $exportService)
    {
        $template = OpcrTemplate::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->applyExportPeriodOverrides($template, $request);

        return $this->doExport($exportService, $template, 'OPCR Template');
    }

    private function applyExportPeriodOverrides($document, Request $request): void
    {
        $schoolYear = trim((string) $request->query('school_year', ''));
        if ($schoolYear !== '') {
            $document->school_year = $schoolYear;
        }

        $semester = trim((string) $request->query('semester', ''));
        if ($semester !== '') {
            $document->semester = $semester;
        }
    }

    /**
     * Common export logic for any OPCR document type.
     */
    private function doExport(OpcrExportService $exportService, $document, string $label)
    {
        try {
            $filePath = $exportService->export($document);

            $fileName = 'OPCR_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $document->title)
                      . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', (string) $document->school_year) . '.xlsx';

            ActivityLogService::log(
                'opcr_exported',
                "Exported {$label} to Excel: " . $document->title,
                $document
            );

            return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error('OPCR Export Error', [
                'document_id' => $document->id,
                'type' => $label,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => "Failed to export {$label}: " . $e->getMessage(),
            ], 500);
        }
    }
}
