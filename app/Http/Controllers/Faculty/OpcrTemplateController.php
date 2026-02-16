<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\OpcrTemplate;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpcrTemplateController extends Controller
{
    /**
     * Get all OPCR templates for the current user.
     */
    public function index(Request $request)
    {
        try {
            $templates = OpcrTemplate::where('user_id', Auth::id())
                ->select(['id', 'title', 'school_year', 'semester', 'created_at'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'templates' => $templates,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching OPCR templates: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch templates',
            ], 500);
        }
    }

    /**
     * Store a newly created OPCR template.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'nullable|string|max:255',
                'strategic_objectives' => 'nullable|array',
                'headers' => 'nullable|array',
            ]);

            $strategicObjectives = $request->input('strategic_objectives', []);
            $headers = $request->input('headers', []);

            if (!is_array($strategicObjectives)) { $strategicObjectives = []; }
            if (!is_array($headers)) { $headers = []; }

            $strategicObjectives = array_values($strategicObjectives);
            $headers = array_values($headers);

            $contentArray = [
                'strategic_objectives' => $strategicObjectives,
                'headers' => $headers,
            ];

            $contentJson = json_encode($contentArray);

            $template = OpcrTemplate::create([
                'user_id' => Auth::id(),
                'title' => $request->input('title', 'OPCR Template'),
                'period' => 'January - June 2026',
                'content' => $contentJson,
            ]);

            ActivityLogService::log('opcr_template_created', 'Created OPCR template: ' . $template->title, $template);

            return response()->json([
                'success' => true,
                'message' => 'OPCR template saved successfully',
                'template' => $template,
            ]);
        } catch (\Exception $e) {
            \Log::error('OPCR Store Error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save OPCR template: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified OPCR template.
     */
    public function show($id)
    {
        try {
            $template = OpcrTemplate::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'template' => $template,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Template not found',
            ], 404);
        }
    }

    /**
     * Remove the specified OPCR template.
     */
    public function destroy($id)
    {
        try {
            $template = OpcrTemplate::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $title = $template->title;
            $template->delete();

            ActivityLogService::log('opcr_template_deleted', 'Deleted OPCR template: ' . $title);

            return response()->json([
                'success' => true,
                'message' => 'OPCR template deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete OPCR template',
            ], 500);
        }
    }

    /**
     * Update the specified OPCR template.
     */
    public function update(Request $request, $id)
    {
        try {
            $template = OpcrTemplate::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $request->validate([
                'title' => 'nullable|string|max:255',
                'strategic_objectives' => 'nullable|array',
                'headers' => 'nullable|array',
            ]);

            $strategicObjectives = $request->input('strategic_objectives', []);
            $headers = $request->input('headers', []);

            if (!is_array($strategicObjectives)) { $strategicObjectives = []; }
            if (!is_array($headers)) { $headers = []; }

            $strategicObjectives = array_values($strategicObjectives);
            $headers = array_values($headers);

            $contentArray = [
                'strategic_objectives' => $strategicObjectives,
                'headers' => $headers,
            ];

            $contentJson = json_encode($contentArray);

            $template->update([
                'title' => $request->input('title', $template->title),
                'content' => $contentJson,
            ]);

            ActivityLogService::log('opcr_template_updated', 'Updated OPCR template: ' . $template->title, $template);

            return response()->json([
                'success' => true,
                'message' => 'OPCR template updated successfully',
                'template' => $template->fresh(),
            ]);
        } catch (\Exception $e) {
            \Log::error('OPCR Update Error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update OPCR template: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a template from a saved copy.
     */
    public function storeFromSavedCopy(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'school_year' => 'nullable|string|max:255',
                'semester' => 'nullable|string|max:255',
                'table_body_html' => 'required|string',
                'so_count_json' => 'nullable|array',
            ]);

            $existingTemplate = OpcrTemplate::where('user_id', Auth::id())
                ->whereRaw('BINARY title = ?', [$request->title])
                ->first();

            if ($existingTemplate) {
                $existingTemplate->update([
                    'school_year' => $request->school_year,
                    'semester' => $request->semester,
                    'period' => $request->school_year ? $request->school_year : 'N/A',
                    'table_body_html' => $request->table_body_html,
                    'so_count_json' => $request->so_count_json,
                ]);

                ActivityLogService::log('opcr_template_updated', 'Updated OPCR template from saved copy: ' . $existingTemplate->title, $existingTemplate);

                return response()->json([
                    'success' => true,
                    'message' => 'OPCR template updated successfully',
                    'template' => $existingTemplate,
                    'updated' => true,
                ]);
            }

            $template = OpcrTemplate::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'period' => $request->school_year ? $request->school_year : 'N/A',
                'school_year' => $request->school_year,
                'semester' => $request->semester,
                'content' => json_encode([]),
                'table_body_html' => $request->table_body_html,
                'so_count_json' => $request->so_count_json,
            ]);

            ActivityLogService::log('opcr_template_created', 'Created OPCR template from saved copy: ' . $template->title, $template);

            return response()->json([
                'success' => true,
                'message' => 'OPCR template saved successfully',
                'template' => $template,
                'updated' => false,
            ]);
        } catch (\Exception $e) {
            \Log::error('OPCR Store From Saved Copy Error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save OPCR template: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Set a template as active.
     */
    public function setActive($id)
    {
        try {
            OpcrTemplate::where('user_id', Auth::id())
                ->update(['is_active' => false]);

            $template = OpcrTemplate::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $template->update(['is_active' => true]);

            ActivityLogService::log('opcr_template_activated', 'Set OPCR template as active: ' . $template->title, $template);

            return response()->json([
                'success' => true,
                'message' => 'OPCR template set as active successfully',
            ]);
        } catch (\Exception $e) {
            \Log::error('OPCR Set Active Error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to set OPCR template as active: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save a copy from template.
     */
    public function saveCopy($id)
    {
        try {
            $template = OpcrTemplate::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $savedCopy = \App\Models\OpcrSavedCopy::create([
                'user_id' => Auth::id(),
                'title' => $template->title,
                'school_year' => $template->school_year,
                'semester' => $template->semester,
                'table_body_html' => $template->table_body_html,
                'saved_at' => now(),
            ]);

            ActivityLogService::log('opcr_template_copy_saved', 'Saved copy of OPCR template: ' . $template->title, $template);

            return response()->json([
                'success' => true,
                'message' => 'OPCR template saved to Saved Copy successfully',
                'saved_copy_id' => $savedCopy->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('OPCR Save Copy Error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save OPCR copy: ' . $e->getMessage(),
            ], 500);
        }
    }
}
