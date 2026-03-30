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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        <div class="flex flex-col md:flex-row gap-6 lg:gap-8">
            <!-- Sidebar -->
            <div class="w-full md:w-56 lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden h-full">
                    <div class="p-6 space-y-4">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Staff Category</h2>
                        
                        <a href="#" class="block px-3 py-2 rounded-lg bg-gray-50 text-gray-900 font-bold text-sm transition-colors border-l-4 border-blue-600">
                            Faculty
                        </a>
                        
                        <a href="#" class="block px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 text-sm font-medium transition-colors border-l-4 border-transparent hover:border-gray-300">
                            Staff
                        </a>
                        
                        <a href="#" class="block px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 text-sm font-medium transition-colors border-l-4 border-transparent hover:border-gray-300">
                            Part time
                        </a>
                        
                        <a href="#" class="block px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 text-sm font-medium transition-colors border-l-4 border-transparent hover:border-gray-300">
                            Dean and Director
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 min-w-0">
                <!-- Tabs -->
                <div class="mb-6">
                    <div class="flex space-x-2 overflow-x-auto pb-1">
                        <a href="{{ route('faculty.summary-reports', ['department' => 'all']) }}"
                           class="px-5 py-2 rounded-full text-sm whitespace-nowrap shadow-sm transition-colors {{ $activeDepartment === 'all' ? 'bg-blue-600 text-white font-semibold' : 'bg-white border border-gray-200 text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900' }}">
                            All
                        </a>
                        @foreach($departments as $dept)
                        <a href="{{ route('faculty.summary-reports', ['department' => $dept->code]) }}"
                           class="px-5 py-2 rounded-full text-sm whitespace-nowrap shadow-sm transition-colors {{ $activeDepartment === $dept->code ? 'bg-blue-600 text-white font-semibold' : 'bg-white border border-gray-200 text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900' }}">
                            {{ $dept->code }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Tables Container -->
                <div class="space-y-6">
                    
                    <!-- Faculty List -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 bg-white flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Faculty Members</h3>
                            <span class="text-xs text-gray-500">{{ $users->count() }} {{ Str::plural('member', $users->count()) }}</span>
                        </div>
                        <div class="overflow-x-auto">
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
                                    @forelse($users as $user)
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
                                            @if($user->is_active)
                                                <span class="inline-flex items-center gap-1.5 text-sm text-green-700">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 text-sm text-gray-500">
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
                                                        'Outstanding' => 'text-blue-700 bg-blue-50',
                                                        'Very Satisfactory' => 'text-green-700 bg-green-50',
                                                        'Satisfactory' => 'text-yellow-700 bg-yellow-50',
                                                        'Unsatisfactory' => 'text-orange-700 bg-orange-50',
                                                        'Poor' => 'text-red-700 bg-red-50',
                                                        default => 'text-gray-700 bg-gray-50',
                                                    };
                                                @endphp
                                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold {{ $ratingColor }}">
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
                                            <p class="text-sm text-gray-500">No faculty members found{{ $activeDepartment !== 'all' ? ' in this department' : '' }}.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
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