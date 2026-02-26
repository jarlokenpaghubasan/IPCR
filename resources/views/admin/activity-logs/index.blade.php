@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('header')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-0">
        <div class="flex-1">
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Activity Logs</h2>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-2">
                System-wide activity tracking across all users and roles
            </p>
        </div>
    </div>
@endsection

@section('content')

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md hover:-translate-y-1 p-5 lg:p-6 transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center transform group-hover:rotate-3 transition-transform">
                    <i class="fas fa-list text-blue-600 dark:text-blue-400 text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium tracking-wide uppercase">Total Logs</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($totalLogs) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md hover:-translate-y-1 p-5 lg:p-6 transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-green-50 dark:bg-green-900/30 flex items-center justify-center transform group-hover:-rotate-3 transition-transform">
                    <i class="fas fa-calendar-day text-green-600 dark:text-green-400 text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium tracking-wide uppercase">Today's Logs</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ number_format($todayLogs) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md hover:-translate-y-1 p-5 lg:p-6 transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center transform group-hover:rotate-3 transition-transform">
                    <i class="fas fa-users text-purple-600 dark:text-purple-400 text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium tracking-wide uppercase">Active Users Today</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($uniqueToday) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-4 sm:p-5 mb-6 transition-all duration-300">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="flex flex-col xl:flex-row xl:items-end gap-5">
            {{-- Search --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Search</label>
                <div class="relative group">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm group-focus-within:text-blue-500 transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search logs..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 transition-all">
                </div>
            </div>

            <div class="flex flex-wrap md:flex-nowrap gap-5 w-full xl:w-auto">
                {{-- Action --}}
                <div class="flex-1 md:w-44 xl:w-36">
                    <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Action</label>
                    <div class="relative group">
                        <select name="action" class="w-full pl-4 pr-10 py-2.5 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-gray-900 dark:text-white transition-all appearance-none cursor-pointer">
                            <option value="">All Actions</option>
                            @foreach($actions as $a)
                                <option value="{{ $a }}" {{ request('action') === $a ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $a)) }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-[10px] pointer-events-none transition-transform group-hover:translate-y-0.5"></i>
                    </div>
                </div>

                {{-- User --}}
                <div class="flex-1 md:w-48 xl:w-44">
                    <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">User</label>
                    <div class="relative group">
                        <select name="user_id" class="w-full pl-4 pr-10 py-2.5 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-gray-900 dark:text-white transition-all appearance-none cursor-pointer">
                            <option value="">All Users</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-[10px] pointer-events-none transition-transform group-hover:translate-y-0.5"></i>
                    </div>
                </div>

                {{-- Dates --}}
                <div class="flex gap-3 w-full md:w-auto">
                    <div class="flex-1 md:w-36">
                        <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">From</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                               class="w-full px-4 py-2.5 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-gray-900 dark:text-white transition-all cursor-pointer">
                    </div>
                    <div class="flex-1 md:w-36">
                        <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">To</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                               class="w-full px-4 py-2.5 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-gray-900 dark:text-white transition-all cursor-pointer">
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-wrap md:flex-nowrap gap-3 w-full xl:w-auto mt-2 xl:mt-0">
                <button type="submit" class="flex-1 xl:flex-none px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-all duration-300 flex items-center justify-center gap-2 shadow-sm hover:shadow active:scale-95">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'action', 'user_id', 'date_from', 'date_to']))
                    <a href="{{ route('admin.activity-logs.index') }}" class="flex-1 xl:flex-none px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center justify-center gap-2 shadow-sm active:scale-95">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                @endif
                <button type="button" onclick="openExportModal()" class="flex-1 xl:flex-none px-6 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-400 dark:hover:border-emerald-800/30 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center justify-center gap-2 shadow-sm active:scale-95 whitespace-nowrap min-w-[110px]">
                    <i class="fas fa-file-export"></i> Export
                </button>
            </div>
        </form>
    </div>


    {{-- Logs Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden transition-all duration-300">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50/80 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 font-bold text-[11px] uppercase tracking-widest whitespace-nowrap">Time</th>
                        <th class="px-6 py-4 font-bold text-[11px] uppercase tracking-widest whitespace-nowrap">User</th>
                        <th class="px-6 py-4 font-bold text-[11px] uppercase tracking-widest whitespace-nowrap">Role</th>
                        <th class="px-6 py-4 font-bold text-[11px] uppercase tracking-widest whitespace-nowrap">Action</th>
                        <th class="px-6 py-4 font-bold text-[11px] uppercase tracking-widest whitespace-nowrap">Description</th>
                        <th class="px-6 py-4 font-bold text-[11px] uppercase tracking-widest whitespace-nowrap">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-colors">
                            {{-- Time --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $log->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">{{ $log->created_at->format('h:i:s A') }}</div>
                            </td>

                            {{-- User --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-xs uppercase">
                                        {{ substr($log->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <span class="font-semibold text-gray-900 dark:text-white text-sm">{{ $log->user->name ?? 'Unknown User' }}</span>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->user)
                                    @php $role = $log->user->getPrimaryRole(); @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium uppercase tracking-wider
                                        {{ $role === 'admin' ? 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300' : '' }}
                                        {{ $role === 'director' ? 'bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300' : '' }}
                                        {{ $role === 'dean' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300' : '' }}
                                        {{ $role === 'faculty' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                    ">
                                        {{ ucfirst($role ?? 'N/A') }}
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-xs">—</span>
                                @endif
                            </td>

                            {{-- Action Badge --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $colors = [
                                        'login'            => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',
                                        'logout'           => 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-500/10 dark:text-gray-400 dark:border-gray-500/20',
                                        'created'          => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20',
                                        'updated'          => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
                                        'deleted'          => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20',
                                        'toggled_active'   => 'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-500/10 dark:text-orange-400 dark:border-orange-500/20',
                                        'backup_created'   => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20',
                                        'backup_restored'  => 'bg-cyan-50 text-cyan-700 border-cyan-200 dark:bg-cyan-500/10 dark:text-cyan-400 dark:border-cyan-500/20',
                                        'backup_deleted'   => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20',
                                        'backup_uploaded'  => 'bg-violet-50 text-violet-700 border-violet-200 dark:bg-violet-500/10 dark:text-violet-400 dark:border-violet-500/20',
                                        'settings_updated' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
                                        'profile_updated'  => 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-500/10 dark:text-sky-400 dark:border-sky-500/20',
                                        'password_changed' => 'bg-pink-50 text-pink-700 border-pink-200 dark:bg-pink-500/10 dark:text-pink-400 dark:border-pink-500/20',
                                        'password_reset'   => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200 dark:bg-fuchsia-500/10 dark:text-fuchsia-400 dark:border-fuchsia-500/20',
                                        'photo_uploaded'   => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-500/10 dark:text-purple-400 dark:border-purple-500/20',
                                        'photo_deleted'    => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20',
                                    ];
                                    $badge = $colors[$log->action] ?? 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-500/10 dark:text-gray-400 dark:border-gray-500/20';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] uppercase tracking-widest font-bold border {{ $badge }}">
                                    {{ str_replace('_', ' ', $log->action) }}
                                </span>
                            </td>

                            {{-- Description --}}
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400 max-w-sm truncate text-sm" title="{{ $log->description }}">
                                {{ $log->description }}
                            </td>

                            {{-- IP --}}
                            <td class="px-6 py-4 whitespace-nowrap text-gray-400 dark:text-gray-500 text-xs font-mono">
                                {{ $log->ip_address ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-400 dark:text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                <p class="text-base font-medium">No activity logs found</p>
                                <p class="text-sm">Logs will appear here once system activity is recorded.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-3 p-4">
            @forelse($logs as $log)
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-100 dark:border-gray-700 space-y-3">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-sm uppercase">
                                {{ substr($log->user->name ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $log->user->name ?? 'Unknown User' }}</p>
                                @if($log->user)
                                    @php $role = $log->user->getPrimaryRole(); @endphp
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] uppercase font-bold tracking-wide mt-0.5
                                        {{ $role === 'admin' ? 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300' : '' }}
                                        {{ $role === 'director' ? 'bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300' : '' }}
                                        {{ $role === 'dean' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300' : '' }}
                                        {{ $role === 'faculty' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                    ">
                                        {{ ucfirst($role ?? 'N/A') }}
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-xs">—</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                             <div class="font-medium text-gray-900 dark:text-white text-xs">{{ $log->created_at->format('M d, Y') }}</div>
                             <div class="text-[10px] text-gray-500 dark:text-gray-400">{{ $log->created_at->format('h:i:s A') }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        @php
                            $colors = [
                                'login'            => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',
                                'logout'           => 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-500/10 dark:text-gray-400 dark:border-gray-500/20',
                                'created'          => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20',
                                'updated'          => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
                                'deleted'          => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20',
                                'toggled_active'   => 'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-500/10 dark:text-orange-400 dark:border-orange-500/20',
                                'backup_created'   => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20',
                                'backup_restored'  => 'bg-cyan-50 text-cyan-700 border-cyan-200 dark:bg-cyan-500/10 dark:text-cyan-400 dark:border-cyan-500/20',
                                'backup_deleted'   => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20',
                                'backup_uploaded'  => 'bg-violet-50 text-violet-700 border-violet-200 dark:bg-violet-500/10 dark:text-violet-400 dark:border-violet-500/20',
                                'settings_updated' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
                                'profile_updated'  => 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-500/10 dark:text-sky-400 dark:border-sky-500/20',
                                'password_changed' => 'bg-pink-50 text-pink-700 border-pink-200 dark:bg-pink-500/10 dark:text-pink-400 dark:border-pink-500/20',
                                'password_reset'   => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200 dark:bg-fuchsia-500/10 dark:text-fuchsia-400 dark:border-fuchsia-500/20',
                                'photo_uploaded'   => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-500/10 dark:text-purple-400 dark:border-purple-500/20',
                                'photo_deleted'    => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20',
                            ];
                            $badge = $colors[$log->action] ?? 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-500/10 dark:text-gray-400 dark:border-gray-500/20';
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] tracking-widest uppercase font-bold border {{ $badge }}">
                            {{ str_replace('_', ' ', $log->action) }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                        {{ $log->description }}
                    </div>

                    <div class="text-[10px] font-mono text-gray-400 dark:text-gray-500 pt-1 flex items-center gap-1">
                        <i class="fas fa-network-wired"></i> IP: {{ $log->ip_address ?? '—' }}
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-400 dark:text-gray-500 py-12 text-sm">
                    No activity logs found.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

@endsection

@push('modals')
    <!-- Export Modal -->
    <div id="exportModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 w-full max-w-md mx-4 animate-scale-in transition-colors">
            <div class="flex items-center justify-between mb-4 border-b dark:border-gray-700 pb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Export Logs</h3>
                <button onclick="closeExportModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="exportForm" action="{{ route('admin.activity-logs.export') }}" method="GET" target="download_iframe">
                <div class="space-y-4 mb-6">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search description or user..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <!-- Date Range -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                    </div>

                    <!-- User -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User</label>
                        <select name="user_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Action</label>
                        <select name="action" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">All Actions</option>
                            @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $action)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeExportModal()" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white rounded-lg font-medium transition flex items-center gap-2">
                        <i class="fas fa-file-download"></i> Download .txt
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Hidden iframe for download -->
    <iframe name="download_iframe" id="download_iframe" style="display:none;"></iframe>
@endpush

@push('scripts')
<script>
    function openExportModal() {
        document.getElementById('exportModal').classList.remove('hidden');
    }
    function closeExportModal() {
        document.getElementById('exportModal').classList.add('hidden');
    }
    
    // Close on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeExportModal();
    });
    
    // Close on backdrop click
    document.getElementById('exportModal').addEventListener('click', function(e) {
        if (e.target === this) closeExportModal();
    });
</script>
@endpush
