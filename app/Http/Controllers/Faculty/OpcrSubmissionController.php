<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\OpcrSubmission;
use Illuminate\Http\Request;

class OpcrSubmissionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'school_year' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'string', 'max:50'],
            'table_body_html' => ['required', 'string'],
            'so_count_json' => ['nullable'],
        ]);

        $soCountJson = $validated['so_count_json'] ?? null;
        if (is_string($soCountJson)) {
            $soCountJson = json_decode($soCountJson, true);
        }

        OpcrSubmission::where('user_id', $request->user()->id)
            ->update(['is_active' => false]);

        $submission = OpcrSubmission::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'school_year' => $validated['school_year'],
            'semester' => $validated['semester'],
            'table_body_html' => $validated['table_body_html'],
            'so_count_json' => $soCountJson,
            'status' => 'submitted',
            'is_active' => true,
            'submitted_at' => now(),
        ]);

        return response()->json([
            'message' => 'OPCR submitted successfully',
            'id' => $submission->id,
        ]);
    }

    public function show($id)
    {
        $submission = OpcrSubmission::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'submission' => $submission,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => ['nullable', 'string', 'max:255'],
                'school_year' => ['nullable', 'string', 'max:20'],
                'semester' => ['nullable', 'string', 'max:50'],
                'table_body_html' => ['nullable', 'string'],
                'so_count_json' => ['nullable'],
            ]);

            $submission = OpcrSubmission::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $updateData = [];

            if (array_key_exists('title', $validated) && $validated['title'] !== null) {
                $updateData['title'] = $validated['title'];
            }
            if (array_key_exists('school_year', $validated) && $validated['school_year'] !== null) {
                $updateData['school_year'] = $validated['school_year'];
            }
            if (array_key_exists('semester', $validated) && $validated['semester'] !== null) {
                $updateData['semester'] = $validated['semester'];
            }
            if (array_key_exists('table_body_html', $validated)) {
                $updateData['table_body_html'] = $validated['table_body_html'] ?? '';
            }
            if (array_key_exists('so_count_json', $validated) && $validated['so_count_json'] !== null) {
                $soCount = $validated['so_count_json'];
                if (is_string($soCount)) {
                    $soCount = json_decode($soCount, true);
                }
                $updateData['so_count_json'] = $soCount;
            }

            $submission->update($updateData);
            $submission->refresh();

            return response()->json([
                'success' => true,
                'message' => 'OPCR submission updated successfully',
                'submission' => $submission,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating OPCR submission', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    public function setActive($id)
    {
        $userId = auth()->id();

        OpcrSubmission::where('user_id', $userId)
            ->update(['is_active' => false]);

        $submission = OpcrSubmission::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $submission->update(['is_active' => true]);

        return response()->json([
            'success' => true,
            'message' => 'OPCR submission set as active successfully',
        ]);
    }

    public function deactivate($id)
    {
        $userId = auth()->id();

        $submission = OpcrSubmission::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $submission->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'OPCR submission deactivated successfully',
        ]);
    }

    public function destroy($id)
    {
        $userId = auth()->id();
        $user = auth()->user();

        $submission = OpcrSubmission::where('id', $id)->firstOrFail();

        if (!$user->hasRole('admin') && $submission->user_id != $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: You can only delete your own submissions',
            ], 403);
        }

        $submission->delete();

        return response()->json([
            'success' => true,
            'message' => 'OPCR submission deleted successfully',
        ]);
    }
}
