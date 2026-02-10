<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use App\Models\IpcrSubmission;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class DeanReviewController extends Controller
{
    /**
     * Get all IPCR submissions from faculty members in the dean's department.
     */
    public function facultySubmissions(Request $request)
    {
        $user = $request->user();
        $departmentId = $user->department_id;

        if (!$departmentId) {
            return response()->json([
                'success' => true,
                'submissions' => [],
            ]);
        }

        // Get all faculty users in the same department (exclude the dean themselves)
        $facultyUserIds = User::where('department_id', $departmentId)
            ->where('id', '!=', $user->id)
            ->whereHas('userRoles', function ($query) {
                $query->where('role', 'faculty');
            })
            ->pluck('id');

        $submissions = IpcrSubmission::whereIn('user_id', $facultyUserIds)
            ->with('user:id,name,employee_id')
            ->orderBy('submitted_at', 'desc')
            ->get()
            ->map(function ($submission) {
                return [
                    'id' => $submission->id,
                    'title' => $submission->title,
                    'school_year' => $submission->school_year,
                    'semester' => $submission->semester,
                    'status' => $submission->status,
                    'submitted_at' => $submission->submitted_at?->format('M d, Y'),
                    'user_name' => $submission->user?->name ?? 'Unknown',
                    'employee_id' => $submission->user?->employee_id ?? 'N/A',
                ];
            });

        return response()->json([
            'success' => true,
            'submissions' => $submissions,
        ]);
    }

    /**
     * View a specific faculty IPCR submission (read-only for dean).
     */
    public function showFacultySubmission(Request $request, $id)
    {
        $user = $request->user();
        $departmentId = $user->department_id;

        // The submission must belong to a user in the dean's department
        $submission = IpcrSubmission::where('id', $id)
            ->whereHas('user', function ($query) use ($departmentId, $user) {
                $query->where('department_id', $departmentId)
                      ->where('id', '!=', $user->id);
            })
            ->with('user:id,name,employee_id')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'submission' => [
                'id' => $submission->id,
                'user_id' => $submission->user_id,
                'title' => $submission->title,
                'school_year' => $submission->school_year,
                'semester' => $submission->semester,
                'table_body_html' => $submission->table_body_html,
                'status' => $submission->status,
                'submitted_at' => $submission->submitted_at?->format('M d, Y'),
                'user_name' => $submission->user?->name ?? 'Unknown',
                'employee_id' => $submission->user?->employee_id ?? 'N/A',
            ],
        ]);
    }

    /**
     * Get all IPCR submissions from other deans (for calibration).
     */
    public function deanSubmissions(Request $request)
    {
        $user = $request->user();

        // Get all users with the dean role (excluding the current user)
        $deanUserIds = User::where('id', '!=', $user->id)
            ->whereHas('userRoles', function ($query) {
                $query->where('role', 'dean');
            })
            ->pluck('id');

        $submissions = IpcrSubmission::whereIn('user_id', $deanUserIds)
            ->with(['user:id,name,employee_id,department_id', 'user.department:id,name,code'])
            ->orderBy('submitted_at', 'desc')
            ->get()
            ->map(function ($submission) {
                return [
                    'id' => $submission->id,
                    'title' => $submission->title,
                    'school_year' => $submission->school_year,
                    'semester' => $submission->semester,
                    'status' => $submission->status,
                    'submitted_at' => $submission->submitted_at?->format('M d, Y'),
                    'user_name' => $submission->user?->name ?? 'Unknown',
                    'employee_id' => $submission->user?->employee_id ?? 'N/A',
                    'department' => $submission->user?->department?->code ?? $submission->user?->department?->name ?? 'N/A',
                ];
            });

        return response()->json([
            'success' => true,
            'submissions' => $submissions,
        ]);
    }

    /**
     * View a specific dean's IPCR submission (read-only for calibration).
     */
    public function showDeanSubmission(Request $request, $id)
    {
        $user = $request->user();

        // The submission must belong to another dean
        $deanUserIds = User::where('id', '!=', $user->id)
            ->whereHas('userRoles', function ($query) {
                $query->where('role', 'dean');
            })
            ->pluck('id');

        $submission = IpcrSubmission::where('id', $id)
            ->whereIn('user_id', $deanUserIds)
            ->with(['user:id,name,employee_id,department_id', 'user.department:id,name,code'])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'submission' => [
                'id' => $submission->id,
                'user_id' => $submission->user_id,
                'title' => $submission->title,
                'school_year' => $submission->school_year,
                'semester' => $submission->semester,
                'table_body_html' => $submission->table_body_html,
                'status' => $submission->status,
                'submitted_at' => $submission->submitted_at?->format('M d, Y'),
                'user_name' => $submission->user?->name ?? 'Unknown',
                'employee_id' => $submission->user?->employee_id ?? 'N/A',
                'department' => $submission->user?->department?->code ?? $submission->user?->department?->name ?? 'N/A',
            ],
        ]);
    }
}
