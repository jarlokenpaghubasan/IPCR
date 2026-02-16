<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\IpcrSavedCopy;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IpcrSavedCopyController extends Controller
{
    /**
     * Get all saved copies for the authenticated user.
     */
    public function index()
    {
        try {
            $savedCopies = IpcrSavedCopy::where('user_id', Auth::id())
                ->orderBy('saved_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'savedCopies' => $savedCopies,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch saved copies',
            ], 500);
        }
    }

    /**
     * Store a new saved copy.
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

            $savedCopy = IpcrSavedCopy::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'school_year' => $request->school_year,
                'semester' => $request->semester,
                'table_body_html' => $request->table_body_html,
                'saved_at' => now(),
            ]);

            ActivityLogService::log('ipcr_draft_saved', 'Saved IPCR draft: ' . $savedCopy->title, $savedCopy);

            return response()->json([
                'success' => true,
                'message' => 'IPCR draft saved successfully',
                'savedCopy' => $savedCopy,
            ]);
        } catch (\Exception $e) {
            \Log::error('IPCR Saved Copy Store Error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save IPCR draft: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified saved copy.
     */
    public function show($id)
    {
        try {
            $savedCopy = IpcrSavedCopy::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'savedCopy' => $savedCopy,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Saved copy not found',
            ], 404);
        }
    }

    /**
     * Update the specified saved copy.
     */
    public function update(Request $request, $id)
    {
        try {
            $savedCopy = IpcrSavedCopy::where('id', $id)
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

            ActivityLogService::log('ipcr_draft_updated', 'Updated IPCR draft: ' . $savedCopy->title, $savedCopy);

            return response()->json([
                'success' => true,
                'message' => 'IPCR draft updated successfully',
                'savedCopy' => $savedCopy->fresh(),
            ]);
        } catch (\Exception $e) {
            \Log::error('IPCR Saved Copy Update Error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update IPCR draft: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified saved copy.
     */
    public function destroy($id)
    {
        try {
            $savedCopy = IpcrSavedCopy::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $title = $savedCopy->title;
            $savedCopy->delete();

            ActivityLogService::log('ipcr_draft_deleted', 'Deleted IPCR draft: ' . $title);

            return response()->json([
                'success' => true,
                'message' => 'Saved copy deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete saved copy',
            ], 500);
        }
    }
}
