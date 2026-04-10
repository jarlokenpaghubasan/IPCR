<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\IpcrSavedCopy;
use App\Models\IpcrSubmission;
use App\Models\IpcrTemplate;
use App\Services\ActivityLogService;
use App\Services\IpcrExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IpcrExportController extends Controller
{
    /**
     * Export a submitted IPCR to .xlsx using the IPCR Sample template.
     */
    public function export(Request $request, $id, IpcrExportService $exportService)
    {
        $submission = IpcrSubmission::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->applyExportPeriodOverrides($submission, $request);

        return $this->doExport($exportService, $submission, 'IPCR Submission');
    }

    /**
     * Export an IPCR saved copy to .xlsx.
     */
    public function exportSavedCopy(Request $request, $id, IpcrExportService $exportService)
    {
        $savedCopy = IpcrSavedCopy::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->applyExportPeriodOverrides($savedCopy, $request);

        return $this->doExport($exportService, $savedCopy, 'IPCR Draft');
    }

    /**
     * Export an IPCR template to .xlsx.
     */
    public function exportTemplate(Request $request, $id, IpcrExportService $exportService)
    {
        $template = IpcrTemplate::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->applyExportPeriodOverrides($template, $request);

        return $this->doExport($exportService, $template, 'IPCR Template');
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
     * Common export logic for any IPCR document type.
     */
    private function doExport(IpcrExportService $exportService, $document, string $label)
    {
        try {
            $filePath = $exportService->export($document);

            $fileName = 'IPCR_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $document->title)
                      . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', (string) $document->school_year) . '.xlsx';

            ActivityLogService::log(
                'ipcr_exported',
                "Exported {$label} to Excel: " . $document->title,
                $document
            );

            return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error('IPCR Export Error', [
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
