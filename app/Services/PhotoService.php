<?php

namespace App\Services;

use App\Models\UserPhoto;
use Illuminate\Support\Facades\Log;

class PhotoService
{
    /**
     * Upload and process user photo - automatically sets as profile photo
     * Filename format: YYYY-MM-DD_HH-MM-SS-username.extension
     */
    public function uploadPhoto($file, $user)
    {
        try {
            // Validate file
            if (!$file) {
                throw new \Exception('No file provided');
            }

            // Create user photo directory if it doesn't exist
            $userPhotoDir = "user_photos/{$user->id}";
            $path = storage_path("app/public/{$userPhotoDir}");
            
            if (!file_exists($path)) {
                if (!mkdir($path, 0755, true)) {
                    throw new \Exception('Failed to create storage directory');
                }
            }

            // Generate filename: YYYY-MM-DD_HH-MM-SS-username.extension
            $extension = strtolower($file->getClientOriginalExtension());
            $timestamp = now()->format('Y-m-d_H-i-s');
            $username = strtolower(str_replace(' ', '-', $user->username));
            $filename = "{$timestamp}-{$username}.{$extension}";
            
            $filePath = "{$userPhotoDir}/{$filename}";
            $fullPath = storage_path("app/public/{$filePath}");

            // Save file directly (no image manipulation)
            $file->move(dirname($fullPath), basename($fullPath));

            if (!file_exists($fullPath)) {
                throw new \Exception('File upload failed');
            }

            // Get file size
            $fileSize = filesize($fullPath);

            // Unset previous profile photo
            UserPhoto::where('user_id', $user->id)
                ->where('is_profile_photo', true)
                ->update(['is_profile_photo' => false]);

            // Save to database and set as profile photo automatically
            $userPhoto = UserPhoto::create([
                'user_id' => $user->id,
                'filename' => $filename,
                'path' => $filePath,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $fileSize,
                'is_profile_photo' => true, // Automatically set as profile photo
            ]);

            return $userPhoto;
        } catch (\Exception $e) {
            Log::error('Photo upload error: ' . $e->getMessage());
            throw new \Exception('Photo upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete a specific photo
     */
    public function deletePhoto(UserPhoto $photo)
    {
        try {
            $fullPath = storage_path("app/public/{$photo->path}");
            
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            $photo->delete();
        } catch (\Exception $e) {
            Log::error('Photo delete error: ' . $e->getMessage());
            throw new \Exception('Failed to delete photo: ' . $e->getMessage());
        }
    }

    /**
     * Delete all photos for a user
     */
    public function deleteAllUserPhotos($user)
    {
        try {
            $userPhotoDir = storage_path("app/public/user_photos/{$user->id}");
            
            if (file_exists($userPhotoDir)) {
                $files = glob("{$userPhotoDir}/*");
                foreach ($files as $file) {
                    if (is_file($file)) {
                        @unlink($file);
                    }
                }
                @rmdir($userPhotoDir);
            }

            UserPhoto::where('user_id', $user->id)->delete();
        } catch (\Exception $e) {
            Log::error('Delete all photos error: ' . $e->getMessage());
            throw new \Exception('Failed to delete user photos: ' . $e->getMessage());
        }
    }

    /**
     * Set a photo as profile photo
     */
    public function setAsProfilePhoto(UserPhoto $photo)
    {
        try {
            // Unset previous profile photo
            UserPhoto::where('user_id', $photo->user_id)
                ->where('is_profile_photo', true)
                ->update(['is_profile_photo' => false]);

            // Set new profile photo
            $photo->update(['is_profile_photo' => true]);
        } catch (\Exception $e) {
            Log::error('Set profile photo error: ' . $e->getMessage());
            throw new \Exception('Failed to set profile photo: ' . $e->getMessage());
        }
    }
}