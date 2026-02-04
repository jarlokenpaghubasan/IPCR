<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\IpcrTemplate;
use App\Models\UserPhoto;
use App\Services\PhotoService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class FacultyDashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard.faculty.index');
    }

    public function myIpcrs(): View
    {
        $templates = IpcrTemplate::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('dashboard.faculty.my-ipcrs', compact('templates'));
    }

    public function profile(): View
    {
        $departments = \App\Models\Department::all();
        $designations = \App\Models\Designation::all();
        
        return view('dashboard.faculty.profile', compact('departments', 'designations'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'errors' => [
                    'current_password' => ['The current password is incorrect.']
                ]
            ], 422);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'message' => 'Password updated successfully!'
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully!',
            'user' => $user
        ]);
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ]);

        $user = auth()->user();
        $photoService = app(PhotoService::class);

        try {
            $photoService->uploadPhoto($request->file('photo'), $user);

            return response()->json([
                'success' => true,
                'message' => 'Photo uploaded successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload photo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPhotos()
    {
        $user = auth()->user();
        $photos = $user->photos()->orderBy('created_at', 'desc')->get()->map(function ($photo) {
            return [
                'id' => $photo->id,
                'url' => $photo->photo_url,
                'is_profile' => $photo->is_profile_photo
            ];
        });

        return response()->json([
            'photos' => $photos
        ]);
    }

    public function setProfilePhoto(Request $request)
    {
        $request->validate([
            'photo_id' => 'required|exists:user_photos,id'
        ]);

        $user = auth()->user();
        $photoService = app(PhotoService::class);

        try {
            $photoService->setProfilePhoto($user, $request->photo_id);

            return response()->json([
                'success' => true,
                'message' => 'Profile photo updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set profile photo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deletePhoto($id)
    {
        $user = auth()->user();
        $photo = UserPhoto::where('user_id', $user->id)->where('id', $id)->firstOrFail();

        $photoService = app(PhotoService::class);

        try {
            $photoService->deletePhoto($photo);

            return response()->json([
                'success' => true,
                'message' => 'Photo deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete photo: ' . $e->getMessage()
            ], 500);
        }
    }
}