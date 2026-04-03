<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Summary Reports - IPCR Dashboard</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/urs_logo.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <img src="{{ asset('images/urs_logo.jpg') }}" alt="URS Logo" class="h-10 sm:h-12 w-auto object-contain flex-shrink-0">
                    <h1 class="text-base sm:text-xl font-bold text-gray-900">IPCR Dashboard</h1>
                </div>

                <div class="hidden lg:flex items-center space-x-6 xl:space-x-8">
                    <a href="{{ route('faculty.dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('faculty.my-ipcrs') }}" class="text-gray-600 hover:text-gray-900">My IPCRs</a>
                    <a href="{{ route('faculty.summary-reports') }}" class="text-blue-600 font-semibold hover:text-blue-700">Summary Reports</a>
                    <a href="{{ route('faculty.profile') }}" class="text-gray-600 hover:text-gray-900">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-700 font-semibold">Logout</button>
                    </form>
                </div>

                <div class="lg:hidden">
                    <button onclick="toggleMobileMenu()" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div id="mobileMenu" class="hidden lg:hidden pt-4 space-y-2">
                <a href="{{ route('faculty.dashboard') }}" class="block text-gray-600 hover:text-gray-900 py-2">Dashboard</a>
                <a href="{{ route('faculty.my-ipcrs') }}" class="block text-gray-600 hover:text-gray-900 py-2">My IPCRs</a>
                <a href="{{ route('faculty.summary-reports') }}" class="block text-blue-600 font-semibold hover:text-blue-700 py-2">Summary Reports</a>
                <a href="{{ route('faculty.profile') }}" class="block text-gray-600 hover:text-gray-900 py-2">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-700 font-semibold py-2">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        <div class="flex flex-col md:flex-row gap-6 lg:gap-8">
            <!-- Sidebar / Mobile Category Menu -->
            <div class="w-full md:w-56 lg:w-64 flex-shrink-0 md:sticky md:top-24 self-start">
                <div class="bg-white md:rounded-xl md:shadow-sm md:border border-gray-100 overflow-hidden h-full -mx-4 px-4 md:mx-0 md:px-0">
                    <div class="p-1 md:p-6 flex md:block space-x-2 md:space-x-0 md:space-y-4 overflow-x-auto hide-scrollbar items-center">
                        <h2 class="hidden md:block text-lg font-bold text-gray-900 mb-6 transition-all">Staff Category</h2>
                        
                        <a href="{{ route('faculty.summary-reports', ['category' => 'faculty', 'department' => $activeDepartment]) }}" class="shrink-0 block px-4 md:px-3 py-2 rounded-full md:rounded-lg text-sm transition-all md:border-l-4 {{ $activeCategory === 'faculty' ? 'bg-blue-600 md:bg-gray-50 text-white md:text-gray-900 font-bold border-blue-600 shadow-md md:shadow-none' : 'bg-white md:bg-transparent border border-gray-200 md:border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium hover:border-gray-300' }}">
                            Faculty
                        </a>
                        
                        <a href="{{ route('faculty.summary-reports', ['category' => 'staff', 'department' => 'all']) }}" class="shrink-0 block px-4 md:px-3 py-2 rounded-full md:rounded-lg text-sm transition-all md:border-l-4 {{ $activeCategory === 'staff' ? 'bg-blue-600 md:bg-gray-50 text-white md:text-gray-900 font-bold border-blue-600 shadow-md md:shadow-none' : 'bg-white md:bg-transparent border border-gray-200 md:border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium hover:border-gray-300' }}">
                            Staff
                        </a>
                        
                        <a href="#" class="shrink-0 block px-4 md:px-3 py-2 rounded-full md:rounded-lg text-sm transition-all md:border-l-4 border-gray-200 md:border-transparent bg-white md:bg-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium hover:border-gray-300">
                            Part time
                        </a>
                        
                        <a href="{{ route('faculty.summary-reports', ['category' => 'dean-director', 'department' => 'all']) }}" class="shrink-0 block px-4 md:px-3 py-2 rounded-full md:rounded-lg text-sm transition-all md:border-l-4 {{ $activeCategory === 'dean-director' ? 'bg-blue-600 md:bg-gray-50 text-white md:text-gray-900 font-bold border-blue-600 shadow-md md:shadow-none' : 'bg-white md:bg-transparent border border-gray-200 md:border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium hover:border-gray-300' }}">
                            Dean and Director
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 min-w-0">
                <!-- Tabs -->
                @if($activeCategory !== 'staff')
                <div class="mb-6">
                    <div class="flex space-x-2 overflow-x-auto pb-1">
                        @if($activeCategory === 'dean-director')
                        <a href="{{ route('faculty.summary-reports', ['category' => 'dean-director', 'department' => 'all']) }}"
                           class="px-5 py-2 rounded-full text-sm whitespace-nowrap shadow-sm transition-colors bg-blue-600 text-white font-semibold">
                            All Departments
                        </a>
                        @else
                        <a href="{{ route('faculty.summary-reports', ['category' => $activeCategory, 'department' => 'all']) }}"
                           class="px-5 py-2 rounded-full text-sm whitespace-nowrap shadow-sm transition-colors {{ $activeDepartment === 'all' ? 'bg-blue-600 text-white font-semibold' : 'bg-white border border-gray-200 text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900' }}">
                            All
                        </a>
                        @foreach($departments as $dept)
                        <a href="{{ route('faculty.summary-reports', ['category' => $activeCategory, 'department' => $dept->code]) }}"
                           class="px-5 py-2 rounded-full text-sm whitespace-nowrap shadow-sm transition-colors {{ $activeDepartment === $dept->code ? 'bg-blue-600 text-white font-semibold' : 'bg-white border border-gray-200 text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900' }}">
                            {{ $dept->code }}
                        </a>
                        @endforeach
                        @endif
                    </div>
                </div>
                @endif

                <!-- Tables Container -->
                @if($activeCategory === 'dean-director')
                <div class="space-y-6">
                    @if(session('success'))
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                    @endif

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 bg-white flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Summary of Performance Evaluation</h3>
                                <p class="text-xs text-gray-500 mt-1">Campus Director and College Deans</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('faculty.summary-reports.dean-director.export') }}" class="inline-flex items-center px-3 py-1.5 rounded-md bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition-colors">
                                    Export XLSX
                                </a>
                                <span class="text-xs text-gray-500">{{ $deanDirectorRows->count() }} {{ Str::plural('employee', $deanDirectorRows->count()) }}</span>
                            </div>
                        </div>

                        <!-- Desktop View -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[1050px]">
                                <thead>
                                    <tr class="border-b border-gray-200 bg-gray-50/50">
                                        <th class="px-5 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest min-w-[260px]">Name of Employee</th>
                                        <th class="px-3 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest min-w-[140px] text-center">Strategic Objectives<br>35%</th>
                                        <th class="px-3 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest min-w-[140px] text-center">Core Function/s<br>55%</th>
                                        <th class="px-3 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest min-w-[140px] text-center">Support Function/s<br>10%</th>
                                        <th class="px-3 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest min-w-[100px] text-center">Total (1-5)</th>
                                        <th class="px-5 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest min-w-[180px] text-center">Equivalent Adjectival Rating</th>
                                        <th class="px-5 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest min-w-[130px] text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($deanDirectorRows as $row)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-5 py-3">
                                            @php
                                                $desktopFormId = 'deanDirectorScoreForm' . $row['user_id'];
                                                $desktopClearFormId = 'deanDirectorClearForm' . $row['user_id'];
                                            @endphp
                                            <form id="{{ $desktopFormId }}" method="POST" action="{{ route('faculty.summary-reports.dean-director.update', ['user' => $row['user_id']]) }}" class="hidden">
                                                @csrf
                                                @method('PUT')
                                            </form>
                                            <form id="{{ $desktopClearFormId }}" method="POST" action="{{ route('faculty.summary-reports.dean-director.update', ['user' => $row['user_id']]) }}" class="hidden">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="clear_scores" value="1" />
                                            </form>
                                            <div class="font-bold text-gray-900 text-sm">{{ $row['employee_name'] ?? 'N/A' }}</div>
                                            <div class="text-[11px] text-gray-500 mt-0.5">
                                                {{ $row['employee_label_short'] ?? $row['employee_label'] }}{{ $row['employee_id'] ? ' · ' . $row['employee_id'] : '' }}
                                            </div>
                                            @if($row['is_manual'])
                                                <span class="inline-flex mt-2 px-2 py-0.5 rounded border border-blue-200 bg-blue-50 text-[10px] font-semibold uppercase tracking-wider text-blue-700">Manual override</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3 text-center">
                                            <input type="number" name="strategic_score" form="{{ $desktopFormId }}" step="0.01" min="0" max="35"
                                                value="{{ $row['strategic_score'] !== null ? number_format($row['strategic_score'], 2, '.', '') : '' }}"
                                                class="w-24 text-center text-sm text-gray-900 border border-gray-200 rounded-md px-2 py-1 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                                        </td>
                                        <td class="px-3 py-3 text-center">
                                            <input type="number" name="core_score" form="{{ $desktopFormId }}" step="0.01" min="0" max="55"
                                                value="{{ $row['core_score'] !== null ? number_format($row['core_score'], 2, '.', '') : '' }}"
                                                class="w-24 text-center text-sm text-gray-900 border border-gray-200 rounded-md px-2 py-1 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                                        </td>
                                        <td class="px-3 py-3 text-center">
                                            <input type="number" name="support_score" form="{{ $desktopFormId }}" step="0.01" min="0" max="10"
                                                value="{{ $row['support_score'] !== null ? number_format($row['support_score'], 2, '.', '') : '' }}"
                                                class="w-24 text-center text-sm text-gray-900 border border-gray-200 rounded-md px-2 py-1 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                                        </td>
                                        <td class="px-3 py-3 text-center text-sm font-bold text-gray-900">
                                            {{ $row['total_score'] !== null ? number_format($row['total_score'], 2) : '—' }}
                                        </td>
                                        <td class="px-5 py-3 text-center">
                                            @if($row['adjectival_rating'])
                                                @php
                                                    $leaderRatingColor = match($row['adjectival_rating']) {
                                                        'Outstanding' => 'text-blue-700 bg-blue-50 border border-blue-200',
                                                        'Very Satisfactory' => 'text-green-700 bg-green-50 border border-green-200',
                                                        'Satisfactory' => 'text-yellow-700 bg-yellow-50 border border-yellow-200',
                                                        'Unsatisfactory' => 'text-orange-700 bg-orange-50 border border-orange-200',
                                                        'Poor' => 'text-red-700 bg-red-50 border border-red-200',
                                                        default => 'text-gray-700 bg-gray-50 border border-gray-200',
                                                    };
                                                @endphp
                                                <span class="inline-block px-2.5 py-1 rounded-md shadow-sm text-xs font-bold {{ $leaderRatingColor }}">
                                                    {{ $row['adjectival_rating'] }}
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-400 italic">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-center">
                                            <div class="inline-flex items-center gap-2">
                                                <button type="submit" form="{{ $desktopFormId }}" title="Save" class="inline-flex items-center justify-center p-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                                <button type="submit" form="{{ $desktopClearFormId }}" title="Clear" class="inline-flex items-center justify-center p-2 rounded-md bg-white border border-gray-300 text-gray-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-5 py-12 text-center">
                                            <div class="text-gray-400 mb-1">
                                                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H5a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <p class="text-sm text-gray-500">No dean or director records found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile View -->
                        <div class="md:hidden divide-y divide-gray-100">
                            @forelse($deanDirectorRows as $row)
                            <div class="p-4 bg-white hover:bg-gray-50 transition-colors">
                                @php
                                    $mobileFormId = 'deanDirectorMobileScoreForm' . $row['user_id'];
                                @endphp
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">{{ $row['employee_name'] ?? 'N/A' }}</h4>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $row['employee_label_short'] ?? $row['employee_label'] }}{{ $row['employee_id'] ? ' · ' . $row['employee_id'] : '' }}</p>
                                        @if($row['is_manual'])
                                            <span class="inline-flex mt-2 px-2 py-0.5 rounded border border-blue-200 bg-blue-50 text-[10px] font-semibold uppercase tracking-wider text-blue-700">Manual override</span>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-bold text-gray-900">{{ $row['total_score'] !== null ? number_format($row['total_score'], 2) : '—' }}</div>
                                        <div class="text-[10px] text-gray-500 uppercase tracking-widest">Total (1-5)</div>
                                    </div>
                                </div>
                                <form id="{{ $mobileFormId }}" method="POST" action="{{ route('faculty.summary-reports.dean-director.update', ['user' => $row['user_id']]) }}" class="space-y-2 mb-3">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-1 gap-2">
                                        <label class="text-[11px] text-gray-600 font-medium">
                                            Strategic Objectives (35%)
                                            <input type="number" name="strategic_score" step="0.01" min="0" max="35"
                                                value="{{ $row['strategic_score'] !== null ? number_format($row['strategic_score'], 2, '.', '') : '' }}"
                                                class="mt-1 w-full border border-gray-200 rounded-md px-2.5 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                                        </label>
                                        <label class="text-[11px] text-gray-600 font-medium">
                                            Core Function/s (55%)
                                            <input type="number" name="core_score" step="0.01" min="0" max="55"
                                                value="{{ $row['core_score'] !== null ? number_format($row['core_score'], 2, '.', '') : '' }}"
                                                class="mt-1 w-full border border-gray-200 rounded-md px-2.5 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                                        </label>
                                        <label class="text-[11px] text-gray-600 font-medium">
                                            Support Function/s (10%)
                                            <input type="number" name="support_score" step="0.01" min="0" max="10"
                                                value="{{ $row['support_score'] !== null ? number_format($row['support_score'], 2, '.', '') : '' }}"
                                                class="mt-1 w-full border border-gray-200 rounded-md px-2.5 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                                        </label>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="submit" title="Save" class="flex justify-center items-center w-full px-3 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                        <button type="submit" name="clear_scores" value="1" formnovalidate title="Clear" class="flex justify-center items-center w-full px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </form>
                                <div>
                                    @if($row['adjectival_rating'])
                                        @php
                                            $leaderRatingColor = match($row['adjectival_rating']) {
                                                'Outstanding' => 'text-blue-700 bg-blue-50 border border-blue-200',
                                                'Very Satisfactory' => 'text-green-700 bg-green-50 border border-green-200',
                                                'Satisfactory' => 'text-yellow-700 bg-yellow-50 border border-yellow-200',
                                                'Unsatisfactory' => 'text-orange-700 bg-orange-50 border border-orange-200',
                                                'Poor' => 'text-red-700 bg-red-50 border border-red-200',
                                                default => 'text-gray-700 bg-gray-50 border border-gray-200',
                                            };
                                        @endphp
                                        <span class="w-full block text-center px-2.5 py-1.5 rounded-md shadow-sm text-xs font-bold {{ $leaderRatingColor }}">
                                            {{ $row['adjectival_rating'] }}
                                        </span>
                                    @else
                                        <span class="block w-full text-center text-sm text-gray-400 italic py-1">N/A</span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="p-8 text-center text-sm text-gray-500">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H5a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                No dean or director records found.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @else
                @php
                    $reportSections = $activeCategory === 'staff'
                        ? [
                            [
                                'title' => 'Staff (Permanent, Casual and Contractual)',
                                'members' => $users,
                                'empty' => 'No staff members (Permanent, Casual and Contractual) found',
                            ],
                            [
                                'title' => 'Emergency Laborer',
                                'members' => $emergencyUsers,
                                'empty' => 'No emergency laborers found',
                            ],
                        ]
                        : [
                            [
                                'title' => 'Faculty Members',
                                'members' => $users,
                                'empty' => 'No faculty members found',
                            ],
                            [
                                'title' => 'Part-Time Faculty Members',
                                'members' => $partTimeUsers,
                                'empty' => 'No part-time faculty members found',
                            ],
                        ];
                @endphp
                <div class="space-y-6">
                    @foreach($reportSections as $section)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 bg-white flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">{{ $section['title'] }}</h3>
                            <span class="text-xs text-gray-500">{{ $section['members']->count() }} {{ Str::plural('member', $section['members']->count()) }}</span>
                        </div>
                        <!-- Desktop View -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[1000px]">
                                <thead>
                                    <tr class="border-b border-gray-200 bg-gray-50/50">
                                        <th class="px-5 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest min-w-[220px]">Name</th>
                                        <th class="px-3 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest min-w-[160px]">Position</th>
                                        <th class="px-3 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest min-w-[100px]">Status</th>
                                        <th class="px-3 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest min-w-[180px]">Office Assignment</th>
                                        <th class="px-3 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest min-w-[100px] text-center">Rating</th>
                                        <th class="px-5 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest min-w-[160px] text-center">Adjectival Rating</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($section['members'] as $user)
                                    @php
                                        $initials = collect(explode(' ', $user->name))->map(fn($word) => strtoupper(substr($word, 0, 1)))->take(2)->implode('');
                                        $avatarColors = ['bg-blue-100 text-blue-700', 'bg-purple-100 text-purple-700', 'bg-green-100 text-green-700', 'bg-amber-100 text-amber-700', 'bg-rose-100 text-rose-700', 'bg-teal-100 text-teal-700'];
                                        $colorIndex = crc32($user->name) % count($avatarColors);
                                        $avatarColor = $avatarColors[abs($colorIndex)];
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 transition-colors group">
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                @if($user->hasProfilePhoto())
                                                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                                                @else
                                                    <div class="w-8 h-8 rounded-full {{ $avatarColor }} flex items-center justify-center font-bold text-xs flex-shrink-0">
                                                        {{ $initials }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-bold text-gray-900 text-sm leading-tight">{{ $user->name }}</div>
                                                    <div class="text-[11px] text-gray-500 mt-0.5">{{ $user->employee_id ?? '—' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-sm text-gray-900">{{ $user->designation->title ?? 'N/A' }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            @if($activeCategory === 'staff')
                                                @php
                                                    $staffStatusColor = match($user->employment_status) {
                                                        'Permanent' => 'text-emerald-700 bg-emerald-50 border border-emerald-200',
                                                        'Casual' => 'text-amber-700 bg-amber-50 border border-amber-200',
                                                        'Contractual' => 'text-sky-700 bg-sky-50 border border-sky-200',
                                                        'Emergency Laborer' => 'text-indigo-700 bg-indigo-50 border border-indigo-200',
                                                        default => 'text-gray-700 bg-gray-50 border border-gray-200',
                                                    };
                                                @endphp
                                                <span class="inline-block px-2.5 py-1 rounded-md shadow-sm text-xs font-bold {{ $staffStatusColor }}">
                                                    {{ $user->employment_status ?? 'N/A' }}
                                                </span>
                                            @elseif($user->employment_status === 'Part Time')
                                                <span class="inline-block px-2.5 py-1 rounded-md shadow-sm text-xs font-bold text-violet-700 bg-violet-50 border border-violet-200">
                                                    Part Time
                                                </span>
                                            @elseif($user->is_active)
                                                <span class="inline-flex items-center gap-1.5 text-sm text-green-700 bg-green-50 px-2 py-0.5 rounded-md border border-green-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 text-sm text-gray-500 bg-gray-50 px-2 py-0.5 rounded-md border border-gray-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-sm text-gray-900">{{ $user->department->name ?? 'N/A' }}</span>
                                        </td>
                                        <td class="px-3 py-3 text-center">
                                            @if($user->calibrated_rating !== null)
                                                <span class="text-sm font-bold text-gray-900">{{ number_format($user->calibrated_rating, 2) }}</span>
                                            @else
                                                <span class="text-sm text-gray-400 italic">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-center">
                                            @if($user->adjectival_rating)
                                                @php
                                                    $ratingColor = match($user->adjectival_rating) {
                                                        'Outstanding' => 'text-blue-700 bg-blue-50 border border-blue-200',
                                                        'Very Satisfactory' => 'text-green-700 bg-green-50 border border-green-200',
                                                        'Satisfactory' => 'text-yellow-700 bg-yellow-50 border border-yellow-200',
                                                        'Unsatisfactory' => 'text-orange-700 bg-orange-50 border border-orange-200',
                                                        'Poor' => 'text-red-700 bg-red-50 border border-red-200',
                                                        default => 'text-gray-700 bg-gray-50 border border-gray-200',
                                                    };
                                                @endphp
                                                <span class="inline-block px-2.5 py-1 rounded-md shadow-sm text-xs font-bold {{ $ratingColor }}">
                                                    {{ $user->adjectival_rating }}
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-400 italic">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-12 text-center">
                                            <div class="text-gray-400 mb-1">
                                                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            </div>
                                            <p class="text-sm text-gray-500">{{ $section['empty'] }}{{ $activeDepartment !== 'all' ? ' in this department' : '' }}.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile View -->
                        <div class="md:hidden divide-y divide-gray-100">
                            @forelse($section['members'] as $user)
                            @php
                                $initials = collect(explode(' ', $user->name))->map(fn($word) => strtoupper(substr($word, 0, 1)))->take(2)->implode('');
                                $avatarColors = ['bg-blue-100 text-blue-700', 'bg-purple-100 text-purple-700', 'bg-green-100 text-green-700', 'bg-amber-100 text-amber-700', 'bg-rose-100 text-rose-700', 'bg-teal-100 text-teal-700'];
                                $colorIndex = crc32($user->name) % count($avatarColors);
                                $avatarColor = $avatarColors[abs($colorIndex)];
                            @endphp
                            <div class="p-4 bg-white hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-3 mb-3">
                                    @if($user->hasProfilePhoto())
                                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-full {{ $avatarColor }} flex items-center justify-center font-bold text-sm flex-shrink-0">
                                            {{ $initials }}
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-gray-900 text-sm leading-tight truncate">{{ $user->name }}</div>
                                        <div class="text-[11px] text-gray-500 mt-0.5 truncate">{{ $user->designation->title ?? 'N/A' }}{{ $user->employee_id ? ' · ' . $user->employee_id : '' }}</div>
                                    </div>
                                    <div class="text-right pl-2 shrink-0">
                                        <div class="text-[10px] text-gray-500 uppercase tracking-widest mb-0.5">Rating</div>
                                        @if($user->calibrated_rating !== null)
                                            <div class="text-sm font-bold text-gray-900">{{ number_format($user->calibrated_rating, 2) }}</div>
                                        @else
                                            <div class="text-sm text-gray-400 italic">N/A</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-2 items-center mb-3 text-xs">
                                    <span class="inline-flex items-center gap-1 text-gray-600 bg-gray-100 px-2 py-1 rounded-md">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        {{ $user->department->name ?? 'N/A' }}
                                    </span>
                                    
                                    @if($activeCategory === 'staff')
                                        @php
                                            $staffStatusColor = match($user->employment_status) {
                                                'Permanent' => 'text-emerald-700 bg-emerald-50 border border-emerald-200',
                                                'Casual' => 'text-amber-700 bg-amber-50 border border-amber-200',
                                                'Contractual' => 'text-sky-700 bg-sky-50 border border-sky-200',
                                                'Emergency Laborer' => 'text-indigo-700 bg-indigo-50 border border-indigo-200',
                                                default => 'text-gray-700 bg-gray-50 border border-gray-200',
                                            };
                                        @endphp
                                        <span class="inline-block px-2 py-1 rounded-md text-xs font-medium {{ $staffStatusColor }}">
                                            {{ $user->employment_status ?? 'N/A' }}
                                        </span>
                                    @elseif($user->employment_status === 'Part Time')
                                        <span class="inline-block px-2 py-1 rounded-md text-xs font-medium text-violet-700 bg-violet-50 border border-violet-200">
                                            Part Time
                                        </span>
                                    @elseif($user->is_active)
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-green-700 bg-green-50 px-2 py-1 rounded-md border border-green-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-gray-500 bg-gray-50 px-2 py-1 rounded-md border border-gray-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactive
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    @if($user->adjectival_rating)
                                        @php
                                            $ratingColor = match($user->adjectival_rating) {
                                                'Outstanding' => 'text-blue-700 bg-blue-50 border border-blue-200',
                                                'Very Satisfactory' => 'text-green-700 bg-green-50 border border-green-200',
                                                'Satisfactory' => 'text-yellow-700 bg-yellow-50 border border-yellow-200',
                                                'Unsatisfactory' => 'text-orange-700 bg-orange-50 border border-orange-200',
                                                'Poor' => 'text-red-700 bg-red-50 border border-red-200',
                                                default => 'text-gray-700 bg-gray-50 border border-gray-200',
                                            };
                                        @endphp
                                        <span class="w-full block text-center px-2.5 py-1.5 rounded-md shadow-sm text-xs font-bold {{ $ratingColor }}">
                                            {{ $user->adjectival_rating }}
                                        </span>
                                    @else
                                        <span class="block w-full text-center text-sm text-gray-400 italic py-1">N/A</span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="p-8 text-center text-sm text-gray-500">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                {{ $section['empty'] }}{{ $activeDepartment !== 'all' ? ' in this department' : '' }}.
                            </div>
                            @endforelse
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>