<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\IpcrTemplate;
use App\Models\IpcrSubmission;
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
        // Get the active submission for the user (fallback to latest submission)
        $activeSubmission = IpcrSubmission::where('user_id', auth()->id())
            ->where('is_active', true)
            ->orderBy('submitted_at', 'desc')
            ->first();

        if (!$activeSubmission) {
            $activeSubmission = IpcrSubmission::where('user_id', auth()->id())
                ->orderBy('submitted_at', 'desc')
                ->first();
        }

        // Initialize counters
        $strategicObjectivesCount = 0;
        $coreFunctionsCount = 0;
        $supportFunctionsCount = 0;

        // Use JSON data from active submission if available
        if ($activeSubmission && $activeSubmission->so_count_json) {
            \Log::info('Active submission found with SO counts', [
                'submission_id' => $activeSubmission->id,
                'so_count_json' => $activeSubmission->so_count_json,
            ]);

            $counts = $activeSubmission->so_count_json;
            $strategicObjectivesCount = $counts['strategic_objectives'] ?? 0;
            $coreFunctionsCount = $counts['core_functions'] ?? 0;
            $supportFunctionsCount = $counts['support_functions'] ?? 0;
        } elseif ($activeSubmission && $activeSubmission->table_body_html) {
            try {
                $html = $activeSubmission->table_body_html;
                $dom = new \DOMDocument();
                libxml_use_internal_errors(true);
                $dom->loadHTML('<table><tbody>' . $html . '</tbody></table>');
                libxml_clear_errors();

                $rows = $dom->getElementsByTagName('tr');
                $currentSection = null;

                foreach ($rows as $row) {
                    $classAttr = $row->attributes->getNamedItem('class');
                    $className = $classAttr ? $classAttr->nodeValue : '';

                    if (str_contains($className, 'bg-green-100')) {
                        $currentSection = 'strategic_objectives';
                    } elseif (str_contains($className, 'bg-purple-100')) {
                        $currentSection = 'core_functions';
                    } elseif (str_contains($className, 'bg-orange-100')) {
                        $currentSection = 'support_functions';
                    } elseif (str_contains($className, 'bg-gray-100')) {
                        $currentSection = null;
                    }

                    if ($currentSection && str_contains($className, 'bg-blue-100')) {
                        if ($currentSection === 'strategic_objectives') {
                            $strategicObjectivesCount++;
                        } elseif ($currentSection === 'core_functions') {
                            $coreFunctionsCount++;
                        } elseif ($currentSection === 'support_functions') {
                            $supportFunctionsCount++;
                        }
                    }
                }
            } catch (\Throwable $e) {
                \Log::error('Failed to parse SO counts from submission table_body_html', [
                    'submission_id' => $activeSubmission->id,
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            \Log::info('No active submission or no so_count_json', [
                'has_submission' => $activeSubmission ? true : false,
                'submission_id' => $activeSubmission->id ?? null,
                'so_count_json' => $activeSubmission->so_count_json ?? null,
            ]);
        }

        // TODO: Replace with actual accomplished counts from database
        $strategicObjectivesAccomplished = 0;
        $coreFunctionsAccomplished = 0;
        $supportFunctionsAccomplished = 0;

        // Format the text display (e.g., "0/3" where 0 is accomplished and 3 is total SOs)
        // Show "N/A" when total is 0, otherwise show "accomplished/total"
        $strategicObjectivesText = $strategicObjectivesCount > 0 
            ? "$strategicObjectivesAccomplished/$strategicObjectivesCount" 
            : "N/A";
        $coreFunctionsText = $coreFunctionsCount > 0 
            ? "$coreFunctionsAccomplished/$coreFunctionsCount" 
            : "N/A";
        $supportFunctionsText = $supportFunctionsCount > 0 
            ? "$supportFunctionsAccomplished/$supportFunctionsCount" 
            : "N/A";

        // Calculate percentages based on accomplished/total
        $strategicObjectivesPercent = $strategicObjectivesCount > 0 
            ? round(($strategicObjectivesAccomplished / $strategicObjectivesCount) * 100) . "%" 
            : "0%";
        $coreFunctionsPercent = $coreFunctionsCount > 0 
            ? round(($coreFunctionsAccomplished / $coreFunctionsCount) * 100) . "%" 
            : "0%";
        $supportFunctionsPercent = $supportFunctionsCount > 0 
            ? round(($supportFunctionsAccomplished / $supportFunctionsCount) * 100) . "%" 
            : "0%";

        // Dummy IPCR Progress values
        $ipcrAccomplishedText = "0/0";
        $ipcrPercentageValue = 0;
        $ipcrPercentageText = "0%";

        return view('dashboard.faculty.index', compact(
            'strategicObjectivesText',
            'strategicObjectivesPercent',
            'coreFunctionsText',
            'coreFunctionsPercent',
            'supportFunctionsText',
            'supportFunctionsPercent',
            'ipcrAccomplishedText',
            'ipcrPercentageValue',
            'ipcrPercentageText'
        ));
    }

    public function myIpcrs(): View
    {
        $templates = IpcrTemplate::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        $submissions = IpcrSubmission::where('user_id', auth()->id())
            ->orderBy('submitted_at', 'desc')
            ->get();

        $opcrSubmissions = \App\Models\OpcrSubmission::where('user_id', auth()->id())
            ->orderBy('submitted_at', 'desc')
            ->get();

        $departmentName = auth()->user()->department?->name ?? 'Your Department';
        $departmentCode = auth()->user()->department?->code ?? '';
            
        return view('dashboard.faculty.my-ipcrs', compact(
            'templates', 'submissions', 'opcrSubmissions',
            'departmentName', 'departmentCode'
        ));
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