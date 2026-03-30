<?php

namespace App\Http\Controllers\Faculty;

use App\Models\User;
use App\Models\Department;
use App\Models\DeanCalibration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SummaryReportController extends Controller
{
    /**
     * Display the summary reports page with faculty data.
     */
    public function index(Request $request)
    {
        $activeDepartment = $request->query('department', 'all');

        // Get all departments for the filter tabs
        $departments = Department::orderBy('code')->get();

        // Query users: exclude dean, admin, hr, director roles
        $excludedRoles = ['dean', 'admin', 'hr', 'director'];

        $query = User::with(['department', 'designation', 'userRoles'])
            ->where('is_active', true)
            ->whereHas('userRoles', function ($q) {
                $q->where('role', 'faculty');
            })
            ->whereDoesntHave('userRoles', function ($q) use ($excludedRoles) {
                $q->whereIn('role', $excludedRoles);
            });

        // Filter by department if not "all"
        if ($activeDepartment !== 'all') {
            $query->whereHas('department', function ($q) use ($activeDepartment) {
                $q->where('code', $activeDepartment);
            });
        }

        $users = $query->orderBy('name')->get();

        // Fetch calibrated ratings for each user
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

        return view('dashboard.faculty.summary-reports', compact('users', 'departments', 'activeDepartment'));
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
