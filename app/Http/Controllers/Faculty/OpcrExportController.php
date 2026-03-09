<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\OpcrSavedCopy;
use App\Models\OpcrSubmission;
use App\Models\OpcrTemplate;
use App\Services\ActivityLogService;
use App\Services\OpcrExportService;
use Illuminate\Support\Facades\Auth;

class OpcrExportController extends Controller
{
    /**
     * Export a submitted OPCR to .xlsx.
     */
    public function export($id, OpcrExportService $exportService)
    {
        $submission = OpcrSubmission::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return $this->doExport($exportService, $submission, 'OPCR Submission');
    }

    /**
     * Export an OPCR saved copy to .xlsx.
     */
    public function exportSavedCopy($id, OpcrExportService $exportService)
    {
        $savedCopy = OpcrSavedCopy::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return $this->doExport($exportService, $savedCopy, 'OPCR Draft');
    }

    /**
     * Export an OPCR template to .xlsx.
     */
    public function exportTemplate($id, OpcrExportService $exportService)
    {
        $template = OpcrTemplate::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return $this->doExport($exportService, $template, 'OPCR Template');
    }

    /**
     * Common export logic for any OPCR document type.
     */
    private function doExport(OpcrExportService $exportService, $document, string $label)
    {
        try {
            $filePath = $exportService->export($document);

            $fileName = 'OPCR_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $document->title)
                      . '_' . $document->school_year . '.xlsx';

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
