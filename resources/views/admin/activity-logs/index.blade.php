@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('header')
    <div class="flex-1">
        <h2 class="text-2xl font-bold text-gray-900">Activity Logs</h2>
        <p class="text-sm text-gray-600 mt-1">System-wide activity tracking across all users and roles</p>
    </div>
@endsection

@section('content')

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-list text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalLogs) }}</p>
                    <p class="text-xs text-gray-500">Total Logs</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="fas fa-calendar-day text-green-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($todayLogs) }}</p>
                    <p class="text-xs text-gray-500">Today's Logs</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-users text-purple-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($uniqueToday) }}</p>
                    <p class="text-xs text-gray-500">Active Users Today</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-5">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="flex flex-wrap items-end gap-3">
            {{-- Search --}}
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search description or user…"
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Action --}}
            <div class="min-w-[140px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Action</label>
                <select name="action" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Actions</option>
                    @foreach($actions as $a)
                        <option value="{{ $a }}" {{ request('action') === $a ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $a)) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- User --}}
            <div class="min-w-[160px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">User</label>
                <select name="user_id" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Users</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Date From --}}
            <div class="min-w-[140px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Date To --}}
            <div class="min-w-[140px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <i class="fas fa-search text-xs"></i> Filter
                </button>
                <a href="{{ route('admin.activity-logs.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <i class="fas fa-times text-xs"></i> Clear
                </a>
            </div>
        </form>
    </div>

    {{-- Logs Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Time</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">User</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Role</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Action</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Description</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            {{-- Time --}}
                            <td class="px-4 py-3 whitespace-nowrap text-gray-500">
                                <div class="text-xs">{{ $log->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $log->created_at->format('h:i:s A') }}</div>
                            </td>

                            {{-- User --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($log->user)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-gray-800">{{ $log->user->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">System</span>
                                @endif
                            </td>

                            {{-- Role --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($log->user)
                                    @php $role = $log->user->getPrimaryRole(); @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $role === 'admin' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $role === 'director' ? 'bg-purple-100 text-purple-700' : '' }}
                                        {{ $role === 'dean' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                        {{ $role === 'faculty' ? 'bg-blue-100 text-blue-700' : '' }}
                                    ">
                                        {{ ucfirst($role ?? 'N/A') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>

                            {{-- Action Badge --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $colors = [
                                        'login'            => 'bg-green-100 text-green-700',
                                        'logout'           => 'bg-gray-200 text-gray-700',
                                        'created'          => 'bg-blue-100 text-blue-700',
                                        'updated'          => 'bg-yellow-100 text-yellow-700',
                                        'deleted'          => 'bg-red-100 text-red-700',
                                        'toggled_active'   => 'bg-orange-100 text-orange-700',
                                        'backup_created'   => 'bg-teal-100 text-teal-700',
                                        'backup_restored'  => 'bg-cyan-100 text-cyan-700',
                                        'backup_deleted'   => 'bg-red-100 text-red-700',
                                        'backup_uploaded'  => 'bg-indigo-100 text-indigo-700',
                                        'settings_updated' => 'bg-amber-100 text-amber-700',
                                        'profile_updated'  => 'bg-sky-100 text-sky-700',
                                        'password_changed' => 'bg-pink-100 text-pink-700',
                                        'password_reset'   => 'bg-pink-100 text-pink-700',
                                        'photo_uploaded'   => 'bg-violet-100 text-violet-700',
                                        'photo_deleted'    => 'bg-rose-100 text-rose-700',
                                    ];
                                    $badge = $colors[$log->action] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                </span>
                            </td>

                            {{-- Description --}}
                            <td class="px-4 py-3 text-gray-700 max-w-xs truncate" title="{{ $log->description }}">
                                {{ $log->description }}
                            </td>

                            {{-- IP --}}
                            <td class="px-4 py-3 whitespace-nowrap text-gray-500 text-xs font-mono">
                                {{ $log->ip_address ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                <p class="text-base font-medium">No activity logs found</p>
                                <p class="text-sm">Logs will appear here once system activity is recorded.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

@endsection
