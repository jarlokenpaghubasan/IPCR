<?php

namespace App\Services;

use App\Models\UserPhoto;
use Illuminate\Support\Facades\Log;
use Cloudinary\Cloudinary;

class PhotoService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);
    }

    public function uploadPhoto($file, $user)
    {
        try {
            if (!$file) {
                throw new \Exception('No file provided');
            }

            $timestamp = now()->format('Y-m-d_H-i-s');
            $username = strtolower(str_replace(' ', '-', $user->username));
            $publicId = "user_photos/{$user->id}/{$timestamp}-{$username}";

            // Upload to Cloudinary with transformations
            $uploadResult = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'public_id' => $publicId,
                    'folder' => 'user_photos',
                    'transformation' => [
                        'width' => 1200,
                        'height' => 1200,
                        'crop' => 'limit',
                        'quality' => 'auto:good',
                        'fetch_format' => 'auto'
                    ],
                    'resource_type' => 'image'
                ]
            );

            if (!isset($uploadResult['secure_url'])) {
                throw new \Exception('Cloudinary upload failed - no URL returned');
            }

            // Unset current profile photo
            UserPhoto::where('user_id', $user->id)
                ->where('is_profile_photo', true)
                ->update(['is_profile_photo' => false]);

            // Save photo record to database
            $userPhoto = UserPhoto::create([
                'user_id' => $user->id,
                'filename' => $uploadResult['public_id'],
                'path' => $uploadResult['secure_url'],
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $uploadResult['format'] ?? $file->getClientMimeType(),
                'file_size' => $uploadResult['bytes'] ?? $file->getSize(),
                'is_profile_photo' => true,
            ]);

            return $userPhoto;
        } catch (\Exception $e) {
            Log::error('Photo upload error: ' . $e->getMessage());
            throw new \Exception('Photo upload failed: ' . $e->getMessage());
        }
    }

    public function deletePhoto(UserPhoto $photo)
    {
        try {
            // Extract public_id from Cloudinary URL or use filename
            $publicId = $photo->filename;
            
            // Delete from Cloudinary
            try {
                $this->cloudinary->uploadApi()->destroy($publicId, [
                    'resource_type' => 'image'
                ]);
            } catch (\Exception $e) {
                Log::warning('Cloudinary delete warning: ' . $e->getMessage());
            }

            // Delete from database
            $photo->delete();
        } catch (\Exception $e) {
            Log::error('Photo delete error: ' . $e->getMessage());
            throw new \Exception('Failed to delete photo: ' . $e->getMessage());
        }
    }

    public function deleteAllUserPhotos($user)
    {
        try {
            $photos = UserPhoto::where('user_id', $user->id)->get();
            
            foreach ($photos as $photo) {
                try {
                    $publicId = $photo->filename;
                    $this->cloudinary->uploadApi()->destroy($publicId, [
                        'resource_type' => 'image'
                    ]);
                } catch (\Exception $e) {
                    Log::warning("Failed to delete photo {$photo->id} from Cloudinary: " . $e->getMessage());
                }
            }

            // Delete folder from Cloudinary
            try {
                $this->cloudinary->adminApi()->deleteFolder("user_photos/{$user->id}");
            } catch (\Exception $e) {
                Log::warning('Failed to delete Cloudinary folder: ' . $e->getMessage());
            }

            // Delete all records from database
            UserPhoto::where('user_id', $user->id)->delete();
        } catch (\Exception $e) {
            Log::error('Delete all photos error: ' . $e->getMessage());
            throw new \Exception('Failed to delete user photos: ' . $e->getMessage());
        }
    }

    public function setAsProfilePhoto(UserPhoto $photo)
    {
        try {
            // First, unset all other photos as profile
            UserPhoto::where('user_id', $photo->user_id)
                ->where('is_profile_photo', true)
                ->update(['is_profile_photo' => false]);

            // Then set the selected photo as profile
            // Touch the photo to update its timestamp (for cache busting)
            $photo->update([
                'is_profile_photo' => true,
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Set profile photo error: ' . $e->getMessage());
            throw new \Exception('Failed to set profile photo: ' . $e->getMessage());
        }
    }
}