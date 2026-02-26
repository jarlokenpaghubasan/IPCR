@extends('layouts.admin')

@section('title', 'View User')

@section('header')
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">User Management</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400">View User Details</p>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 inline-flex items-center gap-2 text-sm font-medium transition-colors">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <!-- User Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden transition-colors">
        <!-- User Header (Photo + Name) -->
        <div class="p-6 sm:p-8 border-b border-gray-100 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-start md:gap-8">
                <!-- Photo -->
                <div class="flex flex-col items-center md:items-start mb-6 md:mb-0 shrink-0">
                    @if($user->hasProfilePhoto())
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-32 h-32 sm:w-40 sm:h-40 object-cover rounded-2xl shadow-sm border border-gray-200 dark:border-gray-600">
                    @else
                        <div class="w-32 h-32 sm:w-40 sm:h-40 bg-gray-50 dark:bg-gray-700/50 rounded-2xl flex items-center justify-center border-2 border-dashed border-gray-200 dark:border-gray-600">
                            <i class="fas fa-user text-gray-300 dark:text-gray-500 text-5xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Name and Roles -->
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                    <div class="flex gap-2 mt-3 flex-wrap justify-center md:justify-start">
                        @foreach($user->roles() as $role)
                            <span class="px-3 py-1 rounded-full text-xs font-semibold tracking-wide uppercase
                                @if($role === 'admin') bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400
                                @elseif($role === 'director') bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400
                                @elseif($role === 'dean') bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400
                                @else bg-gray-50 text-gray-600 dark:bg-gray-700 dark:text-gray-300
                                @endif
                            ">
                                {{ ucfirst($role) }}
                            </span>
                        @endforeach
                    </div>

                    <!-- Edit Button -->
                    <a href="{{ route('admin.users.edit', $user) }}" class="mt-5 inline-flex bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium items-center gap-2 text-sm transition shadow-sm">
                        <i class="fas fa-edit"></i> Edit User
                    </a>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="p-6 sm:p-8 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-5 uppercase tracking-wide">Personal Information</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wide">Full Name</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $user->name }}</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wide">Email</label>
                    <p class="text-gray-900 dark:text-white font-medium break-all">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wide">Username</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $user->username }}</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wide">Phone</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $user->phone ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="p-6 sm:p-8 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-5 uppercase tracking-wide">Account Information</h3>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wide">Status</label>
                <span class="px-3 py-1 rounded-full text-xs font-semibold tracking-wide uppercase inline-flex items-center gap-1.5 {{ $user->is_active ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        <!-- Department & Designation -->
        <div class="p-6 sm:p-8 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-5 uppercase tracking-wide">Department & Designation</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wide">Department</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $user->department->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wide">Designation</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $user->designation->title ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="p-6 sm:p-8 bg-gray-50/50 dark:bg-gray-800/50 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium text-center text-sm transition shadow-sm inline-flex items-center justify-center gap-2">
                <i class="fas fa-edit"></i> Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 px-5 py-2.5 rounded-lg font-medium text-center text-sm transition shadow-sm">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection