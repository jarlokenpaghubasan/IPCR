<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My IPCRs - IPCR Dashboard</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/urs_logo.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/dashboard_faculty_my-ipcrs.css', 'resources/js/dashboard_faculty_my-ipcrs.js'])
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
                    <a href="{{ route('faculty.my-ipcrs') }}" class="text-blue-600 font-semibold hover:text-blue-700">My IPCRs</a>
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
                    <a href="{{ route('faculty.profile') }}" class="text-gray-600 hover:text-gray-900">Profile</a>
                    
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
                <a href="{{ route('faculty.my-ipcrs') }}" class="block text-blue-600 font-semibold hover:text-blue-700 py-2">My IPCRs</a>
                <a href="{{ route('faculty.profile') }}" class="block text-gray-600 hover:text-gray-900 py-2">Profile</a>
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
            <!-- Left Main Content (2/3 width) -->
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                <!-- Header Section -->
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 md:p-8">
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-4 sm:mb-6 md:mb-8">Individual Performance Commitment and Review for {{ auth()->user()->designation->title ?? 'Faculty' }}</h1>
                    
                    <!-- User Information Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Full Name -->
                        <div>
                            <label class="text-xs sm:text-sm text-gray-500 block mb-1">Full Name</label>
                            <p class="text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                        </div>
                        
                        <!-- Employee ID -->
                        <div>
                            <label class="text-xs sm:text-sm text-gray-500 block mb-1">Employee ID</label>
                            <p class="text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->employee_id ?? 'N/A' }}</p>
                        </div>
                        
                        <!-- Designation -->
                        <div>
                            <label class="text-xs sm:text-sm text-gray-500 block mb-1">Designation</label>
                            <p class="text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->designation->title ?? 'N/A' }}</p>
                        </div>
                        
                        <!-- Department -->
                        <div>
                            <label class="text-xs sm:text-sm text-gray-500 block mb-1">Department</label>
                            <p class="text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->department->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Create IPCR Section -->
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 md:p-8">
                    <!-- Tab Header -->
                    <div class="border-b border-gray-200 mb-4 sm:mb-6">
                        <div class="flex space-x-4 sm:space-x-8 overflow-x-auto">
                            <button class="pb-3 sm:pb-4 px-1 border-b-2 border-blue-600 font-semibold text-blue-600 text-sm sm:text-base whitespace-nowrap">
                                Create IPCR
                            </button>
                        </div>
                    </div>

                    <!-- Create Button Area -->
                    <div id="createButtonArea" class="py-8 sm:py-12 flex justify-center">
                        <button class="create-ipcr-button" onclick="openCreateIpcrModal()">
                            Create IPCR
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Create IPCR Modal -->
                    <div id="createIpcrModal" class="fixed inset-0 z-50 hidden">
                        <div class="absolute inset-0 bg-black/50" onclick="closeCreateIpcrModal()"></div>
                        <div class="relative mx-auto mt-24 w-full max-w-lg bg-white rounded-xl shadow-lg">
                            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg sm:text-xl font-bold text-gray-900">Individual Performance Commitment and Review for {{ auth()->user()->designation->title ?? 'Faculty' }}</h2>
                                <button type="button" onclick="closeCreateIpcrModal()" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="px-6 py-5 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">School Year</label>
                                    <select id="ipcrSchoolYear" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @php
                                            $currentYear = now()->year;
                                            $startYear = $currentYear - 5;
                                        @endphp
                                        @for ($year = $currentYear; $year >= $startYear; $year--)
                                            <option value="{{ $year }}-{{ $year + 1 }}">{{ $year }}-{{ $year + 1 }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Semester</label>
                                    <select id="ipcrSemester" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="first">First Semester</option>
                                        <option value="second">Second Semester</option>
                                    </select>
                                </div>
                                @php
                                    $deptId = auth()->user()->department_id;
                                    $deanUser = $deptId
                                        ? \App\Models\User::where('department_id', $deptId)
                                            ->whereHas('userRoles', function ($query) {
                                                $query->where('role', 'deanUser');
                                            })
                                            ->first()
                                        : null;
                                    
                                    $directorUser = \App\Models\User::whereHas('userRoles', function ($query) {
                                        $query->where('role', 'directorUser');
                                    })->first();
                                @endphp
                                <div class="pt-2 border-t border-gray-200">
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500">Name of the Ratee:</label>
                                            <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500">Approved By:</label>
                                            <p class="text-sm font-semibold text-gray-900">{{ $directorUser ? $directorUser->name : 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500">Noted By:</label>
                                            <p class="text-sm font-semibold text-gray-900">{{ $deanUser ? $deanUser->name : 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
                                <button type="button" onclick="closeCreateIpcrModal()" class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200">Cancel</button>
                                <button type="button" onclick="proceedCreateIpcr()" class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700">Proceed</button>
                            </div>
                        </div>
                    </div>

                    <!-- IPCR Document Modal -->
                    <div id="ipcrDocumentContainer" class="fixed inset-0 z-50 hidden">
                        <div class="absolute inset-0 bg-black/50" onclick="closeIpcrDocument()"></div>
                        <div class="relative mx-auto mt-2 sm:mt-8 mb-2 sm:mb-8 w-full max-w-6xl bg-white rounded-lg sm:rounded-xl shadow-lg max-h-[98vh] sm:max-h-[90vh] overflow-y-auto px-2 sm:px-0">
                            <!-- Document Header -->
                            <div class="bg-gray-50 px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-300 sticky top-0 bg-white z-10">
                                <div class="flex justify-between items-start mb-3 sm:mb-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1 sm:gap-2 mb-2">
                                            <input type="text" id="ipcrDocumentTitle" class="text-sm sm:text-lg font-bold text-gray-900 border-0 border-b-2 border-transparent hover:border-gray-300 focus:border-blue-500 focus:ring-0 bg-transparent px-1 sm:px-2 py-1 -ml-1 sm:-ml-2 w-full" value="IPCR for {{ auth()->user()->designation->title ?? 'Faculty' }}" />
                                            <button onclick="saveDocumentTitle()" class="text-blue-600 hover:text-blue-700 flex-shrink-0" title="Save title">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="text-xs sm:text-sm text-gray-600">School Year: <span id="displaySchoolYear" class="font-semibold"></span></p>
                                        <p class="text-xs sm:text-sm text-gray-600">Semester: <span id="displaySemester" class="font-semibold"></span></p>
                                    </div>
                                    <button onclick="closeIpcrDocument()" class="text-gray-500 hover:text-gray-700 ml-2 flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 text-xs sm:text-sm">
                                    <div class="flex flex-col sm:block">
                                        <span class="text-gray-600">Ratee:</span>
                                        <span class="font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</span>
                                    </div>
                                    <div class="flex flex-col sm:block">
                                        <span class="text-gray-600">Approved By:</span>
                                        <span class="font-semibold text-gray-900 truncate">{{ $directorUser ? $directorUser->name : 'N/A' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:block">
                                        <span class="text-gray-600">Noted By:</span>
                                        <span class="font-semibold text-gray-900 truncate">{{ $deanUser ? $deanUser->name : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Excel-like Table -->
                            <div class="overflow-x-auto px-2 sm:px-6 py-3 sm:py-4">
                                <table class="w-full border-collapse min-w-[800px]">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700" rowspan="2" style="width: 15%;">MFO</th>
                                            <th class="border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700" colspan="2" style="width: 25%;">Success Indicator</th>
                                            <th class="border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700 hidden" rowspan="2" style="width: 20%;">Actual Accomplishment</th>
                                            <th class="border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700 hidden" colspan="4">Rating</th>
                                            <th class="border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700 hidden" rowspan="2" style="width: 15%;">Remarks</th>
                                        </tr>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600">Target</th>
                                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600">Measures</th>
                                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600 hidden" style="width: 8%;">Q</th>
                                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600 hidden" style="width: 8%;">E</th>
                                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600 hidden" style="width: 8%;">T</th>
                                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600 hidden" style="width: 8%;">A</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ipcrTableBody">
                                        <!-- Table rows will be added dynamically -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Action Buttons -->
                            <div class="px-2 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-300 sticky bottom-0 z-10">
                                <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-2 sm:gap-3">
                                    <div class="flex flex-wrap gap-2">
                                        <!-- Dropdown for Add Section Header -->
                                        <div class="relative" id="sectionHeaderDropdown">
                                            <button type="button" onclick="toggleSectionHeaderDropdown()" class="px-2 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-blue-600 bg-blue-50 border border-blue-200 hover:bg-blue-100 flex items-center gap-1 sm:gap-2 whitespace-nowrap">
                                                <span class="hidden sm:inline">+</span> Add Section
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                            <div id="sectionHeaderDropdownMenu" class="hidden absolute left-0 bottom-full mb-2 w-48 sm:w-56 rounded-lg shadow-xl bg-white border border-gray-200 z-[9999]">
                                                <div class="py-1">
                                                    <button type="button" onclick="addSectionHeader('Strategic Objectives', false)" class="w-full text-left px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                                        Strategic Objectives
                                                    </button>
                                                    <button type="button" onclick="addSectionHeader('Core Functions', false)" class="w-full text-left px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                                        Core Functions
                                                    </button>
                                                    <button type="button" onclick="addSectionHeader('Support Function', false)" class="w-full text-left px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                                        Support Function
                                                    </button>
                                                    <button type="button" onclick="addSectionHeader('', true)" class="w-full text-left px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-t border-gray-200">
                                                        Others (Custom)
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" onclick="addSOHeader()" class="px-2 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-purple-600 bg-purple-50 border border-purple-200 hover:bg-purple-100 whitespace-nowrap">
                                            <span class="hidden sm:inline">+</span> Add SO
                                        </button>
                                        <button type="button" onclick="addDataRow()" class="px-2 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-green-600 bg-green-50 border border-green-200 hover:bg-green-100 whitespace-nowrap">
                                            <span class="hidden sm:inline">+</span> Add Row
                                        </button>
                                        <button type="button" onclick="removeLastRow()" class="px-2 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-red-600 bg-red-50 border border-red-200 hover:bg-red-100 whitespace-nowrap">
                                            <span class="hidden sm:inline">-</span> Remove
                                        </button>
                                    </div>
                                    <div class="flex gap-2 sm:gap-3">
                                        <button type="button" onclick="saveAsTemplate()" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-orange-700 bg-orange-50 border border-orange-300 hover:bg-orange-100 whitespace-nowrap">
                                            <span class="hidden sm:inline">📋</span> Save as Template
                                        </button>
                                        <button type="button" onclick="closeIpcrDocument()" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">Close</button>
                                        <button type="button" onclick="saveIpcrDocument()" class="flex-1 sm:flex-none px-4 sm:px-6 py-2 rounded-lg text-xs sm:text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Template Preview Modal -->
                    <div id="templatePreviewModal" class="fixed inset-0 z-50 hidden">
                        <div class="absolute inset-0 bg-black/50" onclick="closeTemplatePreview()"></div>
                        <div class="relative mx-auto mt-2 sm:mt-8 mb-2 sm:mb-8 w-full max-w-6xl bg-white rounded-lg sm:rounded-xl shadow-lg max-h-[98vh] sm:max-h-[90vh] overflow-y-auto px-2 sm:px-0">
                            <!-- Document Header -->
                            <div class="bg-gray-50 px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-300 sticky top-0 bg-white z-10">
                                <div class="flex justify-between items-start mb-3 sm:mb-4">
                                    <div class="flex-1 min-w-0">
                                        <h2 id="templatePreviewTitle" class="text-sm sm:text-lg font-bold text-gray-900 mb-2"></h2>
                                        <p class="text-xs sm:text-sm text-gray-600">School Year: <span id="templatePreviewSchoolYear" class="font-semibold"></span></p>
                                        <p class="text-xs sm:text-sm text-gray-600">Semester: <span id="templatePreviewSemester" class="font-semibold"></span></p>
                                    </div>
                                    <button onclick="closeTemplatePreview()" class="text-gray-500 hover:text-gray-700 ml-2 flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 text-xs sm:text-sm">
                                    <div class="flex flex-col sm:block">
                                        <span class="text-gray-600">Ratee:</span>
                                        <span class="font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</span>
                                    </div>
                                    <div class="flex flex-col sm:block">
                                        <span class="text-gray-600">Approved By:</span>
                                        <span class="font-semibold text-gray-900 truncate">{{ $directorUser ? $directorUser->name : 'N/A' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:block">
                                        <span class="text-gray-600">Noted By:</span>
                                        <span class="font-semibold text-gray-900 truncate">{{ $deanUser ? $deanUser->name : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Excel-like Table -->
                            <div class="overflow-x-auto px-2 sm:px-6 py-3 sm:py-4">
                                <table class="w-full border-collapse min-w-[800px]">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700" rowspan="2" style="width: 15%;">MFO</th>
                                            <th class="border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700" colspan="2" style="width: 25%;">Success Indicator</th>
                                            <th class="border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700" rowspan="2" style="width: 20%;">Actual Accomplishment</th>
                                            <th class="border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700" colspan="4">Rating</th>
                                            <th class="border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700" rowspan="2" style="width: 15%;">Remarks</th>
                                        </tr>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600">Target</th>
                                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600">Measures</th>
                                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600" style="width: 8%;">Q</th>
                                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600" style="width: 8%;">E</th>
                                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600" style="width: 8%;">T</th>
                                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600" style="width: 8%;">A</th>
                                        </tr>
                                    </thead>
                                    <tbody id="templatePreviewTableBody">
                                        <!-- Table rows will be added dynamically -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Action Buttons -->
                            <div class="px-2 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-300 sticky bottom-0 z-10">
                                <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-2 sm:gap-3">
                                    <button type="button" id="updateSubmissionBtn" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-white bg-green-600 hover:bg-green-700 hidden">Update Submission</button>
                                    <button type="button" id="saveCopyBtn" onclick="saveCopyFromPreview()" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-white bg-orange-600 hover:bg-orange-700">Edit IPCR</button>
                                    <button type="button" onclick="closeTemplatePreview()" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">Close</button>
                                </div>
                                <input type="hidden" id="currentPreviewTemplateId" value="">
                                <input type="hidden" id="currentSubmissionIdToUpdate" value="">
                            </div>
                        </div>
                    </div>

                    <!-- IPCR Form Modal -->
                    <div id="ipcrFormModal" class="fixed inset-0 z-50 hidden">
                        <div class="absolute inset-0 bg-black/50" onclick="closeIpcrFormModal()"></div>
                        <div class="relative mx-auto mt-12 w-full max-w-4xl bg-white rounded-xl shadow-lg max-h-[85vh] overflow-y-auto">
                            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 sticky top-0 bg-white">
                                <h2 class="text-lg sm:text-xl font-bold text-gray-900">IPCR Template Builder</h2>
                                <button type="button" onclick="closeIpcrFormModal()" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="px-6 py-5">
                                <div class="mb-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <label class="text-sm font-semibold text-gray-700">Template Name:</label>
                                        <input type="text" id="currentTemplateName" placeholder="Enter template name" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Rating Period: January - June 2026</h3>
                                </div>

                                <form id="ipcrForm">
                                    <div class="mb-6">
                                        <!-- Global Formatting Toolbar -->
                                        <div class="format-toolbar mb-4">
                                            <button type="button" class="format-btn" data-command="bold" title="Bold (Ctrl+B)">
                                                <b>B</b>
                                            </button>
                                            <button type="button" class="format-btn" data-command="italic" title="Italic (Ctrl+I)">
                                                <i>I</i>
                                            </button>
                                            <button type="button" class="format-btn" data-command="underline" title="Underline (Ctrl+U)">
                                                <u>U</u>
                                            </button>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div class="text-center font-semibold text-gray-700">MRO</div>
                                            <div class="text-center font-semibold text-gray-700">Success Indicators<br>(Target + Measures)</div>
                                        </div>

                                        <!-- Strategic Objectives Container -->
                                        <div id="strategicObjectivesContainer">
                                            <div class="mb-4">
                                                <div class="editable-field" contenteditable="true" data-placeholder="Strategic objectives..."></div>
                                            </div>
                                        </div>

                                        <!-- Dynamic Headers Container -->
                                        <div id="headersContainer"></div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex flex-wrap gap-3 justify-between items-center border-t border-gray-200 pt-4">
                                        <div class="flex gap-3">
                                            <button type="button" onclick="addHeader()" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-100 font-semibold text-sm">
                                                + Add Header
                                            </button>
                                            <button type="button" onclick="addRow()" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-100 font-semibold text-sm">
                                                + Add Row
                                            </button>
                                        </div>
                                        <div class="flex gap-3">
                                            <button type="button" onclick="clearForm()" class="text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-100 font-semibold text-sm">
                                                Clear Form
                                            </button>
                                            <button type="button" onclick="closeIpcrFormModal()" class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">Close</button>
                                            <button type="button" id="saveButton" onclick="generateIPCR()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-semibold text-sm">
                                                Generate IPCR
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Previous Submissions and Saved Copy Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-6 sm:mt-8">
                        <!-- Previous IPCR Submissions -->
                        <div id="previousSubmissionsSection">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Previous IPCR Submissions</h3>
                            <div class="space-y-2 sm:space-y-3">
                                <p class="text-sm text-gray-500 text-center py-6">No submissions yet. Create and submit an IPCR to see them here.</p>
                            </div>
                        </div>

                        <!-- Saved Copy -->
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Saved Copy</h3>
                            <div id="savedCopiesList" class="space-y-2 sm:space-y-3"></div>
                            <p id="savedCopiesEmpty" class="text-sm text-gray-500 text-center py-6">No saved copies yet. Save your IPCR draft to see them here.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar (1/3 width) -->
            <div class="space-y-4 sm:space-y-6">
                <!-- Template -->
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Templates</h3>
                    
                    <div id="templatesContainer">
                        @forelse($templates ?? [] as $template)
                            <!-- Template Item -->
                            <div class="template-card mb-3 relative">
                                <button onclick="deleteTemplate({{ $template->id }})" class="absolute top-2 right-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full p-2 transition" title="Delete template">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                                <div class="mb-3 pr-8">
                                    <p class="text-sm sm:text-base font-semibold text-gray-900">{{ $template->title }}</p>
                                    @if($template->school_year && $template->semester)
                                        <p class="text-xs sm:text-sm text-gray-600">{{ $template->school_year }} • {{ $template->semester }}</p>
                                    @else
                                        <p class="text-xs sm:text-sm text-gray-600">{{ $template->period }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500">Saved on {{ $template->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="flex gap-2 ml-7">
                                    @if($template->table_body_html)
                                        <button onclick="loadTemplateToDocument({{ $template->id }})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold py-2 px-3 sm:px-4 rounded">
                                            Use
                                        </button>
                                    @else
                                        <button onclick="loadTemplate({{ $template->id }})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold py-2 px-3 sm:px-4 rounded">
                                            Edit
                                        </button>
                                    @endif
                                    <button onclick="viewTemplate({{ $template->id }})" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-xs sm:text-sm font-semibold py-2 px-3 sm:px-4 rounded">
                                        View
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-4">No saved templates yet</p>
                        @endforelse
                    </div>
                </div>

                <!-- Submit IPCR -->
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Submit IPCR</h3>
                    @php
                        $currentYear = now()->year;
                        $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);
                        $currentSemester = now()->month <= 6 ? 'First Semester' : 'Second Semester';
                        $hasSubmission = isset($submissions) && count($submissions) > 0;
                    @endphp
                    <div class="space-y-2 text-sm text-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Current Sem:</span>
                            <span class="font-semibold text-gray-900">{{ $currentSemester }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Current Year:</span>
                            <span class="font-semibold text-gray-900">{{ $currentSchoolYear }}</span>
                        </div>
                    </div>
                    @if($hasSubmission)
                        <button type="button" disabled class="mt-4 w-full bg-gray-400 text-white text-sm font-semibold py-2 rounded cursor-not-allowed opacity-75">
                            ✓ Submitted
                        </button>
                    @else
                        <button type="button" class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 rounded" onclick="openSubmitIpcrModal()">
                            Submit
                        </button>
                    @endif
                    
                    <!-- Submitted IPCRs List -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-bold text-gray-900 mb-3">Submitted IPCRs</h4>
                        @forelse($submissions ?? [] as $submission)
                            <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm font-semibold text-gray-900 mb-1">{{ $submission->title }}</p>
                                <p class="text-xs text-gray-600">{{ $submission->school_year }} • {{ $submission->semester }}</p>
                                <p class="text-xs text-gray-500 mb-2">Submitted: {{ $submission->submitted_at->format('M d, Y') }}</p>
                                <div class="flex gap-2">
                                    <button onclick="viewSubmission({{ $submission->id }})" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold py-1.5 px-3 rounded">
                                        View & Edit
                                    </button>
                                    <button onclick="deleteSubmission({{ $submission->id }})" class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold py-1.5 px-3 rounded">
                                        Delete
                                    </button>
                                    <span class="px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500 text-center py-4">No submissions yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit IPCR Modal -->
    <div id="submitIpcrModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full animate-scale-in">
            <div class="bg-blue-50 border-b border-blue-200 px-6 py-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Submit IPCR</h2>
                <button type="button" onclick="closeSubmitIpcrModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Select Template</label>
                    <select id="submitSavedCopySelect" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">No templates found</option>
                    </select>
                </div>
            </div>
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex gap-3 justify-end">
                <button type="button" onclick="closeSubmitIpcrModal()" class="px-4 py-2 rounded-lg font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300 transition text-sm">
                    Cancel
                </button>
                <button type="button" onclick="submitSelectedCopy()" class="px-4 py-2 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 transition text-sm">
                    Submit
                </button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full animate-scale-in">
            <div id="modalHeader" class="bg-yellow-50 border-b border-yellow-200 px-6 py-4 flex items-center gap-3">
                <div class="bg-yellow-100 rounded-full w-12 h-12 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h2 id="modalTitle" class="text-lg font-bold text-gray-900">Confirm Action</h2>
                    <p class="text-sm text-gray-600">This action cannot be undone</p>
                </div>
            </div>

            <div class="px-6 py-4">
                <p id="modalMessage" class="text-gray-700 mb-2 text-sm"></p>
                <p id="modalSubMessage" class="text-sm text-gray-600"></p>
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex gap-3 justify-end">
                <button type="button" onclick="closeConfirmationModal()" class="px-4 py-2 rounded-lg font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300 transition text-sm">
                    Cancel
                </button>
                <button type="button" id="confirmButton" onclick="confirmAction()" class="px-4 py-2 rounded-lg font-semibold text-white bg-yellow-600 hover:bg-yellow-700 transition flex items-center gap-2 text-sm">
                    <i class="fas fa-check"></i> <span id="confirmButtonText">Confirm</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Modal -->
    <div id="alertModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full animate-scale-in">
            <div id="alertModalHeader" class="bg-blue-50 border-b border-blue-200 px-6 py-4 flex items-center gap-3">
                <div id="alertModalIconContainer" class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center">
                    <i id="alertModalIcon" class="fas fa-info-circle text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h2 id="alertModalTitle" class="text-lg font-bold text-gray-900">Information</h2>
                </div>
            </div>

            <div class="px-6 py-4">
                <p id="alertModalMessage" class="text-gray-700 text-sm"></p>
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end">
                <button type="button" onclick="closeAlertModal()" class="px-6 py-2 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 transition text-sm">
                    OK
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes scale-in {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .animate-scale-in {
            animation: scale-in 0.2s ease-out;
        }
        
        /* Text Formatting Toolbar */
        .format-toolbar {
            display: flex;
            gap: 2px;
            margin-bottom: 4px;
            background: #f9fafb;
            padding: 4px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        
        .format-btn {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #d1d5db;
            background: white;
            color: #374151;
            font-weight: bold;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.15s;
            border-radius: 4px;
        }
        
        .format-btn:hover {
            background: #eff6ff;
            border-color: #3b82f6;
            color: #3b82f6;
        }
        
        .format-btn.active {
            background: #3b82f6;
            border-color: #2563eb;
            color: white;
        }
        
        /* Contenteditable field styling */
        .editable-field {
            min-height: 80px;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: white;
            outline: none;
            transition: all 0.15s;
        }
        
        .editable-field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .editable-field:empty:before {
            content: attr(data-placeholder);
            color: #9ca3af;
        }
    </style>

    <script>
        let headerCount = 0;
        let soHeaderCount = 1;
        let currentHeaderForRows = null;
        let pendingAction = null;
        let pendingActionData = null;
        let isEditMode = false;
        let currentTemplateId = null;
        let currentSavedCopyId = null;
        const ipcrRoleLabel = @json(auth()->user()->designation->title ?? 'Faculty');
        const csrfToken = @json(csrf_token());

        function openCreateIpcrModal() {
            const modal = document.getElementById('createIpcrModal');
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeCreateIpcrModal() {
            const modal = document.getElementById('createIpcrModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function proceedCreateIpcr() {
            const schoolYear = document.getElementById('ipcrSchoolYear').value;
            const semester = document.getElementById('ipcrSemester').value;
            
            // Update display values
            document.getElementById('displaySchoolYear').textContent = schoolYear;
            document.getElementById('displaySemester').textContent = semester === 'first' ? 'First Semester' : 'Second Semester';
            
            // Clear table body and reset title
            const tableBody = document.getElementById('ipcrTableBody');
            if (tableBody) {
                tableBody.innerHTML = '';
            }
            
            // Reset document title
            const titleInput = document.getElementById('ipcrDocumentTitle');
            if (titleInput) {
                titleInput.value = `IPCR for ${ipcrRoleLabel}`;
            }
            
            // Reset saved copy ID and SO counter
            currentSavedCopyId = null;
            soHeaderCount = 0;
            
            // Hide modal and create button
            closeCreateIpcrModal();
            document.getElementById('createButtonArea').style.display = 'none';
            
            // Show IPCR document modal
            document.getElementById('ipcrDocumentContainer').classList.remove('hidden');
        }

        function closeIpcrDocument() {
            document.getElementById('ipcrDocumentContainer').classList.add('hidden');
            document.getElementById('createButtonArea').style.display = 'flex';
            currentSavedCopyId = null;
        }

        function saveDocumentTitle() {
            const titleInput = document.getElementById('ipcrDocumentTitle');
            if (titleInput) {
                showAlertModal('success', 'Title Updated', 'Document title has been updated. Remember to save your document to persist changes.');
            }
        }

        function openIpcrFormModal() {
            const modal = document.getElementById('ipcrFormModal');
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeIpcrFormModal() {
            const modal = document.getElementById('ipcrFormModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function openSubmitIpcrModal() {
            populateSubmitTemplates();
            const modal = document.getElementById('submitIpcrModal');
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeSubmitIpcrModal() {
            const modal = document.getElementById('submitIpcrModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        async function populateSubmitTemplates() {
            const select = document.getElementById('submitSavedCopySelect');
            if (!select) return;

            const templates = await getTemplates();
            select.innerHTML = '';

            if (templates.length === 0) {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No templates found';
                select.appendChild(option);
                select.disabled = true;
                return;
            }

            select.disabled = false;
            templates.forEach(template => {
                const option = document.createElement('option');
                option.value = template.id;
                option.textContent = `${template.title} • ${template.school_year} • ${template.semester}`;
                select.appendChild(option);
            });
        }

        async function getTemplates() {
            try {
                const response = await fetch('/faculty/ipcr/templates', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                return data.success ? data.templates : [];
            } catch (error) {
                console.error('Error fetching templates:', error);
                return [];
            }
        }

        async function submitSelectedCopy() {
            const select = document.getElementById('submitSavedCopySelect');
            const selectedId = select ? select.value : '';
            if (!selectedId) {
                showAlertModal('warning', 'Select a Template', 'Please select a template to submit.');
                return;
            }
            
            try {
                console.log('Fetching template:', selectedId);
                const response = await fetch(`/faculty/ipcr/templates/${selectedId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                
                console.log('Template response status:', response.status);
                const data = await response.json();
                console.log('Template data:', data);
                
                if (!data.success) {
                    showAlertModal('error', 'Not Found', 'Selected template could not be found.');
                    return;
                }
                
                const item = data.template;
                const soCounts = item.so_count_json || { strategic_objectives: 0, core_functions: 0, support_functions: 0 };
                
                console.log('Item from template:', item);
                console.log('Table body HTML:', item.table_body_html);
                console.log('SO Counts:', soCounts);
                
                // Use FormData instead of JSON to avoid HTML encoding issues
                const formData = new FormData();
                formData.append('title', item.title);
                formData.append('school_year', item.school_year);
                formData.append('semester', item.semester);
                formData.append('table_body_html', item.table_body_html || '');
                formData.append('so_count_json', JSON.stringify(soCounts));
                
                console.log('FormData entries:');
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ':', pair[1].substring(0, 100));
                }

                const submitResponse = await fetch('/faculty/ipcr/submissions', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                console.log('Submit response status:', submitResponse.status);
                if (!submitResponse.ok) {
                    const submitData = await submitResponse.json().catch(() => ({}));
                    console.error('Submit failed:', submitData);
                    throw new Error(submitData.message || 'Failed to submit IPCR');
                }
                
                const submitResult = await submitResponse.json();
                console.log('Submit success:', submitResult);

                closeSubmitIpcrModal();
                showAlertModal('success', 'Submitted', 'Your IPCR has been submitted successfully.', function() {
                    // Reload page to show the new submission
                    window.location.reload();
                });
            } catch (error) {
                console.error('Submit error:', error);
                showAlertModal('error', 'Submit Failed', error.message || 'Failed to submit IPCR.');
            }
        }

        function saveIpcrDocument() {
            const schoolYear = document.getElementById('displaySchoolYear')?.textContent?.trim();
            const semester = document.getElementById('displaySemester')?.textContent?.trim();
            const titleInput = document.getElementById('ipcrDocumentTitle');
            const title = titleInput ? titleInput.value.trim() : `IPCR for ${ipcrRoleLabel}`;
            const tableBody = document.getElementById('ipcrTableBody');
            const tableBodyHtml = tableBody ? buildTableBodySnapshot(tableBody) : '';

            const payload = {
                title: title,
                school_year: schoolYear || 'N/A',
                semester: semester || 'N/A',
                table_body_html: tableBodyHtml
            };

            const url = currentSavedCopyId 
                ? `{{ url('faculty/ipcr/saved-copies') }}/${currentSavedCopyId}` 
                : '{{ route("faculty.ipcr.saved-copies.store") }}';
            const method = currentSavedCopyId ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentSavedCopyId = data.savedCopy.id;
                    renderSavedCopies();
                    showAlertModal('success', 'Saved', data.message);
                } else {
                    showAlertModal('error', 'Error', data.message || 'Failed to save IPCR draft');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlertModal('error', 'Error', 'An error occurred while saving the IPCR draft.');
            });
        }

        function extractSoCounts() {
            const tableBody = document.getElementById('ipcrTableBody');
            if (!tableBody) return { strategic_objectives: 0, core_functions: 0, support_functions: 0 };
            
            let counts = {
                strategic_objectives: 0,
                core_functions: 0,
                support_functions: 0
            };
            
            let currentSection = null;
            const rows = tableBody.querySelectorAll('tr');
            
            rows.forEach(row => {
                const className = row.className;
                
                // Detect section headers
                if (className.includes('bg-green-100')) {
                    currentSection = 'strategic_objectives';
                } else if (className.includes('bg-purple-100')) {
                    currentSection = 'core_functions';
                } else if (className.includes('bg-orange-100')) {
                    currentSection = 'support_functions';
                } else if (className.includes('bg-gray-100') && row.querySelector('td[colspan]')) {
                    // Custom section (Others) - don't assign to any count
                    currentSection = null;
                }
                
                // Count SO headers (blue rows) under the current section
                if (className.includes('bg-blue-100') && currentSection) {
                    counts[currentSection]++;
                }
            });
            
            return counts;
        }

        function extractSoCountsFromHtml(tableBodyHtml) {
            if (!tableBodyHtml || typeof tableBodyHtml !== 'string') {
                return { strategic_objectives: 0, core_functions: 0, support_functions: 0 };
            }

            const container = document.createElement('tbody');
            container.innerHTML = tableBodyHtml;

            let counts = {
                strategic_objectives: 0,
                core_functions: 0,
                support_functions: 0
            };

            let currentSection = null;
            const rows = container.querySelectorAll('tr');

            rows.forEach(row => {
                const className = row.className || '';

                if (className.includes('bg-green-100')) {
                    currentSection = 'strategic_objectives';
                } else if (className.includes('bg-purple-100')) {
                    currentSection = 'core_functions';
                } else if (className.includes('bg-orange-100')) {
                    currentSection = 'support_functions';
                } else if (className.includes('bg-gray-100') && row.querySelector('td[colspan]')) {
                    currentSection = null;
                }

                if (className.includes('bg-blue-100') && currentSection) {
                    counts[currentSection]++;
                }
            });

            return counts;
        }

        function saveAsTemplate() {
            const schoolYear = document.getElementById('displaySchoolYear')?.textContent?.trim();
            const semester = document.getElementById('displaySemester')?.textContent?.trim();
            const titleInput = document.getElementById('ipcrDocumentTitle');
            const title = titleInput ? titleInput.value.trim() : `IPCR Template`;
            const tableBody = document.getElementById('ipcrTableBody');
            const tableBodyHtml = tableBody ? buildTableBodySnapshot(tableBody) : '';

            if (!tableBodyHtml || tableBodyHtml.trim() === '') {
                showAlertModal('warning', 'Empty Template', 'Please add some content before saving as a template.');
                return;
            }

            // Extract SO counts
            const soCounts = extractSoCounts();

            const payload = {
                title: title,
                school_year: schoolYear || 'N/A',
                semester: semester || 'N/A',
                table_body_html: tableBodyHtml,
                so_count_json: soCounts
            };

            console.log('Saving template with counts:', soCounts);

            fetch('{{ route("faculty.ipcr.templates.from-saved-copy") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const message = data.updated 
                        ? 'Your existing template has been updated with the new content.' 
                        : 'Your IPCR has been saved as a template. You can find it in the Templates section.';
                    const title = data.updated ? 'Template Updated' : 'Template Saved';
                    
                    showAlertModal('success', title, message, function() {
                        location.reload();
                    });
                } else {
                    showAlertModal('error', 'Error', data.message || 'Failed to save as template');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlertModal('error', 'Error', error.message || 'An error occurred while saving the template.');
            });
        }

        async function getSavedCopies() {
            try {
                const response = await fetch('{{ route("faculty.ipcr.saved-copies.index") }}', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                return data.success ? data.savedCopies : [];
            } catch (error) {
                console.error('Error fetching saved copies:', error);
                return [];
            }
        }

        function buildTableBodySnapshot(tableBody) {
            const clone = tableBody.cloneNode(true);
            clone.querySelectorAll('input, textarea').forEach(field => {
                if (field.tagName.toLowerCase() === 'input') {
                    field.setAttribute('value', field.value);
                } else if (field.tagName.toLowerCase() === 'textarea') {
                    field.textContent = field.value;
                }
            });
            return clone.innerHTML;
        }

        function formatSavedDate(isoDate) {
            const date = new Date(isoDate);
            if (Number.isNaN(date.getTime())) {
                return 'Unknown date';
            }
            return date.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
        }

        async function renderSavedCopies() {
            const list = document.getElementById('savedCopiesList');
            const empty = document.getElementById('savedCopiesEmpty');
            if (!list || !empty) return;

            const savedCopies = await getSavedCopies();
            list.innerHTML = '';

            if (savedCopies.length === 0) {
                empty.classList.remove('hidden');
                return;
            }

            empty.classList.add('hidden');
            savedCopies.forEach(item => {
                const card = document.createElement('div');
                card.className = 'submission-card';
                const savedDate = item.saved_at || item.created_at;
                card.innerHTML = `
                    <div class="flex justify-between items-start gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-semibold text-gray-900">${item.title}</p>
                            <p class="text-xs text-gray-500 mt-1">${item.school_year} • ${item.semester}</p>
                            <p class="text-xs text-gray-500 mt-1">Saved on ${formatSavedDate(savedDate)}</p>
                        </div>
                        <div class="flex gap-2 flex-shrink-0">
                            <button class="text-blue-600 hover:text-blue-700 font-semibold text-xs sm:text-sm" onclick="editSavedCopy(${item.id})">View</button>
                            <button class="text-red-600 hover:text-red-700 font-semibold text-xs sm:text-sm" onclick="deleteSavedCopy(${item.id})">Delete</button>
                        </div>
                    </div>
                `;
                list.appendChild(card);
            });
        }

        function deleteSavedCopy(id) {
            openConfirmationModal(
                'Delete Saved Copy',
                'Are you sure you want to delete this saved copy?',
                'This action cannot be undone.',
                'danger',
                'Delete',
                function() {
                    fetch(`{{ url('faculty/ipcr/saved-copies') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (currentSavedCopyId === id) {
                                currentSavedCopyId = null;
                            }
                            showAlertModal('success', 'Deleted', 'Saved copy deleted successfully!', function() {
                                renderSavedCopies();
                            });
                        } else {
                            showAlertModal('error', 'Error', data.message || 'Failed to delete saved copy');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlertModal('error', 'Error', 'An error occurred while deleting the saved copy.');
                    });
                }
            );
        }

        function updateSoHeaderCountFromTable() {
            const tableBody = document.getElementById('ipcrTableBody');
            if (!tableBody) return;

            const rows = tableBody.querySelectorAll('tr.bg-blue-100');
            let count = 0;
            rows.forEach(row => {
                const input = row.querySelector('input[type="text"]');
                if (input && input.value.includes('SO')) {
                    count += 1;
                }
            });
            soHeaderCount = Math.max(1, count);
        }

        function unhideTableColumns() {
            // Unhide table headers
            const headers = document.querySelectorAll('#ipcrDocumentContainer thead th.hidden');
            headers.forEach(header => header.classList.remove('hidden'));
            
            // Unhide table body cells
            const cells = document.querySelectorAll('#ipcrTableBody td.hidden');
            cells.forEach(cell => cell.classList.remove('hidden'));
        }
        
        function editSavedCopy(id) {
            fetch(`{{ url('faculty/ipcr/saved-copies') }}/${id}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = data.savedCopy;
                    const tableBody = document.getElementById('ipcrTableBody');
                    if (tableBody && item.table_body_html) {
                        tableBody.innerHTML = item.table_body_html;
                        
                        // Unhide columns for saved copy
                        unhideTableColumns();
                    }

                    // Load title
                    const titleInput = document.getElementById('ipcrDocumentTitle');
                    if (titleInput && item.title) {
                        titleInput.value = item.title;
                    }

                    if (item.school_year) {
                        const displaySchoolYear = document.getElementById('displaySchoolYear');
                        if (displaySchoolYear) displaySchoolYear.textContent = item.school_year;
                    }
                    if (item.semester) {
                        const displaySemester = document.getElementById('displaySemester');
                        if (displaySemester) displaySemester.textContent = item.semester;
                    }

                    updateSoHeaderCountFromTable();
                    currentSavedCopyId = item.id;
                    document.getElementById('createButtonArea').style.display = 'none';
                    document.getElementById('ipcrDocumentContainer').classList.remove('hidden');
                } else {
                    showAlertModal('error', 'Not Found', 'Saved copy could not be found.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlertModal('error', 'Error', 'An error occurred while loading the saved copy.');
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderSavedCopies();
            
            // Setup update button event listener
            const updateBtn = document.getElementById('updateSubmissionBtn');
            if (updateBtn) {
                updateBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const submissionId = document.getElementById('currentSubmissionIdToUpdate').value;
                    if (submissionId) {
                        console.log('Update button clicked, submission ID:', submissionId);
                        updateSubmissionData(submissionId);
                    } else {
                        console.error('No submission ID found');
                    }
                });
            }
        });

        function addSectionHeader(headerText = '', isEditable = true) {
            const tableBody = document.getElementById('ipcrTableBody');
            if (!tableBody) return;
            
            // Close dropdown after selection
            toggleSectionHeaderDropdown();
            
            // Reset SO counter for non-custom sections
            if (!isEditable) {
                soHeaderCount = 0;
            }
            
            // Determine color based on section type
            let bgColor = 'bg-gray-100'; // Default for custom/others
            if (!isEditable) {
                if (headerText === 'Strategic Objectives') {
                    bgColor = 'bg-green-100';
                } else if (headerText === 'Core Functions') {
                    bgColor = 'bg-purple-100';
                } else if (headerText === 'Support Function') {
                    bgColor = 'bg-orange-100';
                }
            }
            
            // Create new section header row
            const newRow = document.createElement('tr');
            newRow.className = bgColor;
            
            if (isEditable) {
                // Editable section header (Others/Custom)
                newRow.innerHTML = `
                    <td colspan="9" class="border border-gray-300 px-3 py-2 font-semibold text-gray-800">
                        <input type="text" class="w-full bg-transparent border-0 focus:ring-0 font-semibold text-gray-800" placeholder="Enter custom section header..." value="${headerText}" />
                    </td>
                `;
            } else {
                // Non-editable section header (predefined sections)
                newRow.innerHTML = `
                    <td colspan="9" class="border border-gray-300 px-3 py-2 font-semibold text-gray-800">
                        <div class="font-semibold text-gray-800">${headerText}</div>
                        <input type="hidden" value="${headerText}" />
                    </td>
                `;
                // Track section type for future development
                newRow.setAttribute('data-section-type', headerText.toLowerCase().replace(/\s+/g, '-'));
            }
            
            // Append to table
            tableBody.appendChild(newRow);
        }
        
        function toggleSectionHeaderDropdown() {
            const dropdown = document.getElementById('sectionHeaderDropdownMenu');
            if (!dropdown) return;
            
            dropdown.classList.toggle('hidden');
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('sectionHeaderDropdown');
            const dropdownMenu = document.getElementById('sectionHeaderDropdownMenu');
            
            if (dropdown && dropdownMenu && !dropdown.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });

        function addSOHeader() {
            const tableBody = document.getElementById('ipcrTableBody');
            if (!tableBody) return;
            
            // Find the last section header (green, purple, or orange background)
            const allRows = tableBody.querySelectorAll('tr');
            let lastSectionIndex = -1;
            
            for (let i = allRows.length - 1; i >= 0; i--) {
                const row = allRows[i];
                if (row.classList.contains('bg-green-100') || 
                    row.classList.contains('bg-purple-100') || 
                    row.classList.contains('bg-orange-100')) {
                    lastSectionIndex = i;
                    break;
                }
            }
            
            // Count existing SO headers after the last section header
            let currentSOCount = 0;
            for (let i = lastSectionIndex + 1; i < allRows.length; i++) {
                const row = allRows[i];
                if (row.classList.contains('bg-blue-100')) {
                    const span = row.querySelector('span.font-semibold.text-gray-800');
                    if (span && span.textContent.includes('SO')) {
                        currentSOCount++;
                    }
                }
            }
            
            // Set next SO number
            const nextSONumber = currentSOCount + 1;
            const soLabel = convertToRoman(nextSONumber);
            
            // Create new SO header row
            const newRow = document.createElement('tr');
            newRow.className = 'bg-blue-100';
            newRow.innerHTML = `
                <td colspan="9" class="border border-gray-300 px-3 py-2 font-semibold text-gray-800">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-gray-800">SO ${soLabel}:</span>
                        <input type="text" class="flex-1 bg-transparent border-0 focus:ring-0 font-semibold text-gray-800" placeholder="Enter SO description..." value="" />
                    </div>
                </td>
            `;
            
            // Append to table
            tableBody.appendChild(newRow);
        }

        // Convert number to Roman numeral
        function convertToRoman(num) {
            const romanNumerals = [
                { value: 1000, numeral: 'M' },
                { value: 900, numeral: 'CM' },
                { value: 500, numeral: 'D' },
                { value: 400, numeral: 'CD' },
                { value: 100, numeral: 'C' },
                { value: 90, numeral: 'XC' },
                { value: 50, numeral: 'L' },
                { value: 40, numeral: 'XL' },
                { value: 10, numeral: 'X' },
                { value: 9, numeral: 'IX' },
                { value: 5, numeral: 'V' },
                { value: 4, numeral: 'IV' },
                { value: 1, numeral: 'I' }
            ];
            
            let result = '';
            for (let i = 0; i < romanNumerals.length; i++) {
                while (num >= romanNumerals[i].value) {
                    result += romanNumerals[i].numeral;
                    num -= romanNumerals[i].value;
                }
            }
            return result;
        }

        function addDataRow() {
            const tableBody = document.getElementById('ipcrTableBody');
            if (!tableBody) return;
            
            // Create new data row
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td class="border border-gray-300 px-2 py-2">
                    <textarea class="w-full h-20 px-2 py-1 text-xs resize-none border-0 focus:ring-0" placeholder="Enter MFO"></textarea>
                </td>
                <td class="border border-gray-300 px-2 py-2">
                    <textarea class="w-full h-20 px-2 py-1 text-xs resize-none border-0 focus:ring-0"></textarea>
                </td>
                <td class="border border-gray-300 px-2 py-2">
                    <textarea class="w-full h-20 px-2 py-1 text-xs resize-none border-0 focus:ring-0"></textarea>
                </td>
                <td class="border border-gray-300 px-2 py-2 hidden">
                    <textarea class="w-full h-20 px-2 py-1 text-xs resize-none border-0 focus:ring-0"></textarea>
                </td>
                <td class="border border-gray-300 px-2 py-2 hidden">
                    <input type="number" class="w-full h-20 px-2 py-1 text-xs text-center border-0 focus:ring-0" min="0" max="5" value="">
                </td>
                <td class="border border-gray-300 px-2 py-2 hidden">
                    <input type="number" class="w-full h-20 px-2 py-1 text-xs text-center border-0 focus:ring-0" min="0" max="5" value="">
                </td>
                <td class="border border-gray-300 px-2 py-2 hidden">
                    <input type="number" class="w-full h-20 px-2 py-1 text-xs text-center border-0 focus:ring-0" min="0" max="5" value="">
                </td>
                <td class="border border-gray-300 px-2 py-2 hidden">
                    <input type="number" class="w-full h-20 px-2 py-1 text-xs text-center border-0 focus:ring-0" min="0" max="5" value="">
                </td>
                <td class="border border-gray-300 px-2 py-2 hidden">
                    <textarea class="w-full h-20 px-2 py-1 text-xs resize-none border-0 focus:ring-0"></textarea>
                </td>
            `;
            
            // Append to table
            tableBody.appendChild(newRow);
        }

        function deleteSectionHeader(button) {
            // Find the parent row and remove it
            const row = button.closest('tr');
            if (row) {
                row.remove();
            }
        }

        function removeLastSectionHeader() {
            const tableBody = document.getElementById('ipcrTableBody');
            if (!tableBody) return;
            
            // Find all rows and remove the last one that looks like a section header (bg-blue-100 or bg-blue-50)
            const rows = tableBody.querySelectorAll('tr');
            for (let i = rows.length - 1; i >= 0; i--) {
                if (rows[i].classList.contains('bg-blue-100') || rows[i].classList.contains('bg-blue-50')) {
                    rows[i].remove();
                    break;
                }
            }
        }

        function removeLastSOHeader() {
            const tableBody = document.getElementById('ipcrTableBody');
            if (!tableBody) return;
            
            // Find all rows and remove the last one that is a SO header (bg-blue-100 with specific structure)
            const rows = tableBody.querySelectorAll('tr');
            for (let i = rows.length - 1; i >= 0; i--) {
                if (rows[i].classList.contains('bg-blue-100')) {
                    const span = rows[i].querySelector('span');
                    if (span && span.textContent.includes('SO')) {
                        rows[i].remove();
                        soHeaderCount--;
                        break;
                    }
                }
            }
        }

        function removeLastDataRow() {
            const tableBody = document.getElementById('ipcrTableBody');
            if (!tableBody) return;
            
            // Find all rows and remove the last one that is a data row (no special classes)
            const rows = tableBody.querySelectorAll('tr');
            for (let i = rows.length - 1; i >= 0; i--) {
                if (!rows[i].classList.contains('bg-blue-50') && 
                    !rows[i].classList.contains('bg-blue-100') && 
                    !rows[i].classList.contains('bg-green-100') &&
                    !rows[i].classList.contains('bg-gray-100')) {
                    rows[i].remove();
                    break;
                }
            }
        }

        function removeLastRow() {
            const tableBody = document.getElementById('ipcrTableBody');
            if (!tableBody) return;
            
            // Remove the last row in the table body
            const rows = tableBody.querySelectorAll('tr');
            if (rows.length > 0) {
                rows[rows.length - 1].remove();
            }
        }

        // Text formatting functions
        let activeEditableField = null;
        
        function formatText(command) {
            if (!activeEditableField || !activeEditableField.classList.contains('editable-field')) {
                console.log('No active field');
                return;
            }
            
            console.log('Formatting with command:', command);
            console.log('Active field:', activeEditableField);
            
            // Focus the field first
            activeEditableField.focus();
            
            // Small delay to ensure focus is set
            setTimeout(() => {
                document.execCommand(command, false, null);
                updateFormatButtons();
            }, 10);
        }
        
        function updateFormatButtons() {
            const toolbar = document.querySelector('.format-toolbar');
            if (!toolbar) return;
            
            const buttons = toolbar.querySelectorAll('.format-btn');
            buttons[0].classList.toggle('active', document.queryCommandState('bold'));
            buttons[1].classList.toggle('active', document.queryCommandState('italic'));
            buttons[2].classList.toggle('active', document.queryCommandState('underline'));
        }
        
        function setupFormatField(field) {
            console.log('Setting up format field:', field);
            
            // Track focus to know which field is active
            field.addEventListener('focus', function() {
                activeEditableField = field;
                console.log('Field focused:', field);
                updateFormatButtons();
            });
            
            field.addEventListener('blur', function() {
                console.log('Field blurred');
            });
            
            // Handle keyboard shortcuts
            field.addEventListener('keydown', function(e) {
                if (e.ctrlKey || e.metaKey) {
                    if (e.key === 'b') {
                        e.preventDefault();
                        document.execCommand('bold');
                        updateFormatButtons();
                    } else if (e.key === 'i') {
                        e.preventDefault();
                        document.execCommand('italic');
                        updateFormatButtons();
                    } else if (e.key === 'u') {
                        e.preventDefault();
                        document.execCommand('underline');
                        updateFormatButtons();
                    }
                }
            });
            
            // Update buttons on selection change
            field.addEventListener('mouseup', () => updateFormatButtons());
            field.addEventListener('keyup', () => updateFormatButtons());
        }

        // Initialize formatting toolbar event listeners
        function initFormatToolbar() {
            const toolbar = document.querySelector('.format-toolbar');
            if (!toolbar) return;
            
            const buttons = toolbar.querySelectorAll('.format-btn');
            buttons.forEach(button => {
                button.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    const command = this.getAttribute('data-command');
                    formatText(command);
                });
            });
        }
        
        function showIPCRForm() {
            openIpcrFormModal();
            
            // Initialize toolbar event listeners
            initFormatToolbar();
            
            // Setup formatting for the initial Strategic Objectives field
            const strategicField = document.querySelector('#strategicObjectivesContainer .editable-field');
            if (strategicField) setupFormatField(strategicField);
        }

        function addHeader() {
            headerCount++;
            const headersContainer = document.getElementById('headersContainer');
            
            const headerDiv = document.createElement('div');
            headerDiv.className = 'mb-2';
            headerDiv.id = `header-${headerCount}`;
            headerDiv.dataset.headerId = headerCount;
            headerDiv.dataset.type = 'header';
            
            const soFieldId = `so-field-${headerCount}`;
            
            headerDiv.innerHTML = `
                <div class="flex items-start gap-2">
                    <div id="${soFieldId}" class="editable-field flex-1" contenteditable="true" data-placeholder="SO I"></div>
                    <div class="flex flex-col gap-0.5">
                        <button type="button" onclick="moveHeaderUp(${headerCount})" class="bg-blue-50 text-blue-600 border border-blue-200 w-5 h-5 flex items-center justify-center hover:bg-blue-100 hover:border-blue-300 transition-all" title="Move Up">
                            <i class="fas fa-chevron-up" style="font-size: 8px;"></i>
                        </button>
                        <button type="button" onclick="moveHeaderDown(${headerCount})" class="bg-blue-50 text-blue-600 border border-blue-200 w-5 h-5 flex items-center justify-center hover:bg-blue-100 hover:border-blue-300 transition-all" title="Move Down">
                            <i class="fas fa-chevron-down" style="font-size: 8px;"></i>
                        </button>
                    </div>
                    <button type="button" onclick="removeHeader(${headerCount})" class="bg-red-50 text-red-600 border border-red-200 w-5 h-5 flex items-center justify-center hover:bg-red-100 hover:border-red-300 flex-shrink-0 transition-all">
                        <i class="fas fa-times" style="font-size: 8px;"></i>
                    </button>
                </div>
            `;
            
            headersContainer.appendChild(headerDiv);
            
            // Setup formatting for the new field
            const soField = document.getElementById(soFieldId);
            if (soField) setupFormatField(soField);
            
            currentHeaderForRows = headerCount;
        }

        function addRow() {
            if (currentHeaderForRows === null) {
                showAlertModal('warning', 'Add Header First', 'Please add a header first before adding rows.');
                return;
            }
            
            const headersContainer = document.getElementById('headersContainer');
            if (!headersContainer) {
                showAlertModal('warning', 'Add Header First', 'Please add a header first.');
                return;
            }
            
            const rowId = `row-${currentHeaderForRows}-${Date.now()}`;
            const mroFieldId = `mro-${rowId}`;
            const successFieldId = `success-${rowId}`;
            
            const rowDiv = document.createElement('div');
            rowDiv.className = 'mb-2';
            rowDiv.id = rowId;
            rowDiv.dataset.type = 'row';
            rowDiv.dataset.headerId = currentHeaderForRows;
            
            rowDiv.innerHTML = `
                <div class="flex items-start gap-2">
                    <div class="flex-1 grid grid-cols-2 gap-4">
                        <div id="${mroFieldId}" class="editable-field" contenteditable="true" data-placeholder="Major Responsibility & Output"></div>
                        <div id="${successFieldId}" class="editable-field" contenteditable="true" data-placeholder="Success Indicator"></div>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <button type="button" onclick="moveRowUp('${rowId}')" class="bg-indigo-50 text-indigo-600 border border-indigo-200 w-5 h-5 flex items-center justify-center hover:bg-indigo-100 hover:border-indigo-300 transition-all" title="Move Up">
                            <i class="fas fa-chevron-up" style="font-size: 8px;"></i>
                        </button>
                        <button type="button" onclick="moveRowDown('${rowId}')" class="bg-indigo-50 text-indigo-600 border border-indigo-200 w-5 h-5 flex items-center justify-center hover:bg-indigo-100 hover:border-indigo-300 transition-all" title="Move Down">
                            <i class="fas fa-chevron-down" style="font-size: 8px;"></i>
                        </button>
                    </div>
                    <button type="button" onclick="removeRow('${rowId}')" class="bg-red-50 text-red-600 border border-red-200 w-5 h-5 flex items-center justify-center hover:bg-red-100 hover:border-red-300 flex-shrink-0 transition-all">
                        <i class="fas fa-times" style="font-size: 8px;"></i>
                    </button>
                </div>
            `;
            
            headersContainer.appendChild(rowDiv);
            
            // Setup formatting for the new fields
            const mroField = document.getElementById(mroFieldId);
            const successField = document.getElementById(successFieldId);
            if (mroField) setupFormatField(mroField);
            if (successField) setupFormatField(successField);
        }

        function removeHeader(headerId) {
            const headerElement = document.getElementById(`header-${headerId}`);
            if (headerElement) {
                headerElement.remove();
                if (currentHeaderForRows === headerId) {
                    // Find the last remaining header or reset to null
                    const remainingHeaders = document.querySelectorAll('[data-header-id]');
                    if (remainingHeaders.length > 0) {
                        currentHeaderForRows = parseInt(remainingHeaders[remainingHeaders.length - 1].dataset.headerId);
                    } else {
                        currentHeaderForRows = null;
                    }
                }
            }
        }

        function removeRow(rowId) {
            const rowElement = document.getElementById(rowId);
            if (rowElement) {
                rowElement.remove();
            }
        }

        function clearForm() {
            openConfirmationModal(
                'Clear Form',
                'Are you sure you want to clear the form?',
                'All unsaved data will be lost.',
                'warning',
                'Clear',
                function() {
                    // Clear strategic objectives
                    document.getElementById('strategicObjectivesContainer').innerHTML = `
                        <div class="mb-4">
                            <div class="editable-field" contenteditable="true" data-placeholder="Strategic objectives..."></div>
                        </div>
                    `;
                    
                    // Setup formatting for the new field
                    const strategicField = document.querySelector('#strategicObjectivesContainer .editable-field');
                    if (strategicField) setupFormatField(strategicField);
                    
                    // Clear all headers
                    document.getElementById('headersContainer').innerHTML = '';
                    
                    // Reset counters
                    headerCount = 0;
                    currentHeaderForRows = null;
                }
            );
        }

        function generateIPCR() {
            // Validate form has content
            const stratObjectives = document.querySelectorAll('#strategicObjectivesContainer .editable-field');
            const headers = document.querySelectorAll('[data-header-id]');
            
            let hasContent = false;
            stratObjectives.forEach(field => {
                if (field.innerHTML.trim()) hasContent = true;
            });
            
            if (!hasContent && headers.length === 0) {
                showAlertModal('warning', 'Empty Form', 'Please fill in at least some content before saving.');
                return;
            }
            
            // Get template name - from edit field if in edit mode, otherwise generate default
            let templateName;
            if (isEditMode) {
                templateName = document.getElementById('currentTemplateName').value.trim() || 'IPCR Template';
            } else {
                const now = new Date();
                const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                const currentMonth = monthNames[now.getMonth()];
                const currentYear = now.getFullYear();
                templateName = `IPCR - ${currentMonth} ${currentYear}`;
            }
            
            // Collect form data
            const formData = {
                title: templateName,
                strategic_objectives: [],
                headers: []
            };
            
            console.log('==== COLLECTING FORM DATA ====');
            
            // Get strategic objectives (already queried above, reuse the same variable)
            console.log('Strategic objective fields found:', stratObjectives.length);
            stratObjectives.forEach((field, i) => {
                console.log(`SO ${i}:`, field.innerHTML);
                if (field.innerHTML.trim()) {
                    formData.strategic_objectives.push(field.innerHTML.trim());
                }
            });
            
            // Get headers and rows - Check container exists
            const headersContainer = document.getElementById('headersContainer');
            console.log('Headers container:', headersContainer);
            console.log('Headers container HTML:', headersContainer ? headersContainer.innerHTML : 'NULL');
            
            // Get all items (headers and rows) from container
            const allItems = headersContainer ? headersContainer.children : [];
            console.log('Total items in container:', allItems.length);
            
            let currentHeader = null;
            
            for (let i = 0; i < allItems.length; i++) {
                const item = allItems[i];
                const itemType = item.dataset.type;
                
                console.log(`Item ${i}: type=${itemType}, id=${item.id}`);
                
                if (itemType === 'header') {
                    const soField = item.querySelector('.editable-field');
                    currentHeader = {
                        so: soField ? soField.innerHTML.trim() : '',
                        rows: []
                    };
                    formData.headers.push(currentHeader);
                    console.log(`Created header: ${currentHeader.so}`);
                } else if (itemType === 'row' && currentHeader) {
                    const editableFields = item.querySelectorAll('.editable-field');
                    if (editableFields.length === 2) {
                        const rowData = {
                            mro: editableFields[0].innerHTML.trim(),
                            success_indicator: editableFields[1].innerHTML.trim()
                        };
                        currentHeader.rows.push(rowData);
                        console.log(`Added row to current header:`, rowData);
                    }
                }
            }
            
            console.log('\n==== FINAL FORM DATA ====');
            console.log('Final form data to save:', formData);
            console.log('Total headers to save:', formData.headers.length);
            formData.headers.forEach((h, i) => {
                console.log(`Header ${i}:`, h);
            });
            
            // Ensure arrays are properly formatted
            const dataToSend = {
                title: formData.title,
                strategic_objectives: Array.isArray(formData.strategic_objectives) ? formData.strategic_objectives : [],
                headers: Array.isArray(formData.headers) ? formData.headers : []
            };
            
            console.log('\n==== DATA TO SEND ====');
            console.log('Data being sent to server:', dataToSend);
            console.log('JSON stringified:', JSON.stringify(dataToSend));
            
            // Determine if update or create
            const url = isEditMode ? `{{ url('faculty/ipcr/templates') }}/${currentTemplateId}` : '{{ route("faculty.ipcr.store") }}';
            const method = isEditMode ? 'PUT' : 'POST';
            
            // Send formData to server via AJAX
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(dataToSend)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlertModal('success', isEditMode ? 'Changes Saved' : 'Template Created', isEditMode ? 'Your changes have been saved successfully!' : 'IPCR template created successfully!', function() {
                        location.reload();
                    });
                } else {
                    showAlertModal('error', 'Error', data.message || 'Failed to save IPCR template');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlertModal('error', 'Error', 'An error occurred while saving the IPCR template.');
            });
        }

        function loadTemplateToDocument(templateId) {
            fetch(`{{ url('faculty/ipcr/templates') }}/${templateId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const template = data.template;
                    
                    // Check if template has table_body_html
                    if (template.table_body_html) {
                        const tableBody = document.getElementById('templatePreviewTableBody');
                        if (tableBody) {
                            tableBody.innerHTML = template.table_body_html;
                        }

                        // Load title
                        const titleElement = document.getElementById('templatePreviewTitle');
                        if (titleElement && template.title) {
                            titleElement.textContent = template.title;
                        }

                        // Load school year and semester
                        if (template.school_year) {
                            const displaySchoolYear = document.getElementById('templatePreviewSchoolYear');
                            if (displaySchoolYear) displaySchoolYear.textContent = template.school_year;
                        }
                        if (template.semester) {
                            const displaySemester = document.getElementById('templatePreviewSemester');
                            if (displaySemester) displaySemester.textContent = template.semester;
                        }

                        // Store template ID for save copy functionality
                        const templateIdField = document.getElementById('currentPreviewTemplateId');
                        if (templateIdField) {
                            templateIdField.value = templateId;
                        }

                        document.getElementById('templatePreviewModal').classList.remove('hidden');
                    } else {
                        showAlertModal('info', 'Legacy Template', 'This template was created with the old format. Please use the Edit button to modify it.');
                    }
                } else {
                    showAlertModal('error', 'Not Found', 'Template could not be found.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlertModal('error', 'Error', 'An error occurred while loading the template.');
            });
        }
        
        function closeTemplatePreview() {
            // Check if we're in edit mode with unsaved changes
            const submissionIdField = document.getElementById('currentSubmissionIdToUpdate');
            if (submissionIdField && submissionIdField.value) {
                // Check if table has been modified
                const tableBody = document.getElementById('templatePreviewTableBody');
                if (tableBody && tableBody.innerHTML.trim() !== '') {
                    if (!confirm('You have unsaved changes. Are you sure you want to close without saving?')) {
                        return;
                    }
                }
                submissionIdField.value = '';
            }
            
            // Make table cells non-editable when closing
            const cells = document.getElementById('templatePreviewTableBody')?.querySelectorAll('td');
            if (cells) {
                cells.forEach(cell => {
                    cell.removeAttribute('contenteditable');
                    cell.style.cursor = 'auto';
                });
            }
            
            document.getElementById('templatePreviewModal').classList.add('hidden');
        }
        
        function loadTemplate(templateId) {
            fetch(`{{ url('faculty/ipcr/templates') }}/${templateId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('==== RAW API RESPONSE ====');
                    console.log('Full data:', data);
                    console.log('Template object:', data.template);
                    console.log('Content field:', data.template.content);
                    console.log('Content type:', typeof data.template.content);
                    
                    if (data.success) {
                        const template = data.template;
                        let content;
                        
                        // Parse content if it's a string, otherwise use as is
                        if (typeof template.content === 'string') {
                            console.log('Parsing content as JSON string');
                            content = JSON.parse(template.content);
                        } else {
                            console.log('Content is already an object');
                            content = template.content;
                        }
                        
                        console.log('==== PARSED CONTENT ====');
                        console.log('Full content:', content);
                        console.log('Strategic objectives:', content.strategic_objectives);
                        console.log('Headers array:', content.headers);
                        console.log('Number of headers:', content.headers ? content.headers.length : 0);
                        
                        // Ensure headers is an array
                        if (!Array.isArray(content.headers)) {
                            console.warn('Headers is not an array, converting to array');
                            content.headers = content.headers ? Object.values(content.headers) : [];
                        }
                        
                        // Ensure strategic_objectives is an array
                        if (!Array.isArray(content.strategic_objectives)) {
                            console.warn('Strategic objectives is not an array, converting to array');
                            content.strategic_objectives = content.strategic_objectives ? Object.values(content.strategic_objectives) : [];
                        }
                        
                        if (content.headers) {
                            content.headers.forEach((h, i) => {
                                console.log(`Header ${i}:`, h);
                                console.log(`  - SO: ${h.so}`);
                                console.log(`  - Rows:`, h.rows);
                                console.log(`  - Row count: ${h.rows ? h.rows.length : 0}`);
                            });
                        }
                        
                        // Set edit mode
                        isEditMode = true;
                        currentTemplateId = templateId;
                        
                        // Update button text
                        document.getElementById('saveButton').innerHTML = '<i class="fas fa-save mr-2"></i>Save Changes';
                        
                        // Set template name
                        document.getElementById('currentTemplateName').value = template.title;
                        
                        // Show the form
                        document.getElementById('createButtonArea').style.display = 'none';
                        document.getElementById('ipcrFormContainer').style.display = 'block';
                        
                        // Clear existing form
                        document.getElementById('strategicObjectivesContainer').innerHTML = '';
                        document.getElementById('headersContainer').innerHTML = '';
                        headerCount = 0;
                        currentHeaderForRows = null;
                        
                        // Load strategic objectives
                        if (content.strategic_objectives && Array.isArray(content.strategic_objectives) && content.strategic_objectives.length > 0) {
                            content.strategic_objectives.forEach(obj => {
                                const div = document.createElement('div');
                                div.className = 'mb-4';
                                const soContent = obj || '';
                                div.innerHTML = `<div class="editable-field" contenteditable="true" data-placeholder="Strategic objectives...">${soContent}</div>`;
                                document.getElementById('strategicObjectivesContainer').appendChild(div);
                                
                                // Setup formatting for this field
                                const field = div.querySelector('.editable-field');
                                if (field) setupFormatField(field);
                            });
                        } else {
                            const div = document.createElement('div');
                            div.className = 'mb-4';
                            div.innerHTML = `<div class="editable-field" contenteditable="true" data-placeholder="Strategic objectives..."></div>`;
                            document.getElementById('strategicObjectivesContainer').appendChild(div);
                            
                            // Setup formatting for this field
                            const field = div.querySelector('.editable-field');
                            if (field) setupFormatField(field);
                        }
                        
                        // Load headers and rows
                        if (content.headers && Array.isArray(content.headers) && content.headers.length > 0) {
                            console.log('Loading headers...', content.headers);
                            
                            for (let headerIndex = 0; headerIndex < content.headers.length; headerIndex++) {
                                const headerData = content.headers[headerIndex];
                                console.log(`Loading header ${headerIndex + 1}:`, headerData);
                                
                                headerCount++;
                                const currentHeaderId = headerCount;
                                const headersContainer = document.getElementById('headersContainer');
                                
                                const soValue = (headerData.so || '').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
                                
                                // Create header element
                                const headerDiv = document.createElement('div');
                                headerDiv.className = 'mb-2';
                                headerDiv.id = `header-${currentHeaderId}`;
                                headerDiv.dataset.headerId = currentHeaderId;
                                headerDiv.dataset.type = 'header';
                                
                                const soFieldId = `so-field-${currentHeaderId}`;
                                const soContent = headerData.so || '';
                                
                                headerDiv.innerHTML = `
                                    <div class="flex items-start gap-2">
                                        <div id="${soFieldId}" class="editable-field flex-1" contenteditable="true" data-placeholder="SO I">${soContent}</div>
                                        <div class="flex flex-col gap-0.5">
                                            <button type="button" onclick="moveHeaderUp(${currentHeaderId})" class="bg-blue-50 text-blue-600 border border-blue-200 w-5 h-5 flex items-center justify-center hover:bg-blue-100 hover:border-blue-300 transition-all" title="Move Up">
                                                <i class="fas fa-chevron-up" style="font-size: 8px;"></i>
                                            </button>
                                            <button type="button" onclick="moveHeaderDown(${currentHeaderId})" class="bg-blue-50 text-blue-600 border border-blue-200 w-5 h-5 flex items-center justify-center hover:bg-blue-100 hover:border-blue-300 transition-all" title="Move Down">
                                                <i class="fas fa-chevron-down" style="font-size: 8px;"></i>
                                            </button>
                                        </div>
                                        <button type="button" onclick="removeHeader(${currentHeaderId})" class="bg-red-50 text-red-600 border border-red-200 w-5 h-5 flex items-center justify-center hover:bg-red-100 hover:border-red-300 flex-shrink-0 transition-all">
                                            <i class="fas fa-times" style="font-size: 8px;"></i>
                                        </button>
                                    </div>
                                `;
                                
                                headersContainer.appendChild(headerDiv);
                                
                                // Setup formatting for the loaded field
                                const soField = document.getElementById(soFieldId);
                                if (soField) setupFormatField(soField);
                                
                                console.log(`Header ${currentHeaderId} appended to DOM`);
                                
                                // Create rows for this header
                                if (headerData.rows && headerData.rows.length > 0) {
                                    for (let rowIndex = 0; rowIndex < headerData.rows.length; rowIndex++) {
                                        const rowData = headerData.rows[rowIndex];
                                        const rowId = `row-${currentHeaderId}-${rowIndex}-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
                                        const mroFieldId = `mro-${rowId}`;
                                        const successFieldId = `success-${rowId}`;
                                        
                                        const mroContent = rowData.mro || '';
                                        const successContent = rowData.success_indicator || '';
                                        
                                        const rowDiv = document.createElement('div');
                                        rowDiv.className = 'mb-2';
                                        rowDiv.id = rowId;
                                        rowDiv.dataset.type = 'row';
                                        rowDiv.dataset.headerId = currentHeaderId;
                                        
                                        rowDiv.innerHTML = `
                                            <div class="flex items-start gap-2">
                                                <div class="flex-1 grid grid-cols-2 gap-4">
                                                    <div id="${mroFieldId}" class="editable-field" contenteditable="true" data-placeholder="Major Responsibility & Output">${mroContent}</div>
                                                    <div id="${successFieldId}" class="editable-field" contenteditable="true" data-placeholder="Success Indicator">${successContent}</div>
                                                </div>
                                                <div class="flex flex-col gap-0.5">
                                                    <button type="button" onclick="moveRowUp('${rowId}')" class="bg-indigo-50 text-indigo-600 border border-indigo-200 w-5 h-5 flex items-center justify-center hover:bg-indigo-100 hover:border-indigo-300 transition-all" title="Move Up">
                                                        <i class="fas fa-chevron-up" style="font-size: 8px;"></i>
                                                    </button>
                                                    <button type="button" onclick="moveRowDown('${rowId}')" class="bg-indigo-50 text-indigo-600 border border-indigo-200 w-5 h-5 flex items-center justify-center hover:bg-indigo-100 hover:border-indigo-300 transition-all" title="Move Down">
                                                        <i class="fas fa-chevron-down" style="font-size: 8px;"></i>
                                                    </button>
                                                </div>
                                                <button type="button" onclick="removeRow('${rowId}')" class="bg-red-50 text-red-600 border border-red-200 w-5 h-5 flex items-center justify-center hover:bg-red-100 hover:border-red-300 flex-shrink-0 transition-all">
                                                    <i class="fas fa-times" style="font-size: 8px;"></i>
                                                </button>
                                            </div>
                                        `;
                                        
                                        headersContainer.appendChild(rowDiv);
                                        
                                        // Setup formatting for the loaded fields
                                        const mroField = document.getElementById(mroFieldId);
                                        const successField = document.getElementById(successFieldId);
                                        if (mroField) setupFormatField(mroField);
                                        if (successField) setupFormatField(successField);
                                        
                                        headersContainer.appendChild(rowDiv);
                                        console.log(`Row ${rowIndex + 1} for header ${currentHeaderId} appended to DOM`);
                                    }
                                    console.log(`Built ${headerData.rows.length} rows for header ${currentHeaderId}`);
                                }
                                
                                currentHeaderForRows = currentHeaderId;
                            }
                            
                            console.log('All headers loaded. Total headers:', headerCount);
                        }
                    } else {
                        showAlertModal('error', 'Load Failed', data.message || 'Failed to load template');
                    }
                })
                .catch(error => {
                    console.error('==== ERROR DETAILS ====');
                    console.error('Error type:', error.name);
                    console.error('Error message:', error.message);
                    console.error('Error stack:', error.stack);
                    console.error('Full error:', error);
                    showAlertModal('error', 'Error', 'An error occurred while loading the template.');
                });
        }
        
        function deleteTemplate(templateId) {
            openConfirmationModal(
                'Delete Template',
                'Are you sure you want to delete this template?',
                'This action cannot be undone.',
                'danger',
                'Delete',
                function() {
                    fetch(`{{ url('faculty/ipcr/templates') }}/${templateId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlertModal('success', 'Deleted', 'Template deleted successfully!', function() {
                                location.reload();
                            });
                        } else {
                            showAlertModal('error', 'Delete Failed', data.message || 'Failed to delete template');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlertModal('error', 'Error', 'An error occurred while deleting the template.');
                    });
                }
            );
        }
        
        function setActiveTemplate(templateId) {
            fetch(`{{ url('faculty/ipcr/templates') }}/${templateId}/set-active`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Template set as active');
                    // Reload to update dashboard counts
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showAlertModal('error', 'Error', data.message || 'Failed to set template as active');
                    // Reload to reset radio button state
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlertModal('error', 'Error', 'An error occurred while setting the active template.');
                location.reload();
            });
        }

        function setActiveSubmission(submissionId) {
            fetch(`{{ url('faculty/ipcr/submissions') }}/${submissionId}/set-active`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Submission set as active');
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showAlertModal('error', 'Error', data.message || 'Failed to set submission as active');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlertModal('error', 'Error', 'An error occurred while setting the active submission.');
                location.reload();
            });
        }

        function saveCopyFromTemplate(templateId) {
            fetch(`{{ url('faculty/ipcr/templates') }}/${templateId}/save-copy`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlertModal('success', 'Success', 'Template saved to Saved Copy successfully');
                    // Close preview modal
                    closeTemplatePreview();
                    // Refresh saved copies list
                    renderSavedCopies();
                } else {
                    showAlertModal('error', 'Error', data.message || 'Failed to save copy');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlertModal('error', 'Error', 'An error occurred while saving the copy.');
            });
        }

        function saveCopyFromPreview() {
            console.log('saveCopyFromPreview called');
            
            // Check if we're updating a submission
            const submissionIdField = document.getElementById('currentSubmissionIdToUpdate');
            console.log('Submission ID field:', submissionIdField);
            console.log('Submission ID value:', submissionIdField ? submissionIdField.value : 'NOT FOUND');
            
            if (submissionIdField && submissionIdField.value) {
                console.log('Calling updateSubmissionData with ID:', submissionIdField.value);
                updateSubmissionData(submissionIdField.value);
                return;
            }

            const templateIdField = document.getElementById('currentPreviewTemplateId');
            if (!templateIdField || !templateIdField.value) {
                showAlertModal('error', 'Error', 'No template selected');
                return;
            }
            saveCopyFromTemplate(templateIdField.value);
        }

        function updateSubmissionData(submissionId) {
            console.log('=== updateSubmissionData called ===');
            console.log('Submission ID:', submissionId);
            
            const tableBody = document.getElementById('templatePreviewTableBody');
            
            if (!tableBody) {
                showAlertModal('error', 'Error', 'Table body not found');
                return;
            }
            
            // IMPORTANT: Sync all input/textarea values before capturing HTML
            console.log('Syncing all field values...');
            const allCells = tableBody.querySelectorAll('td');
            let syncCount = 0;
            allCells.forEach(cell => {
                // Sync all textareas - set their textContent to their current value
                const textareas = cell.querySelectorAll('textarea');
                textareas.forEach((textarea) => {
                    const currentValue = textarea.value;
                    textarea.textContent = currentValue;
                    textarea.innerHTML = currentValue;
                    syncCount++;
                    console.log(`Synced textarea (${currentValue.length} chars): "${currentValue.substring(0, 50)}"`);
                });
                
                // Sync all regular inputs (not hidden) - ensure their value attribute is current
                const visibleInputs = cell.querySelectorAll('input:not([type="hidden"])');
                visibleInputs.forEach((input) => {
                    const currentValue = input.value;
                    input.setAttribute('value', currentValue);
                    syncCount++;
                    console.log(`Synced input: "${currentValue}"`);
                });
                
                // Sync hidden inputs with their associated div content
                const hiddenInputs = cell.querySelectorAll('input[type="hidden"]');
                const divs = cell.querySelectorAll('div');
                
                hiddenInputs.forEach((input, idx) => {
                    const div = divs[idx];
                    if (div && !div.querySelector('textarea') && !div.querySelector('input')) {
                        const oldValue = input.value;
                        const newValue = div.textContent.trim();
                        if (oldValue !== newValue) {
                            input.value = newValue;
                            input.setAttribute('value', newValue);
                            div.textContent = newValue;
                            syncCount++;
                            console.log(`Synced hidden input: "${oldValue}" -> "${newValue}"`);
                        }
                    }
                });
            });
            console.log(`Total fields synced: ${syncCount}`);
            
            // Clone the table body to work with a copy
            const clonedTableBody = tableBody.cloneNode(true);
            
            // Remove contenteditable attributes and inline styles that were added for editing
            const allClonedCells = clonedTableBody.querySelectorAll('td');
            allClonedCells.forEach(cell => {
                cell.removeAttribute('contenteditable');
                // Remove the blue background we added
                if (cell.style.backgroundColor === 'rgb(240, 249, 255)') {
                    cell.style.backgroundColor = '';
                }
                if (cell.style.cursor === 'default') {
                    cell.style.cursor = '';
                }
                if (cell.style.userSelect === 'none') {
                    cell.style.userSelect = '';
                }
                // Clean up empty style attribute
                if (cell.getAttribute('style') === '') {
                    cell.removeAttribute('style');
                }
            });
            
            // Also remove editing styles from textareas and inputs in cloned table
            const clonedTextareas = clonedTableBody.querySelectorAll('textarea');
            const clonedInputs = clonedTableBody.querySelectorAll('input');
            
            clonedTextareas.forEach(textarea => {
                textarea.removeAttribute('contenteditable');
                if (textarea.style.pointerEvents === 'auto') textarea.style.pointerEvents = '';
                if (textarea.style.backgroundColor === 'white') textarea.style.backgroundColor = '';
                if (textarea.style.cursor === 'text') textarea.style.cursor = '';
                if (textarea.style.userSelect === 'text') textarea.style.userSelect = '';
                if (textarea.getAttribute('style') === '') textarea.removeAttribute('style');
            });
            
            clonedInputs.forEach(input => {
                input.removeAttribute('contenteditable');
                if (input.style.pointerEvents === 'auto') input.style.pointerEvents = '';
                if (input.style.backgroundColor === 'white') input.style.backgroundColor = '';
                if (input.style.cursor === 'text') input.style.cursor = '';
                if (input.style.userSelect === 'text') input.style.userSelect = '';
                if (input.getAttribute('style') === '') input.removeAttribute('style');
            });
            
            let tableBodyHtml = clonedTableBody.innerHTML;

            console.log('Table HTML length:', tableBodyHtml.length);
            console.log('First 500 chars:', tableBodyHtml.substring(0, 500));

            if (!tableBodyHtml || tableBodyHtml.trim() === '') {
                showAlertModal('error', 'Empty Table', 'Cannot update with empty table content.');
                return;
            }

            // Use FormData instead of JSON to avoid JSON encoding issues with HTML
            const formData = new FormData();
            formData.append('table_body_html', tableBodyHtml);
            formData.append('_method', 'PUT');

            console.log('FormData created, attempting fetch...');
            console.log('Fetch URL:', `{{ url('faculty/ipcr/submissions') }}/${submissionId}`);

            // Show loading state
            const updateBtn = document.getElementById('updateSubmissionBtn');
            const originalText = updateBtn.textContent;
            updateBtn.disabled = true;
            updateBtn.textContent = 'Updating...';

            fetch(`{{ url('faculty/ipcr/submissions') }}/${submissionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                console.log('Response received, status:', response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Response error text:', text);
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response JSON:', data);
                if (data.success) {
                    // Clear the submission ID field
                    const submissionIdField = document.getElementById('currentSubmissionIdToUpdate');
                    if (submissionIdField) submissionIdField.value = '';

                    // Close the modal
                    closeTemplatePreview();
                    
                    showAlertModal('success', 'Updated Successfully', 'The submitted IPCR has been updated successfully.', function() {
                        // Reload page to reflect changes
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    });
                } else {
                    showAlertModal('error', 'Update Failed', data.message || 'Failed to update submission');
                    updateBtn.disabled = false;
                    updateBtn.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showAlertModal('error', 'Error', error.message || 'An error occurred while updating the submission.');
                updateBtn.disabled = false;
                updateBtn.textContent = originalText;
            });
        }
        
        function deleteSubmission(submissionId) {
            console.log('Delete submission:', submissionId);
            
            if (confirm('Are you sure you want to delete this submission? This action cannot be undone.')) {
                try {
                    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfTokenElement) {
                        showAlertModal('error', 'Error', 'CSRF token not found. Please refresh the page.');
                        return;
                    }
                    
                    const csrfToken = csrfTokenElement.getAttribute('content');
                    const url = `/faculty/ipcr/submissions/${submissionId}`;
                    
                    console.log('Deleting submission:', submissionId);
                    
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json().catch(() => ({ success: false, message: 'Invalid response' }));
                    })
                    .then(data => {
                        console.log('Delete response:', data);
                        if (data.success) {
                            showAlertModal('success', 'Deleted Successfully', 'The submission has been deleted successfully.', function() {
                                setTimeout(() => {
                                    location.reload();
                                }, 500);
                            });
                        } else {
                            showAlertModal('error', 'Delete Failed', data.message || 'Failed to delete submission');
                        }
                    })
                    .catch(error => {
                        console.error('Delete error:', error);
                        showAlertModal('error', 'Error', error.message || 'An error occurred while deleting the submission.');
                    });
                } catch (error) {
                    console.error('Exception:', error);
                    showAlertModal('error', 'Error', 'An error occurred: ' + error.message);
                }
            }
        }
        
        function openConfirmationModal(title, message, subMessage, type, confirmText, callback) {
            const modal = document.getElementById('confirmationModal');
            const modalHeader = document.getElementById('modalHeader');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalSubMessage = document.getElementById('modalSubMessage');
            const confirmButton = document.getElementById('confirmButton');
            const confirmButtonText = document.getElementById('confirmButtonText');
            
            // Set content
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            modalSubMessage.textContent = subMessage;
            confirmButtonText.textContent = confirmText;
            
            // Set styling based on type
            if (type === 'danger') {
                modalHeader.className = 'bg-red-50 border-b border-red-200 px-6 py-4 flex items-center gap-3';
                modalHeader.querySelector('div').className = 'bg-red-100 rounded-full w-12 h-12 flex items-center justify-center';
                modalHeader.querySelector('i').className = 'fas fa-exclamation-triangle text-red-600 text-xl';
                confirmButton.className = 'px-4 py-2 rounded-lg font-semibold text-white bg-red-600 hover:bg-red-700 transition flex items-center gap-2 text-sm';
            } else {
                modalHeader.className = 'bg-yellow-50 border-b border-yellow-200 px-6 py-4 flex items-center gap-3';
                modalHeader.querySelector('div').className = 'bg-yellow-100 rounded-full w-12 h-12 flex items-center justify-center';
                modalHeader.querySelector('i').className = 'fas fa-exclamation-triangle text-yellow-600 text-xl';
                confirmButton.className = 'px-4 py-2 rounded-lg font-semibold text-white bg-yellow-600 hover:bg-yellow-700 transition flex items-center gap-2 text-sm';
            }
            
            // Store callback
            pendingAction = callback;
            
            // Show modal
            modal.classList.remove('hidden');
        }
        
        function closeConfirmationModal() {
            document.getElementById('confirmationModal').classList.add('hidden');
            pendingAction = null;
        }
        
        function confirmAction() {
            if (pendingAction) {
                pendingAction();
            }
            closeConfirmationModal();
        }
        
        // Close modal on outside click
        document.getElementById('confirmationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmationModal();
            }
        });
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeConfirmationModal();
                closeAlertModal();
            }
        });
        
        // Move header up
        function moveHeaderUp(headerId) {
            const header = document.getElementById(`header-${headerId}`);
            if (header && header.previousElementSibling) {
                header.parentNode.insertBefore(header, header.previousElementSibling);
            }
        }
        
        // Move header down
        function moveHeaderDown(headerId) {
            const header = document.getElementById(`header-${headerId}`);
            if (header && header.nextElementSibling) {
                header.parentNode.insertBefore(header.nextElementSibling, header);
            }
        }
        
        // Move row up
        function moveRowUp(rowId) {
            const row = document.getElementById(rowId);
            if (row && row.previousElementSibling) {
                row.parentNode.insertBefore(row, row.previousElementSibling);
            }
        }
        
        // Move row down
        function moveRowDown(rowId) {
            const row = document.getElementById(rowId);
            if (row && row.nextElementSibling) {
                row.parentNode.insertBefore(row.nextElementSibling, row);
            }
        }
        
        // View template (placeholder for future implementation)
        function viewTemplate(templateId) {
            fetch(`{{ url('faculty/ipcr/templates') }}/${templateId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const template = data.template;
                        
                        // Check if template has table_body_html
                        if (template.table_body_html) {
                            const tableBody = document.getElementById('templatePreviewTableBody');
                            if (tableBody) {
                                tableBody.innerHTML = template.table_body_html;
                            }

                            // Load title
                            const titleElement = document.getElementById('templatePreviewTitle');
                            if (titleElement && template.title) {
                                titleElement.textContent = template.title;
                            }

                            // Load school year and semester
                            if (template.school_year) {
                                const displaySchoolYear = document.getElementById('templatePreviewSchoolYear');
                                if (displaySchoolYear) displaySchoolYear.textContent = template.school_year;
                            }
                            if (template.semester) {
                                const displaySemester = document.getElementById('templatePreviewSemester');
                                if (displaySemester) displaySemester.textContent = template.semester;
                            }

                            // Store template ID for save copy functionality
                            const templateIdField = document.getElementById('currentPreviewTemplateId');
                            if (templateIdField) {
                                templateIdField.value = templateId;
                            }

                            document.getElementById('templatePreviewModal').classList.remove('hidden');
                        } else {
                            showAlertModal('info', 'Legacy Template', 'This template was created with the old format. Please use the Edit button to modify it.');
                        }
                    } else {
                        showAlertModal('error', 'Not Found', 'Template could not be found.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlertModal('error', 'Error', 'An error occurred while loading the template.');
                });
        }
        
        function viewSubmission(submissionId) {
            console.log('Viewing/Editing submission:', submissionId);
            // Add cache-busting parameter
            fetch(`{{ url('faculty/ipcr/submissions') }}/${submissionId}?t=${Date.now()}`, {
                headers: {
                    'Cache-Control': 'no-cache'
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log('View submission response:', data);
                    if (data.success) {
                        const submission = data.submission;
                        
                        // Store submission ID for update functionality
                        const submissionIdField = document.getElementById('currentSubmissionIdToUpdate');
                        if (submissionIdField) {
                            submissionIdField.value = submissionId;
                            console.log('Submission ID stored in field:', submissionIdField.value);
                        }
                        
                        // Load table body
                        const tableBody = document.getElementById('templatePreviewTableBody');
                        if (tableBody && submission.table_body_html) {
                            tableBody.innerHTML = submission.table_body_html;
                            
                            // Make all table cells NON-editable, but enable inputs/textareas inside
                            const cells = tableBody.querySelectorAll('td');
                            console.log('Total cells found:', cells.length);
                            
                            cells.forEach((cell) => {
                                // Make cells non-editable
                                cell.setAttribute('contenteditable', 'false');
                                cell.style.cursor = 'default';
                                cell.style.userSelect = 'none';
                                cell.style.backgroundColor = '#f0f9ff';
                                cell.classList.add('hover:bg-blue-50');
                                
                                // Make inputs and textareas inside cells fully editable
                                const inputs = cell.querySelectorAll('input');
                                const textareas = cell.querySelectorAll('textarea');
                                
                                inputs.forEach(input => {
                                    input.setAttribute('contenteditable', 'true');
                                    input.removeAttribute('readonly');
                                    input.removeAttribute('disabled');
                                    input.style.pointerEvents = 'auto';
                                    input.style.backgroundColor = 'white';
                                    input.style.cursor = 'text';
                                    input.style.userSelect = 'text';
                                });
                                
                                textareas.forEach(textarea => {
                                    textarea.setAttribute('contenteditable', 'true');
                                    textarea.removeAttribute('readonly');
                                    textarea.removeAttribute('disabled');
                                    textarea.style.pointerEvents = 'auto';
                                    textarea.style.backgroundColor = 'white';
                                    textarea.style.cursor = 'text';
                                    textarea.style.userSelect = 'text';
                                });
                            });
                        }

                        // Load title
                        const titleElement = document.getElementById('templatePreviewTitle');
                        if (titleElement && submission.title) {
                            titleElement.textContent = submission.title;
                        }

                        // Load school year and semester
                        if (submission.school_year) {
                            const displaySchoolYear = document.getElementById('templatePreviewSchoolYear');
                            if (displaySchoolYear) displaySchoolYear.textContent = submission.school_year;
                        }
                        if (submission.semester) {
                            const displaySemester = document.getElementById('templatePreviewSemester');
                            if (displaySemester) displaySemester.textContent = submission.semester;
                        }

                        // Unhide QETA, Remarks, and Ratings columns
                        const previewModal = document.getElementById('templatePreviewModal');
                        if (previewModal) {
                            const headers = previewModal.querySelectorAll('thead th.hidden');
                            headers.forEach(header => header.classList.remove('hidden'));
                            
                            const cells = previewModal.querySelectorAll('td.hidden');
                            cells.forEach(cell => cell.classList.remove('hidden'));
                        }

                        // Hide Edit IPCR button and show Update Submission button
                        const saveCopyBtn = document.getElementById('saveCopyBtn');
                        if (saveCopyBtn) {
                            saveCopyBtn.style.display = 'none';
                            saveCopyBtn.classList.add('hidden');
                        }
                        
                        const updateBtn = document.getElementById('updateSubmissionBtn');
                        if (updateBtn) {
                            updateBtn.classList.remove('hidden');
                            updateBtn.style.display = 'flex';
                            console.log('Update button shown');
                        }

                        document.getElementById('templatePreviewModal').classList.remove('hidden');
                    } else {
                        showAlertModal('error', 'Not Found', 'Submission could not be found.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlertModal('error', 'Error', 'An error occurred while loading the submission.');
                });
        }

        function updateSubmission(submissionId) {
            console.log('=== updateSubmission (Edit) called with ID:', submissionId, '===');
            
            fetch(`{{ url('faculty/ipcr/submissions') }}/${submissionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Submission data loaded:', data.submission);
                        const submission = data.submission;
                        
                        // Store submission ID for update functionality
                        const submissionIdField = document.getElementById('currentSubmissionIdToUpdate');
                        if (submissionIdField) {
                            submissionIdField.value = submissionId;
                            console.log('Submission ID stored in field:', submissionIdField.value);
                        }

                        // Load table body
                        const tableBody = document.getElementById('templatePreviewTableBody');
                        if (tableBody && submission.table_body_html) {
                            tableBody.innerHTML = submission.table_body_html;
                            console.log('Table loaded, HTML length:', tableBody.innerHTML.length);
                            
                            // Make all table cells editable
                            const cells = tableBody.querySelectorAll('td');
                            console.log('Total cells found:', cells.length);
                            
                            cells.forEach((cell, index) => {
                                cell.setAttribute('contenteditable', 'true');
                                cell.style.cursor = 'text';
                                cell.style.backgroundColor = '#f0f9ff';
                                cell.classList.add('hover:bg-blue-50');
                                
                                if (index < 3) {
                                    console.log(`Cell ${index} set to editable:`, cell.innerHTML.substring(0, 50));
                                }
                            });
                        }

                        // Load title with (Edit) suffix
                        const titleElement = document.getElementById('templatePreviewTitle');
                        if (titleElement && submission.title) {
                            titleElement.textContent = submission.title + ' (Edit)';
                        }

                        // Load school year and semester
                        if (submission.school_year) {
                            const displaySchoolYear = document.getElementById('templatePreviewSchoolYear');
                            if (displaySchoolYear) displaySchoolYear.textContent = submission.school_year;
                        }
                        if (submission.semester) {
                            const displaySemester = document.getElementById('templatePreviewSemester');
                            if (displaySemester) displaySemester.textContent = submission.semester;
                        }

                        // Unhide QETA, Remarks, and Ratings columns
                        const previewModal = document.getElementById('templatePreviewModal');
                        if (previewModal) {
                            const headers = previewModal.querySelectorAll('thead th.hidden');
                            headers.forEach(header => header.classList.remove('hidden'));
                            
                            const cells = previewModal.querySelectorAll('td.hidden');
                            cells.forEach(cell => cell.classList.remove('hidden'));
                        }

                        // Hide save copy button and show update button
                        const saveCopyBtn = document.getElementById('saveCopyBtn');
                        if (saveCopyBtn) {
                            saveCopyBtn.style.display = 'none';
                            saveCopyBtn.classList.add('hidden');
                        }
                        
                        const updateBtn = document.getElementById('updateSubmissionBtn');
                        if (updateBtn) {
                            updateBtn.classList.remove('hidden');
                            updateBtn.style.display = 'flex';
                            console.log('Update button shown, display:', window.getComputedStyle(updateBtn).display);
                        }

                        document.getElementById('templatePreviewModal').classList.remove('hidden');
                    } else {
                        showAlertModal('error', 'Not Found', 'Submission could not be found.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlertModal('error', 'Error', 'An error occurred while loading the submission.');
                });
        }
        
        // Alert Modal Functions
        let alertModalCallback = null;
        let alertModalTimer = null;
        
        function showAlertModal(type, title, message, callback) {
            const modal = document.getElementById('alertModal');
            const modalHeader = document.getElementById('alertModalHeader');
            const iconContainer = document.getElementById('alertModalIconContainer');
            const icon = document.getElementById('alertModalIcon');
            const modalTitle = document.getElementById('alertModalTitle');
            const modalMessage = document.getElementById('alertModalMessage');
            
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            alertModalCallback = callback || null;
            
            // Clear any existing timer
            if (alertModalTimer) {
                clearTimeout(alertModalTimer);
                alertModalTimer = null;
            }
            
            // Set styling based on type
            if (type === 'success') {
                modalHeader.className = 'bg-green-50 border-b border-green-200 px-6 py-4 flex items-center gap-3';
                iconContainer.className = 'bg-green-100 rounded-full p-3';
                icon.className = 'fas fa-check-circle text-green-600 text-xl';
            } else if (type === 'error') {
                modalHeader.className = 'bg-red-50 border-b border-red-200 px-6 py-4 flex items-center gap-3';
                iconContainer.className = 'bg-red-100 rounded-full p-3';
                icon.className = 'fas fa-exclamation-circle text-red-600 text-xl';
            } else if (type === 'warning') {
                modalHeader.className = 'bg-yellow-50 border-b border-yellow-200 px-6 py-4 flex items-center gap-3';
                iconContainer.className = 'bg-yellow-100 rounded-full p-3';
                icon.className = 'fas fa-exclamation-triangle text-yellow-600 text-xl';
            } else {
                modalHeader.className = 'bg-blue-50 border-b border-blue-200 px-6 py-4 flex items-center gap-3';
                iconContainer.className = 'bg-blue-100 rounded-full p-3';
                icon.className = 'fas fa-info-circle text-blue-600 text-xl';
            }
            
            modal.classList.remove('hidden');
            
            // Auto-dismiss after 5 seconds
            alertModalTimer = setTimeout(() => {
                closeAlertModal();
            }, 5000);
        }
        
        function closeAlertModal() {
            if (alertModalTimer) {
                clearTimeout(alertModalTimer);
                alertModalTimer = null;
            }
            document.getElementById('alertModal').classList.add('hidden');
            if (alertModalCallback) {
                alertModalCallback();
                alertModalCallback = null;
            }
        }
        
        // Close alert modal on outside click
        document.getElementById('alertModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAlertModal();
            }
        });
    </script>

</body>
</html>
