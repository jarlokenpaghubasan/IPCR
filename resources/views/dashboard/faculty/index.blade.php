<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard - IPCR/OPCR Module</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/urs_logo.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/dashboard_faculty_index.css', 'resources/js/dashboard_faculty_index.js'])
</head>
<body class="bg-gray-50" style="visibility: hidden;">
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
                    <a href="{{ route('faculty.dashboard') }}" class="text-blue-600 font-semibold hover:text-blue-700">Dashboard</a>
                    <a href="{{ route('faculty.my-ipcrs') }}" class="text-gray-600 hover:text-gray-900">My IPCRs</a>
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
                        <button onclick="toggleNotificationPopup()" class="text-gray-600 hover:text-gray-900 relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="notification-badge">3</span>
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
                <a href="{{ route('faculty.dashboard') }}" class="block text-blue-600 font-semibold hover:text-blue-700 py-2">Dashboard</a>
                <a href="{{ route('faculty.my-ipcrs') }}" class="block text-gray-600 hover:text-gray-900 py-2">My IPCRs</a>
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
        @php
            // Variables passed from controller:
            // $strategicObjectivesText, $strategicObjectivesPercent
            // $coreFunctionsText, $coreFunctionsPercent
            // $supportFunctionsText, $supportFunctionsPercent
            // $ipcrAccomplishedText, $ipcrPercentageText, $ipcrPercentageValue

            // Expected Targets (can be fetched from database in the future)
            $expectedTargets = [
                ['percentage' => 0, 'title' => 'SO I. PROMOTING ACCESS TO QUALITY EDUCATION'],
                ['percentage' => 0, 'title' => 'SO II. PRODUCING COMPETENT AND COMPETITIVE GRADUATES'],
                ['percentage' => 0, 'title' => 'SO III. ENHANCING STUDENT DEVELOPMENT SERVICES'],
                ['percentage' => 0, 'title' => 'SO IV. ENHANCING COMPETENCIES AND PROFESSIONALISM OF FACULTY AND STAFF'],
                ['percentage' => 0, 'title' => 'SO VI. IMPROVING THE QUALITY, RELEVANCE AND RESPONSIVENESS'],
            ];
        @endphp
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Left Main Content (2/3 width) -->
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                <!-- Welcome Section -->
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Welcome, {{ explode(' ', auth()->user()->name)[0] }}!</h2>
                    <p class="text-sm sm:text-base text-gray-500 mt-1">Here's a Summary of your performance and upcoming task</p>
                </div>

                <!-- Metrics Cards - Horizontal Scroll on Mobile -->
                <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0 pb-2">
                    <div class="flex sm:grid sm:grid-cols-3 gap-4 min-w-max sm:min-w-0">
                        <!-- Strategic Objectives Card -->
                        <div class="compact-metric sm:metric-card flex-shrink-0 w-64 sm:w-auto">
                            <div class="flex items-center justify-between mb-3 sm:mb-4">
                                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <span class="text-xs font-semibold px-2.5 py-1 bg-blue-50 text-blue-600 rounded-full">{{ $strategicObjectivesPercent }}</span>
                            </div>
                            <div>
                                <h4 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">{{ $strategicObjectivesText ?? 'N/A' }}</h4>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 mt-1">Strategic Objectives</p>
                            </div>
                        </div>

                        <!-- Core Functions Card -->
                        <div class="compact-metric sm:metric-card flex-shrink-0 w-64 sm:w-auto">
                            <div class="flex items-center justify-between mb-3 sm:mb-4">
                                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <span class="text-xs font-semibold px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-full">{{ $coreFunctionsPercent }}</span>
                            </div>
                            <div>
                                <h4 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">{{ $coreFunctionsText ?? 'N/A' }}</h4>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 mt-1">Core Functions</p>
                            </div>
                        </div>

                        <!-- Support Functions Card -->
                        <div class="compact-metric sm:metric-card flex-shrink-0 w-64 sm:w-auto">
                            <div class="flex items-center justify-between mb-3 sm:mb-4">
                                <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <span class="text-xs font-semibold px-2.5 py-1 bg-purple-50 text-purple-600 rounded-full">{{ $supportFunctionsPercent }}</span>
                            </div>
                            <div>
                                <h4 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">{{ $supportFunctionsText ?? 'N/A' }}</h4>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 mt-1">Support Functions</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- IPCR Progress Bar -->
                @php
                    // Parse metric card values to calculate total progress
                    $parseMetric = function($text) {
                        if (preg_match('/(\d+)\/(\d+)/', $text, $matches)) {
                            return ['accomplished' => (int)$matches[1], 'total' => (int)$matches[2]];
                        }
                        return ['accomplished' => 0, 'total' => 0];
                    };
                    
                    $strategic = $parseMetric($strategicObjectivesText);
                    $core = $parseMetric($coreFunctionsText);
                    $support = $parseMetric($supportFunctionsText);
                    
                    $totalAccomplished = $strategic['accomplished'] + $core['accomplished'] + $support['accomplished'];
                    $totalGoals = $strategic['total'] + $core['total'] + $support['total'];
                    
                    $calculatedPercentage = $totalGoals > 0 ? round(($totalAccomplished / $totalGoals) * 100, 1) : 0;
                @endphp
                <div class="metric-card">
                    <div class="flex items-center justify-between mb-5 sm:mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 leading-tight">IPCR Completion</h3>
                                <p class="text-xs text-gray-500 font-medium">Semester Progress</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl sm:text-3xl font-bold text-indigo-600 tracking-tight">{{ $calculatedPercentage }}%</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill" style="width: {{ $calculatedPercentage }}%;"></div>
                        </div>
                        <div class="flex justify-between items-center text-xs sm:text-sm font-medium">
                            <span class="text-gray-500">Accomplished</span>
                            <span class="text-gray-900 bg-gray-100 px-2.5 py-0.5 rounded-md"><span class="text-indigo-600 font-bold">{{ $totalAccomplished }}</span> of {{ $totalGoals }} Goals</span>
                        </div>
                    </div>
                </div>

                <!-- Performance Overview Section -->
                <div class="metric-card">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                        <!-- Chart Area -->
                        <div class="order-2 md:order-1">
                            <div class="flex items-center space-x-3 mb-4 sm:mb-5">
                                <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 leading-tight">Performance Overview</h3>
                            </div>
                            <div class="relative bg-gray-50/50 rounded-xl p-2 sm:p-4 border border-gray-100" style="height: 250px;">
                                <canvas id="performanceChart"></canvas>
                            </div>
                        </div>

                        <!-- Expected Target Area -->
                        <div class="order-1 md:order-2">
                            <div class="flex items-center space-x-3 mb-4 sm:mb-5">
                                <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 leading-tight">Expected Target</h3>
                            </div>
                            <div class="space-y-3">
                                @foreach($expectedTargets as $target)
                                    <div class="bg-gray-50 hover:bg-gray-100 transition-colors rounded-xl p-3 border border-gray-100 flex items-start space-x-3 group cursor-default">
                                        <div class="mt-0.5">
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-lg bg-indigo-50 text-indigo-700 w-12 text-center">{{ $target['percentage'] }}%</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs sm:text-sm text-gray-700 font-medium leading-relaxed group-hover:text-gray-900 transition-colors">{{ $target['title'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar (1/3 width) -->
            <div class="space-y-4 sm:space-y-6">
                <!-- Upcoming Deadlines -->
                <div class="metric-card">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-5">
                        <div class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 leading-tight">Upcoming Deadlines</h3>
                    </div>
                    <div class="space-y-3">
                        <!-- Deadline Item 1 -->
                        <div class="flex items-start space-x-3 sm:space-x-4 p-3 bg-white hover:bg-rose-50/50 border border-gray-100 hover:border-rose-100 rounded-xl transition-all cursor-default">
                            <div class="deadline-badge">
                                <span class="text-[10px] sm:text-xs uppercase tracking-wider font-semibold opacity-80">Jul</span>
                                <span class="text-lg sm:text-xl font-black leading-none mt-0.5">15</span>
                            </div>
                            <div class="flex-1 min-w-0 pt-0.5">
                                <p class="text-sm font-bold text-gray-900">Submission Deadline</p>
                                <p class="text-xs text-gray-500 mt-0.5">Jan - June 2025 Period</p>
                            </div>
                        </div>

                        <!-- Deadline Item 2 -->
                        <div class="flex items-start space-x-3 sm:space-x-4 p-3 bg-white hover:bg-rose-50/50 border border-gray-100 hover:border-rose-100 rounded-xl transition-all cursor-default">
                            <div class="deadline-badge">
                                <span class="text-[10px] sm:text-xs uppercase tracking-wider font-semibold opacity-80">Jul</span>
                                <span class="text-lg sm:text-xl font-black leading-none mt-0.5">31</span>
                            </div>
                            <div class="flex-1 min-w-0 pt-0.5">
                                <p class="text-sm font-bold text-gray-900">Review Period Ends</p>
                                <p class="text-xs text-gray-500 mt-0.5">All submissions must be reviewed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="metric-card hidden lg:block">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-5">
                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 leading-tight">Notifications</h3>
                    </div>
                    <div class="space-y-2 sm:space-y-3">
                        <!-- Notification 1 -->
                        <div class="notification-item notification-blue">
                            <div class="flex items-start space-x-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs sm:text-sm font-semibold text-gray-900">Your IPCR has been Rated</p>
                                    <p class="text-xs text-gray-600">By PCHS Dean</p>
                                </div>
                            </div>
                        </div>

                        <!-- Notification 2 -->
                        <div class="notification-item notification-yellow">
                            <div class="flex items-start space-x-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs sm:text-sm font-semibold text-gray-900">Reminder: 5 days left to submit.</p>
                                    <p class="text-xs text-gray-600">Submit your Jan - Jun 2024 Review before the deadline</p>
                                </div>
                            </div>
                        </div>

                        <!-- Notification 3 -->
                        <div class="notification-item notification-gray">
                            <div class="flex items-start space-x-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs sm:text-sm font-semibold text-gray-900">System maintenance scheduled</p>
                                    <p class="text-xs text-gray-600">The system will be down on July 25th from 2-4 AM.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Returned IPCR -->
                <div class="metric-card">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-5">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 leading-tight">Returned IPCR</h3>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 sm:p-4 border border-gray-100 hover:border-gray-300 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900">IPCR</p>
                                <p class="text-xs text-gray-500 font-medium">2025 - 2026 Semester 1</p>
                            </div>
                            <button class="bg-white border border-gray-200 text-gray-700 hover:text-blue-700 hover:border-blue-200 hover:bg-blue-50 text-xs sm:text-sm font-bold px-3 py-1.5 rounded-lg flex-shrink-0 transition-colors shadow-sm">View</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>document.body.style.visibility = 'visible';</script>
</body>
</html>