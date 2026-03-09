<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard - IPCR/OPCR Module</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/urs_logo.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.soPerformanceData = @json($soPerformanceData ?? []);
    </script>
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
                            @if(($notifications ?? collect())->count() > 0)
                                <span class="notification-badge">{{ $notifications->count() }}</span>
                            @endif
                        </button>
                        
                        <!-- Notification Popup -->
                        <div id="notificationPopup" class="notification-popup">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-base font-bold text-gray-900">Notifications</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <div class="p-3">
                                    @forelse(($notifications ?? collect()) as $notif)
                                        @php
                                            $mobileNotifStyles = [
                                                'info' => 'notification-blue',
                                                'warning' => 'notification-yellow',
                                                'success' => 'notification-blue',
                                                'danger' => 'notification-yellow',
                                            ];
                                            $mobileIconColors = [
                                                'info' => 'text-blue-500',
                                                'warning' => 'text-yellow-600',
                                                'success' => 'text-green-500',
                                                'danger' => 'text-red-500',
                                            ];
                                        @endphp
                                        <div class="notification-item {{ $mobileNotifStyles[$notif->type] ?? 'notification-gray' }} mb-2">
                                            <div class="flex items-start space-x-2">
                                                <svg class="w-4 h-4 {{ $mobileIconColors[$notif->type] ?? 'text-gray-600' }} mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    @if($notif->type === 'warning' || $notif->type === 'danger')
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    @else
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    @endif
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs font-semibold text-gray-900">{{ $notif->title }}</p>
                                                    <p class="text-xs text-gray-600">{{ Str::limit($notif->message, 80) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="notification-item notification-gray">
                                            <div class="flex items-start space-x-2">
                                                <svg class="w-4 h-4 text-gray-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs font-semibold text-gray-900">No notifications</p>
                                                    <p class="text-xs text-gray-600">You're all caught up!</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
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
            // $soPerformanceData — array of ['label' => 'SO I', 'name' => '...', 'average' => 3.5]
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
                    <div class="flex flex-col gap-6 lg:gap-8">
                        <!-- Chart Area -->
                        <div>
                            <div class="flex items-center justify-between mb-4 sm:mb-5">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                    </div>
                                    <h3 class="text-base sm:text-lg font-bold text-gray-900 leading-tight">Performance Overview</h3>
                                </div>
                            </div>

                            <!-- Section Filter Buttons -->
                            @php
                                $sections = collect($soPerformanceData ?? [])->pluck('section')->unique()->filter()->values()->toArray();
                                $sectionLabels = [
                                    'strategic_objectives' => 'Strategic Objectives',
                                    'core_functions' => 'Core Functions',
                                    'support_functions' => 'Support Functions',
                                ];
                            @endphp
                            @if(count($sections) > 0)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <button type="button" onclick="filterSection('all')" id="filter-all" class="section-filter-btn active px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors">All</button>
                                    @foreach($sections as $sec)
                                        <button type="button" onclick="filterSection('{{ $sec }}')" id="filter-{{ $sec }}" class="section-filter-btn px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors">{{ $sectionLabels[$sec] ?? ucfirst(str_replace('_', ' ', $sec)) }}</button>
                                    @endforeach
                                </div>
                            @endif

                            <div class="relative bg-gray-50/50 rounded-xl p-2 sm:p-4 border border-gray-100" style="height: 350px;">
                                <canvas id="performanceChart"></canvas>
                            </div>
                        </div>

                        <!-- Expected Target Area -->
                        <div>
                            <div class="flex items-center space-x-3 mb-4 sm:mb-5">
                                <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 leading-tight">Expected Target</h3>
                            </div>
                            <div id="expectedTargetList" class="space-y-4">
                                @php
                                    $grouped = collect($soPerformanceData ?? [])->groupBy('section');
                                    $sectionColors = [
                                        'strategic_objectives' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'text' => 'text-green-700', 'dot' => 'bg-green-500'],
                                        'core_functions' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'text' => 'text-purple-700', 'dot' => 'bg-purple-500'],
                                        'support_functions' => ['bg' => 'bg-orange-50', 'border' => 'border-orange-200', 'text' => 'text-orange-700', 'dot' => 'bg-orange-500'],
                                    ];
                                @endphp
                                @forelse($grouped as $sectionKey => $items)
                                    @php $colors = $sectionColors[$sectionKey] ?? ['bg' => 'bg-gray-50', 'border' => 'border-gray-200', 'text' => 'text-gray-700', 'dot' => 'bg-gray-500']; @endphp
                                    <div class="section-group" data-section="{{ $sectionKey }}">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="w-2 h-2 rounded-full {{ $colors['dot'] }}"></span>
                                            <h4 class="text-xs font-bold {{ $colors['text'] }} uppercase tracking-wider">{{ $sectionLabels[$sectionKey] ?? ucfirst(str_replace('_', ' ', $sectionKey)) }}</h4>
                                        </div>
                                        <div class="space-y-2">
                                            @foreach($items as $so)
                                                @php
                                                    $globalIndex = 0;
                                                    foreach ($soPerformanceData as $idx => $entry) {
                                                        if ($entry['label'] === $so['label'] && $entry['section'] === $so['section']) {
                                                            $globalIndex = $idx;
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                <div onclick="openSoModal({{ $globalIndex !== false ? $globalIndex : 0 }})"
                                                     class="bg-gray-50 hover:bg-indigo-50 transition-colors rounded-xl p-3 border border-gray-100 hover:border-indigo-200 flex items-start space-x-3 group cursor-pointer">
                                                    <div class="mt-0.5">
                                                        <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-lg {{ $so['average'] >= 4.5 ? 'bg-emerald-50 text-emerald-700' : ($so['average'] >= 3.0 ? 'bg-blue-50 text-blue-700' : ($so['average'] > 0 ? 'bg-amber-50 text-amber-700' : 'bg-indigo-50 text-indigo-700')) }} w-12 text-center">{{ number_format($so['average'], 2) }}</span>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-xs sm:text-sm text-gray-700 font-medium leading-relaxed group-hover:text-indigo-800 transition-colors">{{ $so['name'] }}</p>
                                                    </div>
                                                    <svg class="w-4 h-4 text-gray-300 group-hover:text-indigo-400 flex-shrink-0 mt-0.5 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-center">
                                        <p class="text-xs sm:text-sm text-gray-400 font-medium">No submitted IPCRs yet</p>
                                    </div>
                                @endforelse
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
                        @forelse(($deadlines ?? collect()) as $deadline)
                            @php
                                $daysLeft = max(0, (int) now()->startOfDay()->diffInDays($deadline->deadline_date, false));
                            @endphp
                            <div class="flex items-start space-x-3 sm:space-x-4 p-3 bg-white hover:bg-rose-50/50 border border-gray-100 hover:border-rose-100 rounded-xl transition-all cursor-default">
                                <div class="deadline-badge">
                                    <span class="text-[10px] sm:text-xs uppercase tracking-wider font-semibold opacity-80">{{ $deadline->deadline_date->format('M') }}</span>
                                    <span class="text-lg sm:text-xl font-black leading-none mt-0.5">{{ $deadline->deadline_date->format('d') }}</span>
                                </div>
                                <div class="flex-1 min-w-0 pt-0.5">
                                    <p class="text-sm font-bold text-gray-900">{{ $deadline->title }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $deadline->description ?? $daysLeft . ' days remaining' }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 text-center">
                                <p class="text-xs text-gray-400 font-medium">No upcoming deadlines</p>
                            </div>
                        @endforelse
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
                        @forelse(($notifications ?? collect()) as $notif)
                            @php
                                $sidebarNotifStyles = [
                                    'info' => 'notification-blue',
                                    'warning' => 'notification-yellow',
                                    'success' => 'notification-blue',
                                    'danger' => 'notification-yellow',
                                ];
                                $sidebarIconColors = [
                                    'info' => 'text-blue-500',
                                    'warning' => 'text-yellow-600',
                                    'success' => 'text-green-500',
                                    'danger' => 'text-red-500',
                                ];
                            @endphp
                            <div class="notification-item {{ $sidebarNotifStyles[$notif->type] ?? 'notification-gray' }}">
                                <div class="flex items-start space-x-2">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $sidebarIconColors[$notif->type] ?? 'text-gray-600' }} mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        @if($notif->type === 'warning' || $notif->type === 'danger')
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        @else
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        @endif
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs sm:text-sm font-semibold text-gray-900">{{ $notif->title }}</p>
                                        <p class="text-xs text-gray-600">{{ Str::limit($notif->message, 80) }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="notification-item notification-gray">
                                <div class="flex items-start space-x-2">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs sm:text-sm font-semibold text-gray-900">No notifications</p>
                                        <p class="text-xs text-gray-600">You're all caught up!</p>
                                    </div>
                                </div>
                            </div>
                        @endforelse
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

    <!-- SO Detail Modal -->
    <div id="soDetailModal" class="so-modal-overlay" onclick="closeSoModal(event)">
        <div class="so-modal-container" onclick="event.stopPropagation()">
            <!-- Header -->
            <div id="soModalHeader" class="so-modal-header">
                <div class="flex items-start justify-between w-full">
                    <div class="flex-1 min-w-0 pr-4">
                        <div class="flex items-center space-x-2 mb-1.5">
                            <span id="soModalSectionBadge" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 border border-gray-200"></span>
                        </div>
                        <h2 id="soModalTitle" class="text-base sm:text-lg font-bold leading-tight text-gray-900 mb-1 truncate"></h2>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-semibold text-gray-700">Average:</span>
                            <span id="soModalAvgValue" class="text-xs font-bold text-gray-900 bg-gray-100 px-1.5 py-0.5 rounded border border-gray-200"></span>
                        </div>
                    </div>
                    <button onclick="closeSoModal()" class="flex-shrink-0 w-8 h-8 rounded bg-gray-50 hover:bg-gray-200 flex items-center justify-center transition-colors border border-gray-200 text-gray-500 hover:text-gray-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="so-modal-body">
                <!-- Rows Table -->
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-full bg-indigo-100 flex items-center justify-center"><svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></span>
                        <span>Performance Rows</span>
                    </h3>
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="w-full text-xs border-collapse min-w-[600px]">
                            <thead class="bg-gray-50/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider w-1/4">MFO</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider w-1/4">Success Indicators</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider w-1/4">Actual Accomplishments</th>
                                    <th class="px-2 py-3 text-center text-[11px] font-bold text-gray-500 uppercase tracking-wider w-12">Q</th>
                                    <th class="px-2 py-3 text-center text-[11px] font-bold text-gray-500 uppercase tracking-wider w-12">E</th>
                                    <th class="px-2 py-3 text-center text-[11px] font-bold text-gray-500 uppercase tracking-wider w-12">T</th>
                                    <th class="px-3 py-3 text-center text-[11px] font-bold text-indigo-600 uppercase tracking-wider w-16 bg-indigo-50/50">Avg</th>
                                </tr>
                            </thead>
                            <tbody id="soModalTableBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Supporting Documents -->
                <div>
                    <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-full bg-purple-100 flex items-center justify-center"><svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg></span>
                        <span>Supporting Documents</span>
                        <span id="soModalDocCount" class="text-xs bg-purple-100 text-purple-700 font-bold px-2 py-0.5 rounded-full"></span>
                    </h3>
                    <div id="soModalDocsGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-3"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Viewer Modal -->
    <div id="imageViewerOverlay" class="fixed inset-0 bg-black/90 z-[1100] hidden items-center justify-center opacity-0 transition-opacity duration-300" onclick="closeImageViewer()">
        <button class="absolute top-4 right-4 text-white hover:text-gray-300 z-50 p-2" onclick="closeImageViewer()">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img id="imageViewerContent" class="max-w-[90vw] max-h-[90vh] object-contain transform scale-95 transition-transform duration-300 rounded-lg shadow-2xl" src="" alt="Expanded View" onclick="event.stopPropagation()">
    </div>

<script>document.body.style.visibility = 'visible';</script>
</body>
</html>