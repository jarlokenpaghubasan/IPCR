<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\OpcrSavedCopy;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpcrSavedCopyController extends Controller
{
    /**
     * Get all OPCR saved copies for the authenticated user.
     */
    public function index()
    {
        try {
            $savedCopies = OpcrSavedCopy::where('user_id', Auth::id())
                ->orderBy('saved_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'savedCopies' => $savedCopies,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch OPCR saved copies',
            ], 500);
        }
    }

    /**
     * Store a new OPCR saved copy.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'school_year' => 'required|string|max:255',
                'semester' => 'required|string|max:255',
                'table_body_html' => 'required|string',
            ]);

            $savedCopy = OpcrSavedCopy::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'school_year' => $request->school_year,
                'semester' => $request->semester,
                'table_body_html' => $request->table_body_html,
                'saved_at' => now(),
            ]);

            ActivityLogService::log('opcr_draft_saved', 'Saved OPCR draft: ' . $savedCopy->title, $savedCopy);

            return response()->json([
                'success' => true,
                'message' => 'OPCR draft saved successfully',
                'savedCopy' => $savedCopy,
            ]);
        } catch (\Exception $e) {
            \Log::error('OPCR Saved Copy Store Error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save OPCR draft: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified OPCR saved copy.
     */
    public function show($id)
    {
        try {
            $savedCopy = OpcrSavedCopy::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'savedCopy' => $savedCopy,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'OPCR saved copy not found',
            ], 404);
        }
    }

    /**
     * Update the specified OPCR saved copy.
     */
    public function update(Request $request, $id)
    {
        try {
            $savedCopy = OpcrSavedCopy::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $request->validate([
                'title' => 'required|string|max:255',
                'school_year' => 'required|string|max:255',
                'semester' => 'required|string|max:255',
                'table_body_html' => 'required|string',
            ]);

            $savedCopy->update([
                'title' => $request->title,
                'school_year' => $request->school_year,
                'semester' => $request->semester,
                'table_body_html' => $request->table_body_html,
                'saved_at' => now(),
            ]);

            ActivityLogService::log('opcr_draft_updated', 'Updated OPCR draft: ' . $savedCopy->title, $savedCopy);

            return response()->json([
                'success' => true,
                'message' => 'OPCR draft updated successfully',
                'savedCopy' => $savedCopy->fresh(),
            ]);
        } catch (\Exception $e) {
            \Log::error('OPCR Saved Copy Update Error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update OPCR draft: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified OPCR saved copy.
     */
    public function destroy($id)
    {
        try {
            $savedCopy = OpcrSavedCopy::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $title = $savedCopy->title;
            $savedCopy->delete();

            ActivityLogService::log('opcr_draft_deleted', 'Deleted OPCR draft: ' . $title);

            return response()->json([
                'success' => true,
                'message' => 'OPCR saved copy deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete OPCR saved copy',
            ], 500);
        }
    }
}
