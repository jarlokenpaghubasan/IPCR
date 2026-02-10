<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\SupportingDocument;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary as CloudinaryFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupportingDocumentController extends Controller
{

    /**
     * Get all supporting documents for a specific SO.
     * Queries across both template and submission types for sync.
     * Supports optional owner_id for dean viewing faculty documents.
     */
    public function index(Request $request)
    {
        $request->validate([
            'documentable_type' => 'required|string|in:ipcr_submission,opcr_submission,ipcr_template,opcr_template',
            'documentable_id' => 'required|integer',
            'so_label' => 'required|string',
            'owner_id' => 'nullable|integer',
        ]);

        // Determine which user's documents to fetch
        $userId = auth()->id();
        if ($request->filled('owner_id')) {
            // Dean/admin viewing another user's documents
            $userId = (int) $request->owner_id;
        }

        // Query across both template and submission types for sync
        $isIpcr = str_starts_with($request->documentable_type, 'ipcr');
        $familyTypes = $isIpcr
            ? ['ipcr_template', 'ipcr_submission']
            : ['opcr_template', 'opcr_submission'];

        $documents = SupportingDocument::where('user_id', $userId)
            ->whereIn('documentable_type', $familyTypes)
            ->where('so_label', $request->so_label)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('path') // Deduplicate same Cloudinary file copied between template/submission
            ->values()
            ->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'original_name' => $doc->original_name,
                    'path' => $doc->path,
                    'mime_type' => $doc->mime_type,
                    'file_size_human' => $doc->file_size_human,
                    'created_at' => $doc->created_at->format('M d, Y h:i A'),
                ];
            });

        return response()->json([
            'success' => true,
            'documents' => $documents,
        ]);
    }

    /**
     * Upload a supporting document for a specific SO.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'documentable_type' => 'required|string|in:ipcr_submission,opcr_submission,ipcr_template,opcr_template',
            'documentable_id' => 'required|integer',
            'so_label' => 'required|string',
        ]);

        try {
            $file = $request->file('file');
            $user = auth()->user();
            $timestamp = now()->format('Y-m-d_H-i-s');
            $safeOrigName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeOrigName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $safeOrigName);

            // Neat path: user_photos/{user_id}/supporting_docs/{type}/{doc_id}/{so_label}/{timestamp}_{filename}
            $soFolder = str_replace(' ', '_', strtolower($request->so_label));
            $folderPath = "user_photos/{$user->id}/supporting_docs/{$request->documentable_type}/{$request->documentable_id}/{$soFolder}";

            Log::info('Starting Cloudinary upload', [
                'user_id' => $user->id,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'folder' => $folderPath,
            ]);

            // Upload using Laravel Cloudinary facade
            $uploadResult = CloudinaryFacade::upload($file->getRealPath(), [
                'folder' => $folderPath,
                'resource_type' => 'auto',
                'timeout' => 120,
            ]);

            Log::info('Cloudinary upload successful', ['url' => $uploadResult->getSecurePath()]);

            $document = SupportingDocument::create([
                'user_id' => $user->id,
                'documentable_type' => $request->documentable_type,
                'documentable_id' => $request->documentable_id,
                'so_label' => $request->so_label,
                'filename' => $uploadResult->getPublicId(),
                'path' => $uploadResult->getSecurePath(),
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $uploadResult->getFileType() ?? $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully.',
                'document' => [
                    'id' => $document->id,
                    'original_name' => $document->original_name,
                    'path' => $document->path,
                    'mime_type' => $document->mime_type,
                    'file_size_human' => $document->file_size_human,
                    'created_at' => $document->created_at->format('M d, Y h:i A'),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Supporting document upload error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a supporting document.
     */
    public function destroy($id)
    {
        $document = SupportingDocument::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        try {
            if ($document->filename) {
                try {
                    CloudinaryFacade::destroy($document->filename);
                } catch (\Exception $e) {
                    Log::warning('Failed to delete from Cloudinary: ' . $e->getMessage());
                }
            }

            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Supporting document delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Rename a supporting document.
     * Updates both database and Cloudinary public_id for cleaner file management.
     */
    public function rename(Request $request, $id)
    {
        $request->validate([
            'original_name' => 'required|string|max:255',
        ]);

        $document = SupportingDocument::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        try {
            // Extract path info from current filename
            $oldPublicId = $document->filename;
            $pathParts = explode('/', $oldPublicId);
            $oldFilename = array_pop($pathParts);
            $folderPath = implode('/', $pathParts);

            // Create new filename from the new original_name
            $newOriginalName = $request->original_name;
            $extension = pathinfo($newOriginalName, PATHINFO_EXTENSION);
            $nameWithoutExt = pathinfo($newOriginalName, PATHINFO_FILENAME);
            $safeNewName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nameWithoutExt);
            $timestamp = now()->format('Y-m-d_H-i-s');
            $newFilename = "{$timestamp}_{$safeNewName}";
            $newPublicId = $folderPath ? "{$folderPath}/{$newFilename}" : $newFilename;

            // Rename in Cloudinary
            try {
                CloudinaryFacade::rename($oldPublicId, $newPublicId);
                
                // Get new URL
                $newUrl = CloudinaryFacade::getUrl($newPublicId);
                
                // Update database
                $document->filename = $newPublicId;
                $document->path = $newUrl;
                $document->original_name = $newOriginalName;
                $document->save();

                Log::info('Document renamed in Cloudinary', [
                    'old_public_id' => $oldPublicId,
                    'new_public_id' => $newPublicId,
                ]);
            } catch (\Exception $cloudinaryError) {
                // If Cloudinary rename fails, just update the display name
                Log::warning('Cloudinary rename failed, updating display name only: ' . $cloudinaryError->getMessage());
                $document->original_name = $newOriginalName;
                $document->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Document renamed successfully.',
                'document' => [
                    'id' => $document->id,
                    'original_name' => $document->original_name,
                    'path' => $document->path,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Supporting document rename error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Rename failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download a supporting document with proper filename.
     * Allows faculty to download their own docs, and deans to download faculty docs.
     */
    public function download($id)
    {
        $document = SupportingDocument::findOrFail($id);
        
        // Check authorization: owner or dean role
        $user = auth()->user();
        $canDownload = $document->user_id === $user->id || $user->hasRole('dean') || $user->hasRole('admin');
        
        if (!$canDownload) {
            abort(403, 'Unauthorized');
        }

        try {
            // Fetch file content from Cloudinary
            $fileContent = file_get_contents($document->path);
            
            if ($fileContent === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch file from storage.',
                ], 500);
            }

            // Return file with download headers
            return response($fileContent)
                ->header('Content-Type', $document->mime_type ?? 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $document->original_name . '"')
                ->header('Content-Length', strlen($fileContent));
        } catch (\Exception $e) {
            Log::error('Supporting document download error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Download failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
