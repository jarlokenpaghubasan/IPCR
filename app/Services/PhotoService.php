<?php

namespace App\Services;

use App\Models\UserPhoto;
use Illuminate\Support\Facades\Log;

class PhotoService
{
    public function uploadPhoto($file, $user)
    {
        try {
            if (!$file) {
                throw new \Exception('No file provided');
            }

            // Upload to Cloudinary with automatic optimization
            $uploadApi = new \Cloudinary\Api\Upload\UploadApi();
            $result = $uploadApi->upload($file->getRealPath(), [
                'folder' => 'user_photos/' . $user->id,
                'transformation' => [
                    'width' => 1200,
                    'height' => 1200,
                    'crop' => 'limit',
                    'quality' => 'auto',
                    'fetch_format' => 'auto'
                ]
            ]);

            if (!$result || !isset($result['secure_url']) || !isset($result['public_id'])) {
                Log::error('Cloudinary response: ' . json_encode($result));
                throw new \Exception('Invalid Cloudinary response');
            }

            $cloudinaryUrl = $result['secure_url'];
            $cloudinaryPublicId = $result['public_id'];

            UserPhoto::where('user_id', $user->id)
                ->where('is_profile_photo', true)
                ->update(['is_profile_photo' => false]);

            $userPhoto = UserPhoto::create([
                'user_id' => $user->id,
                'filename' => basename($cloudinaryUrl),
                'path' => $cloudinaryUrl,
                'cloudinary_public_id' => $cloudinaryPublicId,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'is_profile_photo' => true, 
            ]);

            return $userPhoto;
        } catch (\Exception $e) {
            Log::error('Photo upload error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw new \Exception('Photo upload failed: ' . $e->getMessage());
        }
    }

    public function deletePhoto(UserPhoto $photo)
    {
        try {
            // Delete from Cloudinary
            if ($photo->cloudinary_public_id) {
                $uploadApi = new \Cloudinary\Api\Upload\UploadApi();
                $uploadApi->destroy($photo->cloudinary_public_id);
            }

            $photo->delete();
        } catch (\Exception $e) {
            Log::error('Photo delete error: ' . $e->getMessage());
            throw new \Exception('Failed to delete photo: ' . $e->getMessage());
        }
    }

    public function deleteAllUserPhotos($user)
    {
        try {
            // Delete all photos from Cloudinary
            $photos = UserPhoto::where('user_id', $user->id)->get();
            
            if ($photos->count() > 0) {
                $uploadApi = new \Cloudinary\Api\Upload\UploadApi();
                
                foreach ($photos as $photo) {
                    if ($photo->cloudinary_public_id) {
                        $uploadApi->destroy($photo->cloudinary_public_id);
                    }
                }
            }

            UserPhoto::where('user_id', $user->id)->delete();
        } catch (\Exception $e) {
            Log::error('Delete all photos error: ' . $e->getMessage());
            throw new \Exception('Failed to delete user photos: ' . $e->getMessage());
        }
    }

    public function setAsProfilePhoto(UserPhoto $photo)
    {
        try {
            UserPhoto::where('user_id', $photo->user_id)
                ->where('is_profile_photo', true)
                ->update(['is_profile_photo' => false]);

            $photo->update(['is_profile_photo' => true]);
        } catch (\Exception $e) {
            Log::error('Set profile photo error: ' . $e->getMessage());
            throw new \Exception('Failed to set profile photo: ' . $e->getMessage());
        }
    }
}