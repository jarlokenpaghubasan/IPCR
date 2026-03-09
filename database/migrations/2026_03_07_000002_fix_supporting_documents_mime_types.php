<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Fix mime_type values in supporting_documents table.
 *
 * A bug in SupportingDocumentController::store() stored the Cloudinary
 * resource type (e.g. "image", "raw", "video") instead of the actual
 * MIME type (e.g. "image/jpeg", "application/pdf"). The controller
 * has been fixed to use $file->getClientMimeType(), but existing
 * records need correction.
 *
 * This migration infers the correct MIME type from the file extension
 * in the Cloudinary URL path.
 */
return new class extends Migration
{
    /**
     * Extension-to-MIME mapping for common file types.
     */
    private array $extensionMap = [
        // Images
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'webp' => 'image/webp',
        'svg'  => 'image/svg+xml',
        'bmp'  => 'image/bmp',
        'ico'  => 'image/x-icon',
        'tiff' => 'image/tiff',
        'tif'  => 'image/tiff',

        // Documents
        'pdf'  => 'application/pdf',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt'  => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'txt'  => 'text/plain',
        'csv'  => 'text/csv',
        'rtf'  => 'application/rtf',

        // Video
        'mp4'  => 'video/mp4',
        'avi'  => 'video/x-msvideo',
        'mov'  => 'video/quicktime',
        'wmv'  => 'video/x-ms-wmv',
        'mkv'  => 'video/x-matroska',

        // Audio
        'mp3'  => 'audio/mpeg',
        'wav'  => 'audio/wav',

        // Archives
        'zip'  => 'application/zip',
        'rar'  => 'application/x-rar-compressed',
    ];

    public function up(): void
    {
        // Find records where mime_type looks like a Cloudinary resource type
        // (i.e., does NOT contain '/' which all valid MIME types have)
        $badRecords = DB::table('supporting_documents')
            ->whereNotNull('mime_type')
            ->where('mime_type', '!=', '')
            ->whereRaw("mime_type NOT LIKE '%/%'")
            ->get();

        if ($badRecords->isEmpty()) {
            Log::info('[Migration] No supporting_documents with invalid mime_type found.');
            return;
        }

        Log::info("[Migration] Found {$badRecords->count()} supporting_documents with invalid mime_type.");

        foreach ($badRecords as $record) {
            $newMimeType = $this->inferMimeType($record->path, $record->original_name, $record->mime_type);

            DB::table('supporting_documents')
                ->where('id', $record->id)
                ->update(['mime_type' => $newMimeType]);

            Log::info("[Migration] Fixed supporting_document #{$record->id}: '{$record->mime_type}' → '{$newMimeType}'");
        }
    }

    public function down(): void
    {
        // Not reversible — the original bad values are not preserved.
        // This is a data-correction migration.
    }

    /**
     * Infer the correct MIME type from the URL path or original filename.
     */
    private function inferMimeType(string $path, string $originalName, string $cloudinaryType): string
    {
        // Try to extract extension from the Cloudinary URL
        $extension = $this->extractExtension($path);

        // Fall back to the original filename
        if (!$extension) {
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        }

        if ($extension && isset($this->extensionMap[$extension])) {
            return $this->extensionMap[$extension];
        }

        // Last resort: map the Cloudinary resource type to a generic MIME
        return match ($cloudinaryType) {
            'image' => 'image/jpeg',
            'video' => 'video/mp4',
            'raw'   => 'application/octet-stream',
            default => 'application/octet-stream',
        };
    }

    /**
     * Extract file extension from a Cloudinary URL.
     */
    private function extractExtension(string $url): string
    {
        // URL like: https://res.cloudinary.com/.../filename.jpg
        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) {
            return '';
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // Cloudinary sometimes appends query params; strip anything extra
        $extension = preg_replace('/[^a-z0-9]/', '', $extension);

        return $extension;
    }
};
