<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\IpcrTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IpcrTemplateController extends Controller
{
    /**
     * Get all templates for the current user.
     */
    public function index(Request $request)
    {
        try {
            $templates = IpcrTemplate::where('user_id', Auth::id())
                ->select(['id', 'title', 'school_year', 'semester', 'created_at'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'templates' => $templates,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching templates: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch templates',
            ], 500);
        }
    }

    /**
     * Store a newly created IPCR template.
     */
    public function store(Request $request)
    {
        try {
            \Log::info('==== IPCR STORE REQUEST ====');
            \Log::info('Request all', ['data' => $request->all()]);
            
            $request->validate([
                'title' => 'nullable|string|max:255',
                'strategic_objectives' => 'nullable|array',
                'headers' => 'nullable|array',
            ]);

            // Use input() to get form data, not ->headers which gets HTTP headers!
            $strategicObjectives = $request->input('strategic_objectives', []);
            $headers = $request->input('headers', []);
            
            \Log::info('Strategic objectives from input', ['data' => $strategicObjectives, 'type' => gettype($strategicObjectives)]);
            \Log::info('Headers from input', ['data' => $headers, 'type' => gettype($headers)]);
            
            // Ensure they are arrays
            if (!is_array($strategicObjectives)) {
                $strategicObjectives = [];
            }
            if (!is_array($headers)) {
                $headers = [];
            }
            
            // Re-index arrays to ensure they're proper arrays, not objects
            $strategicObjectives = array_values($strategicObjectives);
            $headers = array_values($headers);
            
            \Log::info('After processing', ['strategic_objectives' => $strategicObjectives, 'headers' => $headers]);
            
            $contentArray = [
                'strategic_objectives' => $strategicObjectives,
                'headers' => $headers,
            ];
            
            $contentJson = json_encode($contentArray);
            \Log::info('Content JSON', ['json' => $contentJson]);

            $template = IpcrTemplate::create([
                'user_id' => Auth::id(),
                'title' => $request->input('title', 'IPCR Template'),
                'period' => 'January - June 2026',
                'content' => $contentJson,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'IPCR template saved successfully',
                'template' => $template,
            ]);
        } catch (\Exception $e) {
            \Log::error('IPCR Store Error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save IPCR template: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified IPCR template.
     */
    public function show($id)
    {
        try {
            $template = IpcrTemplate::where('id', $id)
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
     * Remove the specified IPCR template.
     */
    public function destroy($id)
    {
        try {
            $template = IpcrTemplate::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $template->delete();

            return response()->json([
                'success' => true,
                'message' => 'Template deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete template',
            ], 500);
        }
    }

    /**
     * Update the specified IPCR template.
     */
    public function update(Request $request, $id)
    {
        try {
            \Log::info('==== IPCR UPDATE REQUEST ====');
            \Log::info('Template ID', ['id' => $id]);
            \Log::info('Request all', ['data' => $request->all()]);
            
            $template = IpcrTemplate::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $request->validate([
                'title' => 'nullable|string|max:255',
                'strategic_objectives' => 'nullable|array',
                'headers' => 'nullable|array',
            ]);

            $strategicObjectives = $request->input('strategic_objectives', []);
            $headers = $request->input('headers', []);
            
            // Ensure they are arrays
            if (!is_array($strategicObjectives)) {
                $strategicObjectives = [];
            }
            if (!is_array($headers)) {
                $headers = [];
            }
            
            // Re-index arrays
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

            return response()->json([
                'success' => true,
                'message' => 'IPCR template updated successfully',
                'template' => $template->fresh(),
            ]);
        } catch (\Exception $e) {
            \Log::error('IPCR Update Error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update IPCR template: ' . $e->getMessage(),
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

            // Check if template with exact same title exists (case-sensitive)
            $existingTemplate = IpcrTemplate::where('user_id', Auth::id())
                ->whereRaw('BINARY title = ?', [$request->title])
                ->first();

            if ($existingTemplate) {
                // Update existing template
                $existingTemplate->update([
                    'school_year' => $request->school_year,
                    'semester' => $request->semester,
                    'period' => $request->school_year ? $request->school_year : 'N/A',
                    'table_body_html' => $request->table_body_html,
                    'so_count_json' => $request->so_count_json,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Template updated successfully',
                    'template' => $existingTemplate,
                    'updated' => true,
                ]);
            }

            // Create new template if no match found
            $template = IpcrTemplate::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'period' => $request->school_year ? $request->school_year : 'N/A',
                'school_year' => $request->school_year,
                'semester' => $request->semester,
                'content' => json_encode([]),
                'table_body_html' => $request->table_body_html,
                'so_count_json' => $request->so_count_json,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Template saved successfully',
                'template' => $template,
                'updated' => false,
            ]);
        } catch (\Exception $e) {
            \Log::error('IPCR Store From Saved Copy Error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save template: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Set a template as active.
     */
    public function setActive($id)
    {
        try {
            // First, set all user's templates to inactive
            IpcrTemplate::where('user_id', Auth::id())
                ->update(['is_active' => false]);

            // Then set the selected template as active
            $template = IpcrTemplate::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $template->update(['is_active' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Template set as active successfully',
            ]);
        } catch (\Exception $e) {
            \Log::error('IPCR Set Active Error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to set template as active: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function saveCopy($id)
    {
        try {
            // Find the template
            $template = IpcrTemplate::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Create a saved copy (draft) from the template
            $savedCopy = \App\Models\IpcrSavedCopy::create([
                'user_id' => Auth::id(),
                'title' => $template->title,
                'school_year' => $template->school_year,
                'semester' => $template->semester,
                'table_body_html' => $template->table_body_html,
                'saved_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Template saved to Saved Copy successfully',
                'saved_copy_id' => $savedCopy->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('IPCR Save Copy Error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save copy: ' . $e->getMessage(),
            ], 500);
        }
    }
}
