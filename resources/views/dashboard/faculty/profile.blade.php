<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile - IPCR Dashboard</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/urs_logo.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    @vite(['resources/css/dashboard_faculty_profile.css', 'resources/js/dashboard_faculty_profile.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation Header -->
    <nav class="bg-white shadow-sm border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex justify-between items-center">
                <!-- Logo and Title -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <img src="{{ asset('images/urs_logo.jpg') }}" alt="URS Logo" class="h-10 sm:h-12 w-auto object-contain flex-shrink-0">
                    <h1 class="text-base sm:text-xl font-bold text-gray-900">IPCR Dashboard</h1>
                </div>
                
                <!-- Desktop Navigation Links -->
                <div class="hidden lg:flex items-center space-x-6 xl:space-x-8">
                    <a href="{{ route('faculty.dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('faculty.my-ipcrs') }}" class="text-gray-600 hover:text-gray-900">My IPCRs</a>
                    <div class="relative">
                        <button onclick="toggleNotificationPopup()" class="text-gray-600 hover:text-gray-900 relative flex items-center gap-1">
                            Notifications
                            <span class="notification-badge" style="position: static; margin-left: 4px;">3</span>
                        </button>
                        
                        <!-- Notification Popup -->
                        <div id="notificationPopup" class="notification-popup">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-base font-bold text-gray-900">Notifications</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <div class="p-3">
                                    <!-- Notification 1 -->
                                    <div class="notification-item notification-blue mb-2">
                                        <div class="flex items-start space-x-2">
                                            <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-semibold text-gray-900">Your IPCR has been Rated</p>
                                                <p class="text-xs text-gray-600">By PCHS Dean</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Notification 2 -->
                                    <div class="notification-item notification-yellow mb-2">
                                        <div class="flex items-start space-x-2">
                                            <svg class="w-4 h-4 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-semibold text-gray-900">Reminder: 5 days left to submit.</p>
                                                <p class="text-xs text-gray-600">Submit your Jan - Jun 2024 Review before the deadline</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Notification 3 -->
                                    <div class="notification-item notification-gray">
                                        <div class="flex items-start space-x-2">
                                            <svg class="w-4 h-4 text-gray-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-semibold text-gray-900">System maintenance scheduled</p>
                                                <p class="text-xs text-gray-600">The system will be down on July 25th from 2-4 AM.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('faculty.profile') }}" class="text-blue-600 font-semibold hover:text-blue-700">Profile</a>
                    
                    <!-- Profile Picture -->
                    <div class="flex items-center space-x-3">
                        @if(auth()->user()->hasProfilePhoto())
                            <img src="{{ auth()->user()->profile_photo_url }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="profile-img">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3b82f6&color=fff" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="profile-img">
                        @endif
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-700 font-semibold">Logout</button>
                    </form>
                </div>

                <!-- Mobile Menu Button & Profile -->
                <div class="flex lg:hidden items-center space-x-3">
                    <!-- Notification Bell Icon -->
                    <div class="relative">
                        <button onclick="toggleNotificationPopupMobile()" class="text-gray-600 hover:text-gray-900 relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="notification-badge">3</span>
                        </button>
                        
                        <!-- Notification Popup -->
                        <div id="notificationPopupMobile" class="notification-popup">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-base font-bold text-gray-900">Notifications</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <div class="p-3">
                                    <!-- Notification 1 -->
                                    <div class="notification-item notification-blue mb-2">
                                        <div class="flex items-start space-x-2">
                                            <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-semibold text-gray-900">Your IPCR has been Rated</p>
                                                <p class="text-xs text-gray-600">By PCHS Dean</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Notification 2 -->
                                    <div class="notification-item notification-yellow mb-2">
                                        <div class="flex items-start space-x-2">
                                            <svg class="w-4 h-4 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-semibold text-gray-900">Reminder: 5 days left to submit.</p>
                                                <p class="text-xs text-gray-600">Submit your Jan - Jun 2024 Review before the deadline</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Notification 3 -->
                                    <div class="notification-item notification-gray">
                                        <div class="flex items-start space-x-2">
                                            <svg class="w-4 h-4 text-gray-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-semibold text-gray-900">System maintenance scheduled</p>
                                                <p class="text-xs text-gray-600">The system will be down on July 25th from 2-4 AM.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        @if(auth()->user()->hasProfilePhoto())
                            <img src="{{ auth()->user()->profile_photo_url }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="profile-img">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3b82f6&color=fff" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="profile-img">
                        @endif
                    </div>
                    <div class="hamburger" onclick="toggleMobileMenu()">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Overlay -->
            <div class="mobile-menu-overlay lg:hidden" onclick="toggleMobileMenu()"></div>

            <!-- Mobile Menu -->
            <div class="mobile-menu lg:hidden flex-col space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Menu</h2>
                    <button onclick="toggleMobileMenu()" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <a href="{{ route('faculty.dashboard') }}" class="block text-gray-600 hover:text-gray-900 py-2">Dashboard</a>
                <a href="{{ route('faculty.my-ipcrs') }}" class="block text-gray-600 hover:text-gray-900 py-2">My IPCRs</a>
                <a href="{{ route('faculty.profile') }}" class="block text-blue-600 font-semibold hover:text-blue-700 py-2">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-700 font-semibold py-2">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 sm:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Main Content (2/3 width) -->
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                <!-- Profile Header -->
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 md:p-8">
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-4 sm:mb-6">My Profile</h1>
                    
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6">
                        <!-- Profile Photo -->
                        <div class="flex-shrink-0">
                            @if(auth()->user()->hasProfilePhoto())
                                <img src="{{ auth()->user()->profile_photo_url }}" 
                                     alt="{{ auth()->user()->name }}" 
                                     class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-gray-200">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=128&background=3b82f6&color=fff" 
                                     alt="{{ auth()->user()->name }}" 
                                     class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-gray-200">
                            @endif
                        </div>

                        <!-- Basic Info -->
                        <div class="flex-1 text-center sm:text-left">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h2>
                            <p class="text-sm sm:text-base text-gray-600 mt-1">{{ auth()->user()->email }}</p>
                            <div class="mt-3 sm:mt-4 flex flex-wrap gap-2 justify-center sm:justify-start">
                                @forelse(auth()->user()->roles() as $role)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs sm:text-sm font-semibold px-3 py-1 rounded-full">
                                        {{ ucfirst($role) }}
                                    </span>
                                @empty
                                    <span class="inline-block bg-gray-100 text-gray-800 text-xs sm:text-sm font-semibold px-3 py-1 rounded-full">
                                        No roles assigned
                                    </span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 md:p-8">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Personal Information</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Employee ID -->
                        <div>
                            <label class="text-xs sm:text-sm text-gray-500 block mb-1">Employee ID</label>
                            <p class="text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->employee_id ?? 'Not assigned' }}</p>
                        </div>
                        
                        <!-- Username -->
                        <div>
                            <label class="text-xs sm:text-sm text-gray-500 block mb-1">Username</label>
                            <p class="text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->username }}</p>
                        </div>
                        
                        <!-- Phone -->
                        <div>
                            <label class="text-xs sm:text-sm text-gray-500 block mb-1">Phone Number</label>
                            <p class="text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->phone ?? 'Not provided' }}</p>
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label class="text-xs sm:text-sm text-gray-500 block mb-1">Email Address</label>
                            <p class="text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Work Information -->
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 md:p-8">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Work Information</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Department -->
                        <div>
                            <label class="text-xs sm:text-sm text-gray-500 block mb-1">Department</label>
                            <p class="text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->department->name ?? 'Not assigned' }}</p>
                        </div>
                        
                        <!-- Designation -->
                        <div>
                            <label class="text-xs sm:text-sm text-gray-500 block mb-1">Designation</label>
                            <p class="text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->designation->title ?? 'Not assigned' }}</p>
                        </div>
                        
                        <!-- Roles -->
                        <div class="sm:col-span-2">
                            <label class="text-xs sm:text-sm text-gray-500 block mb-2">Assigned Roles</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach(auth()->user()->roles() as $role)
                                    <span class="inline-block bg-gray-100 text-gray-800 text-xs sm:text-sm font-semibold px-3 py-1 rounded-full">
                                        {{ ucfirst($role) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Account Status -->
                        <div>
                            <label class="text-xs sm:text-sm text-gray-500 block mb-1">Account Status</label>
                            <div>
                                @if(auth()->user()->is_active)
                                    <span class="inline-block bg-green-100 text-green-800 text-xs sm:text-sm font-semibold px-3 py-1 rounded-full">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-block bg-red-100 text-red-800 text-xs sm:text-sm font-semibold px-3 py-1 rounded-full">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Member Since -->
                        <div>
                            <label class="text-xs sm:text-sm text-gray-500 block mb-1">Member Since</label>
                            <p class="text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Quick Actions</h3>
                    
                    <div class="space-y-2 sm:space-y-3">
                        <button onclick="openEditProfileModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 sm:py-3 px-4 rounded-lg text-sm sm:text-base transition">
                            Edit Profile
                        </button>
                        <button onclick="openChangePasswordModal()" class="w-full border border-gray-300 hover:border-gray-400 text-gray-700 font-semibold py-2 sm:py-3 px-4 rounded-lg text-sm sm:text-base transition">
                            Change Password
                        </button>
                    </div>
                </div>

                <!-- Account Activity -->
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Account Activity</h3>
                    
                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500">Last Login</p>
                            <p class="text-sm sm:text-base font-semibold text-gray-900 mt-1">
                                @if(auth()->user()->last_login_at)
                                    {{ auth()->user()->last_login_at->format('F d, Y g:i A') }}
                                @else
                                    Never logged in
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500">Profile Completeness</p>
                            <div class="mt-2">
                                <div class="flex justify-between mb-1">
                                    <span class="text-xs sm:text-sm font-semibold text-gray-900">85%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Need Help?</h3>
                    
                    <div class="space-y-2 text-xs sm:text-sm text-gray-600">
                        <p>If you need to update your information or have any questions, please contact the administrator.</p>
                        <div class="mt-3 sm:mt-4">
                            <button class="text-blue-600 hover:text-blue-700 font-semibold">
                                Contact Support
                            </button>
                        </div>
                    </div>
                </div>
                </div>
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
                        <p class="text-sm text-gray-600">Adjust your profile picture</p>
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
                    Drag to move, scroll to zoom. The cropped area will be your profile picture.
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

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full animate-scale-in">
            <div class="bg-blue-50 border-b border-blue-200 px-6 py-4 flex items-center gap-3">
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Change Password</h2>
                    <p class="text-sm text-gray-600">Update your account password</p>
                </div>
            </div>

            <form id="changePasswordForm" method="POST" action="{{ route('faculty.password.change') }}">
                @csrf
                @method('PATCH')
                
                <div class="px-6 py-4 space-y-4">
                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                        <div class="relative">
                            <input type="password" id="current_password" name="current_password" required
                                   class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                   placeholder="Enter current password">
                            <button type="button" onclick="togglePasswordVisibility('current_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 cursor-pointer">
                                <svg id="current_password_eye_open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="current_password_eye_closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <span class="text-red-500 text-xs hidden" id="current_password_error"></span>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                        <div class="relative">
                            <input type="password" id="new_password" name="new_password" required
                                   class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                   placeholder="Enter new password">
                            <button type="button" onclick="togglePasswordVisibility('new_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 cursor-pointer">
                                <svg id="new_password_eye_open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="new_password_eye_closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <span class="text-red-500 text-xs hidden" id="new_password_error"></span>
                        <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters</p>
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                                   class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                   placeholder="Confirm new password">
                            <button type="button" onclick="togglePasswordVisibility('new_password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 cursor-pointer">
                                <svg id="new_password_confirmation_eye_open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="new_password_confirmation_eye_closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <span class="text-red-500 text-xs hidden" id="new_password_confirmation_error"></span>
                    </div>

                    <!-- Success/Error Messages -->
                    <div id="passwordMessage" class="hidden rounded-lg p-3 text-sm"></div>
                </div>

                <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex gap-3 justify-end">
                    <button type="button" onclick="closeChangePasswordModal()" class="px-4 py-2 rounded-lg font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300 transition text-sm">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 transition flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full animate-scale-in my-8">
            <div class="bg-blue-50 border-b border-blue-200 px-6 py-4 flex items-center gap-3">
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Edit Profile</h2>
                    <p class="text-sm text-gray-600">Update your profile information</p>
                </div>
            </div>

            <form id="editProfileForm" data-action="{{ route('faculty.profile.update') }}" class="space-y-6">
                @csrf
                @method('PATCH')
                
                <div class="px-6 py-4">
                    <!-- Profile Picture Section -->
                    <div class="mb-6 pb-6 border-b">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Profile Picture</h3>
                        
                        <div class="flex items-center gap-4">
                            <!-- Current Photo Preview -->
                            <div class="flex-shrink-0">
                                <div id="modalPhotoPreview" class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-300 bg-gray-200 flex items-center justify-center">
                                    @if(auth()->user()->hasProfilePhoto())
                                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Upload Controls -->
                            <div class="flex-1">
                                <input type="file" id="modalPhotoInput" name="photo" accept="image/*" class="hidden">
                                <button type="button" onclick="document.getElementById('modalPhotoInput').click()" class="px-4 py-2 bg-blue-50 text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                                    <i class="fas fa-camera mr-2"></i>Choose Photo
                                </button>
                                <p class="text-xs text-gray-500 mt-2">Max 5MB • JPEG, PNG, GIF, WebP</p>
                                <div id="modalUploadProgress" class="hidden mt-2">
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div id="modalProgressBar" class="bg-blue-600 h-1.5 rounded-full transition-all" style="width: 0%"></div>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">Uploading...</p>
                                </div>
                                <div id="modalUploadMessage" class="text-xs mt-1"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="mb-6 pb-6 border-b">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Personal Information</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div>
                                <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" name="name" id="edit_name" value="{{ auth()->user()->name }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                       placeholder="Enter full name">
                                <span class="text-red-500 text-xs hidden" id="edit_name_error"></span>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="email" id="edit_email" value="{{ auth()->user()->email }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                       placeholder="Enter email address">
                                <span class="text-red-500 text-xs hidden" id="edit_email_error"></span>
                            </div>

                            <!-- Username -->
                            <div>
                                <label for="edit_username" class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                                <input type="text" name="username" id="edit_username" value="{{ auth()->user()->username }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                       placeholder="Enter username">
                                <span class="text-red-500 text-xs hidden" id="edit_username_error"></span>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="edit_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="text" name="phone" id="edit_phone" value="{{ auth()->user()->phone ?? '' }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                       placeholder="Enter phone number">
                                <span class="text-red-500 text-xs hidden" id="edit_phone_error"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Work Information -->
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Work Information</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Department -->
                            <div>
                                <label for="edit_department_id" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                                <select name="department_id" id="edit_department_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="">Select a department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ auth()->user()->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-red-500 text-xs hidden" id="edit_department_id_error"></span>
                            </div>

                            <!-- Designation -->
                            <div>
                                <label for="edit_designation_id" class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
                                <select name="designation_id" id="edit_designation_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="">Select a designation</option>
                                    @foreach($designations as $desig)
                                        <option value="{{ $desig->id }}" {{ auth()->user()->designation_id == $desig->id ? 'selected' : '' }}>{{ $desig->title }}</option>
                                    @endforeach
                                </select>
                                <span class="text-red-500 text-xs hidden" id="edit_designation_id_error"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    <div id="editProfileMessage" class="hidden rounded-lg p-3 text-sm mt-6"></div>
                </div>

                <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex gap-3 justify-end">
                    <button type="button" onclick="closeEditProfileModal()" class="px-4 py-2 rounded-lg font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300 transition text-sm">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 transition flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
