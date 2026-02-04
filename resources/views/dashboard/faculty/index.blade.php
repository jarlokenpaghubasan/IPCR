<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard - IPCR/OPCR Module</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/dashboard_faculty_index.css', 'resources/js/dashboard_faculty_index.js'])
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Left Main Content (2/3 width) -->
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                <!-- Welcome Section -->
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Welcome, {{ explode(' ', auth()->user()->name)[0] }}!</h2>
                    <p class="text-sm sm:text-base text-gray-500 mt-1">Here's a Summary of your performance and upcoming task</p>
                </div>

                <!-- Metrics Cards - Horizontal Scroll on Mobile -->
                <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0">
                    <div class="flex sm:grid sm:grid-cols-3 gap-3 sm:gap-4 min-w-max sm:min-w-0">
                        <!-- Strategic Objectives Card -->
                        <div class="compact-metric sm:metric-card flex-shrink-0 w-64 sm:w-auto">
                            <div class="sm:block">
                                <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-3 whitespace-nowrap">Strategic Objectives</p>
                                <div class="flex items-center sm:items-end justify-between gap-2">
                                    <span class="text-2xl sm:text-4xl font-bold text-gray-900">2/13</span>
                                    <div class="text-xl sm:text-2xl font-bold text-red-500">15%</div>
                                </div>
                            </div>
                        </div>

                        <!-- Core Functions Card -->
                        <div class="compact-metric sm:metric-card flex-shrink-0 w-64 sm:w-auto">
                            <div class="sm:block">
                                <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-3 whitespace-nowrap">Core Functions</p>
                                <div class="flex items-center sm:items-end justify-between gap-2">
                                    <span class="text-2xl sm:text-4xl font-bold text-gray-900">1/12</span>
                                    <div class="text-xl sm:text-2xl font-bold text-red-500">8%</div>
                                </div>
                            </div>
                        </div>

                        <!-- Support Functions Card -->
                        <div class="compact-metric sm:metric-card flex-shrink-0 w-64 sm:w-auto">
                            <div class="sm:block">
                                <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-3 whitespace-nowrap">Support Functions</p>
                                <div class="flex items-center sm:items-end justify-between gap-2">
                                    <span class="text-2xl sm:text-4xl font-bold text-gray-900">1/4</span>
                                    <div class="text-xl sm:text-2xl font-bold text-orange-500">25%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- IPCR Progress Bar -->
                <div class="metric-card">
                    <div class="mb-3 sm:mb-4">
                        <h3 class="text-base sm:text-lg font-bold text-gray-900">IPCR Progress Bar</h3>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs sm:text-sm text-gray-600">Accomplished Goal</span>
                            <span class="text-xs sm:text-sm font-semibold text-gray-900">100%</span>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill" style="width: 13%;"></div>
                        </div>
                        <div class="mt-2">
                            <span class="text-xs sm:text-sm font-bold text-indigo-600">13%</span>
                        </div>
                    </div>
                </div>

                <!-- Performance Overview Section -->
                <div class="metric-card">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Chart Area -->
                        <div class="order-2 md:order-1">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Performance Overview</h3>
                            <div class="relative" style="height: 250px;">
                                <canvas id="performanceChart"></canvas>
                            </div>
                        </div>

                        <!-- Expected Target Area -->
                        <div class="order-1 md:order-2">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Expected Target</h3>
                            <div class="space-y-2 sm:space-y-3">
                                <div class="flex items-start space-x-2">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full mt-1.5 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-xs sm:text-sm"><span class="font-bold text-purple-600">90%</span> - SO I. PROMOTING ACCESS TO QUALITY EDUCATION</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full mt-1.5 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-xs sm:text-sm"><span class="font-bold text-purple-600">85%</span> - SO II. PRODUCING COMPETENT AND COMPETITIVE GRADUATES</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full mt-1.5 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-xs sm:text-sm"><span class="font-bold text-purple-600">90%</span> - SO III. ENHANCING STUDENT DEVELOPMENT SERVICES</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full mt-1.5 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-xs sm:text-sm"><span class="font-bold text-purple-600">93%</span> - SO IV. ENHANCING COMPETENCIES AND PROFESSIONALISM OF FACULTY AND STAFF</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full mt-1.5 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-xs sm:text-sm"><span class="font-bold text-purple-600">85%</span> - SO VI. IMPROVING THE QUALITY, RELEVANCE AND RESPONSIVENESS</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar (1/3 width) -->
            <div class="space-y-4 sm:space-y-6">
                <!-- Upcoming Deadlines -->
                <div class="metric-card">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Upcoming Deadlines</h3>
                    <div class="space-y-3 sm:space-y-4">
                        <!-- Deadline Item 1 -->
                        <div class="flex items-start space-x-2 sm:space-x-3">
                            <div class="deadline-badge bg-orange-100 text-orange-600 flex-shrink-0">
                                <div class="text-xs">JUL</div>
                                <div class="text-base sm:text-lg font-bold">15</div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-semibold text-gray-900">Submission Deadline</p>
                                <p class="text-xs text-gray-500">Jan - June 2025 Period</p>
                            </div>
                        </div>

                        <!-- Deadline Item 2 -->
                        <div class="flex items-start space-x-2 sm:space-x-3">
                            <div class="deadline-badge bg-orange-100 text-orange-600 flex-shrink-0">
                                <div class="text-xs">JUL</div>
                                <div class="text-base sm:text-lg font-bold">31</div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-semibold text-gray-900">Review Period Ends</p>
                                <p class="text-xs text-gray-500">All submissions must be reviewed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="metric-card hidden lg:block">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Notifications</h3>
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
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Returned IPCR</h3>
                    <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-semibold text-gray-900">IPCR</p>
                                <p class="text-xs text-gray-600">2025 - 2026 Semester 1</p>
                            </div>
                            <button class="text-blue-600 hover:text-blue-700 text-xs sm:text-sm font-semibold flex-shrink-0">View</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>