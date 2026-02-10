<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit User - Admin Dashboard</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/urs_logo.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    @vite(['resources/css/admin_users_edit.css', 'resources/js/admin_users_edit.js'])
</head>
<body class="bg-gray-50">
    <div class="flex h-screen bg-gray-50">
        <!-- Mobile Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden" onclick="toggleSidebar()"></div>

        <!-- Sidebar Navigation -->
        <div id="sidebar" class="sidebar-hidden fixed lg:relative inset-y-0 left-0 w-64 bg-white shadow-lg z-40">
            <div class="p-6 border-b">
                <h1 class="text-xl font-bold text-gray-900">Admin Panel</h1>
                <p class="text-sm text-gray-600">IPCR/OPCR Module</p>
            </div>

            <nav class="p-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="fas fa-home w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-semibold">
                    <i class="fas fa-users w-5"></i>
                    <span>User Management</span>
                </a>

                <hr class="my-4">

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition w-full">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <div class="bg-white shadow">
                <div class="px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center gap-4">
                    <!-- Hamburger Menu Button (mobile only) -->
                    <button id="hamburgerBtn" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-bars text-gray-700 text-xl"></i>
                    </button>

                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg sm:text-2xl font-bold text-gray-900">Edit User</h2>
                        <p class="text-gray-600 text-xs sm:text-sm truncate">{{ $user->name }}</p>
                    </div>
                    <div class="flex items-center gap-3 text-right whitespace-nowrap hidden sm:flex">
                        <div class="text-right">
                            <p class="text-gray-900 font-semibold text-sm">{{ auth()->user()->name }}</p>
                            <p class="text-gray-600 text-xs">Admin</p>
                        </div>
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover">
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="flex-1 overflow-auto p-4 sm:p-6 lg:p-8">
                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-900 mb-6 inline-flex items-center gap-2 text-sm">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <h3 class="text-red-800 font-semibold mb-2 text-sm">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside text-red-700 text-xs space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3 text-sm">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Photo Section (Left/Top on mobile, Left on desktop) -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow p-4 lg:p-6 lg:sticky lg:top-0">
                            <h3 class="text-base lg:text-lg font-semibold text-gray-900 mb-3 lg:mb-4">Profile Photo</h3>

                            <!-- Current Profile Photo -->
                            <div class="mb-4 lg:mb-6">
                                <div class="w-full aspect-square bg-gray-200 rounded-lg mb-2 flex items-center justify-center border-2 border-dashed border-gray-400 overflow-hidden">
                                    @if($user->hasProfilePhoto())
                                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user text-gray-400 text-3xl lg:text-5xl"></i>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-600 text-center">@if($user->hasProfilePhoto())Current Profile Photo @else No Profile Photo @endif</p>
                            </div>

                            <!-- Photo Upload Form -->
                            <div class="border-t pt-4 lg:pt-6">
                                <label class="block text-xs lg:text-sm font-medium text-gray-700 mb-2 lg:mb-3">Upload Photo</label>
                                <form id="photoUploadForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3 lg:mb-4 flex gap-2 sm:gap-3">
                                        <input type="file" id="photoInput" name="photo" accept="image/*" class="hidden">
                                        <button type="button" onclick="document.getElementById('photoInput').click()" class="flex-1 px-3 lg:px-4 py-1.5 lg:py-2 border-2 border-dashed border-blue-300 rounded-lg text-center text-blue-600 hover:bg-blue-50 transition cursor-pointer text-xs lg:text-sm whitespace-nowrap">
                                            <i class="fas fa-cloud-upload-alt mr-1"></i>
                                            <span id="uploadText" class="hidden sm:inline">Choose Photo</span>
                                            <span id="uploadTextShort" class="sm:hidden">Upload</span>
                                        </button>
                                    </div>
                                    <div id="uploadProgress" class="hidden mb-2 lg:mb-3">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all" style="width: 0%"></div>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1">Uploading...</p>
                                    </div>
                                    <div id="uploadMessage" class="text-xs mb-2 lg:mb-3"></div>
                                    <p class="text-xs text-gray-500">Max 5MB • JPEG, PNG, GIF, WebP</p>
                                </form>
                            </div>

                            <!-- All Photos -->
                            <div class="border-t pt-4 lg:pt-6 mt-4 lg:mt-6">
                                <h4 class="font-semibold text-gray-900 mb-2 lg:mb-3 text-xs lg:text-sm">All Photos (<span id="photoCount">0</span>)</h4>
                                <div id="allPhotos" class="grid grid-cols-4 lg:grid-cols-3 gap-1.5 lg:gap-2 max-h-48 lg:max-h-64 overflow-y-auto">
                                    <!-- Photos loaded via AJAX -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Form (Right on desktop, below photo on mobile) -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow p-6">
                            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <!-- Personal Information -->
                                <div class="border-b pb-6">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Full Name -->
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                            @error('name')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>

                                        <!-- Email -->
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                            @error('email')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>

                                        <!-- Username -->
                                        <div>
                                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                            @error('username')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>

                                        <!-- Phone -->
                                        <div>
                                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                            @error('phone')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Account Information -->
                                <div class="border-b pb-6">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Account Information</h3>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Password -->
                                        <div>
                                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password (Leave blank to keep current)</label>
                                            <div class="relative">
                                                <input type="password" name="password" id="password" class="w-full px-3 sm:px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                                <button type="button" onclick="togglePasswordVisibility('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 z-10 cursor-pointer">
                                                    <svg id="password_eye_open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    <svg id="password_eye_closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            @error('password')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>

                                        <!-- Confirm Password -->
                                        <div>
                                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                            <div class="relative">
                                                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-3 sm:px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                                <button type="button" onclick="togglePasswordVisibility('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 z-10 cursor-pointer">
                                                    <svg id="password_confirmation_eye_open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    <svg id="password_confirmation_eye_closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Roles (Multiple Selection) -->
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-3">Roles *</label>
                                            <div class="space-y-2 bg-gray-50 p-4 rounded-lg">
                                                @php
                                                    $availableRoles = ['admin', 'hr', 'director', 'dean', 'faculty'];
                                                    $userRoles = $user->roles();
                                                @endphp
                                                
                                                @foreach($availableRoles as $role)
                                                    <div class="flex items-center">
                                                        <input 
                                                            type="checkbox" 
                                                            name="roles[]" 
                                                            id="role_{{ $role }}" 
                                                            value="{{ $role }}"
                                                            {{ in_array($role, $userRoles) ? 'checked' : '' }}
                                                            class="w-4 h-4 text-blue-600 rounded role-checkbox"
                                                            onchange="handleRoleSelection()"
                                                        >
                                                        <label for="role_{{ $role }}" class="ml-2 text-sm text-gray-700 cursor-pointer">
                                                            {{ $role == 'hr' ? 'Human Resource' : ucfirst($role) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">Select one or more roles</p>
                                            @error('roles')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>

                                        <!-- Status -->
                                        <div>
                                            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                            <div class="flex items-center gap-2 mt-2">
                                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded">
                                                <label for="is_active" class="text-gray-700 text-sm">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Department & Designation -->
                                <div class="border-b pb-6">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Department & Designation</h3>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Department -->
                                        <div>
                                            <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                                            <select name="department_id" id="department_id" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                                <option value="">Select a department</option>
                                                @foreach($departments as $dept)
                                                    <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('department_id')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>

                                        <!-- Designation -->
                                        <div>
                                            <label for="designation_id" class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
                                            <select name="designation_id" id="designation_id" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                                <option value="">Select a designation</option>
                                                @foreach($designations as $desig)
                                                    <option value="{{ $desig->id }}" {{ old('designation_id', $user->designation_id) == $desig->id ? 'selected' : '' }}>{{ $desig->title }}</option>
                                                @endforeach
                                            </select>
                                            @error('designation_id')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold flex items-center justify-center gap-2 text-sm">
                                        <i class="fas fa-save"></i> Update User
                                    </button>
                                    <a href="{{ route('admin.users.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg font-semibold text-center text-sm">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Set as Profile Confirmation Modal -->
    <div id="setProfileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full animate-scale-in">
            <div class="bg-blue-50 border-b border-blue-200 px-6 py-4 flex items-center gap-3">
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Set as Profile Picture</h2>
                    <p class="text-sm text-gray-600">Update your profile photo</p>
                </div>
            </div>

            <div class="px-6 py-4">
                <p class="text-gray-700 text-sm">Set this photo as your profile picture?</p>
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex gap-3 justify-end">
                <button type="button" onclick="closeSetProfileModal()" class="px-4 py-2 rounded-lg font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300 transition text-sm">
                    Cancel
                </button>
                <button type="button" onclick="confirmSetProfile()" class="px-4 py-2 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 transition flex items-center gap-2 text-sm">
                    <i class="fas fa-check"></i> Set as Profile
                </button>
            </div>
        </div>
    </div>

    <!-- Image Crop Modal -->
    <div id="cropModal" class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center z-[60] p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full animate-scale-in">
            <div class="bg-blue-50 border-b border-blue-200 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 rounded-full p-3">
                        <i class="fas fa-crop-alt text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Crop & Resize Photo</h2>
                        <p class="text-sm text-gray-600">Adjust the user's profile picture</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4">
                <div class="mb-4">
                    <div class="max-h-96 overflow-hidden bg-gray-100 rounded-lg flex items-center justify-center">
                        <img id="cropImage" src="" alt="Crop preview" style="max-width: 100%; display: block;">
                    </div>
                </div>

                <!-- Crop Controls -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <button type="button" onclick="cropperZoomIn()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-search-plus mr-1"></i> Zoom In
                    </button>
                    <button type="button" onclick="cropperZoomOut()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-search-minus mr-1"></i> Zoom Out
                    </button>
                    <button type="button" onclick="cropperRotateLeft()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-undo mr-1"></i> Rotate Left
                    </button>
                    <button type="button" onclick="cropperRotateRight()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-redo mr-1"></i> Rotate Right
                    </button>
                    <button type="button" onclick="cropperReset()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-sync mr-1"></i> Reset
                    </button>
                </div>

                <p class="text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Drag to move, scroll to zoom. The cropped area will be the profile picture.
                </p>
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex gap-3 justify-end">
                <button type="button" onclick="closeCropModal()" class="px-4 py-2 rounded-lg font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300 transition text-sm">
                    Cancel
                </button>
                <button type="button" onclick="applyCropAndUpload()" class="px-4 py-2 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 transition flex items-center gap-2 text-sm">
                    <i class="fas fa-check"></i> Crop & Upload
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Photo Confirmation Modal -->
    <div id="deletePhotoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full animate-scale-in">
            <div class="bg-red-50 border-b border-red-200 px-6 py-4 flex items-center gap-3">
                <div class="bg-red-100 rounded-full p-3">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Confirm Delete</h2>
                    <p class="text-sm text-gray-600">This action cannot be undone</p>
                </div>
            </div>

            <div class="px-6 py-4">
                <p class="text-gray-700 mb-2 text-sm">Are you sure you want to delete this photo?</p>
                <p class="text-sm text-gray-600">The photo will be permanently removed from the system.</p>
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex gap-3 justify-end">
                <button type="button" onclick="closeDeletePhotoModal()" class="px-4 py-2 rounded-lg font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300 transition text-sm">
                    Cancel
                </button>
                <button type="button" onclick="confirmDeletePhoto()" class="px-4 py-2 rounded-lg font-semibold text-white bg-red-600 hover:bg-red-700 transition flex items-center gap-2 text-sm">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <script>
        const userId = {{ $user->id }};
    </script>
</body>
</html>