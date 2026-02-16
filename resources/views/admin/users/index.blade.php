@extends('layouts.admin')

@section('title', 'User Management')

@section('header')
    <div class="flex-1">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900">User Management</h2>
        <p class="text-gray-500 text-xs">Manage your {{ $totalUsers }} users</p>
    </div>
    <button onclick="openAddUserModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 text-sm transition shadow-sm">
        <i class="fas fa-plus text-xs"></i> Add User
    </button>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Users</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalUsers }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                <i class="fas fa-users text-blue-500 text-sm"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Active</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $activeUsers }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center">
                <i class="fas fa-user-check text-green-500 text-sm"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Inactive</p>
                <p class="text-2xl font-bold text-red-500 mt-1">{{ $inactiveUsers }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center">
                <i class="fas fa-user-xmark text-red-400 text-sm"></i>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-xl border border-gray-200 p-3 mb-5">
        <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm" class="flex flex-wrap items-center gap-2">
            <!-- Search -->
            <div class="relative flex-1 min-w-[200px]">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input
                    type="text"
                    name="search"
                    id="searchInput"
                    value="{{ request('search') }}"
                    placeholder="Search by name, email, or username..."
                    class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition"
                >
            </div>

            <!-- Department Filter -->
            <select
                name="department"
                id="departmentFilter"
                class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition min-w-[160px]"
            >
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>

            @if(request('search') || request('department'))
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-red-500 transition" title="Clear filters">
                    <i class="fas fa-times"></i> Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Desktop Table -->
    <div class="hidden md:block bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden xl:table-cell">Contact</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Department</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-400 font-medium">
                            {{ $users->firstItem() + $loop->index }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover border border-gray-200 flex-shrink-0">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ $user->employee_id ?? 'No ID' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden xl:table-cell">
                            <p class="text-sm text-gray-700 truncate">{{ $user->email }}</p>
                            <p class="text-xs text-gray-400">{{ $user->phone ?? '—' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @foreach($user->roles() as $role)
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium
                                    @if($role === 'admin') bg-purple-50 text-purple-700
                                    @elseif($role === 'director') bg-emerald-50 text-emerald-700
                                    @elseif($role === 'dean') bg-blue-50 text-blue-700
                                    @else bg-gray-100 text-gray-600
                                    @endif
                                ">{{ ucfirst($role) }}</span>
                            @endforeach
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="text-sm text-gray-600 truncate block max-w-[140px]">{{ $user->department?->name ?? '—' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if(auth()->user()->id !== $user->id && $user->employee_id !== 'URS26-ADM00001')
                                <form method="POST" action="{{ route('admin.users.toggleActive', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" onchange="this.form.submit()" {{ $user->is_active ? 'checked' : '' }}>
                                        <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                                        <span class="ms-2 text-xs font-medium text-gray-600 w-14">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                                    </label>
                                </form>
                            @else
                                @if($user->is_active)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Inactive
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.users.show', $user) }}" class="w-7 h-7 rounded-md flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition" title="View">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                @if($user->employee_id !== 'URS26-ADM00001')
                                    <a href="{{ route('admin.users.edit', $user) }}" class="w-7 h-7 rounded-md flex items-center justify-center text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 transition" title="Edit">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    @if(auth()->user()->id !== $user->id)
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline deleteForm" style="margin:0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="openConfirmationModal('{{ $user->name }}', this.form)" class="w-7 h-7 rounded-md flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition" title="Delete">
                                                <i class="fas fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="w-7 h-7 rounded-md flex items-center justify-center text-gray-300" title="Protected">
                                        <i class="fas fa-lock text-xs"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">
                            <i class="fas fa-users text-3xl mb-3 block text-gray-300"></i>
                            No users found. <a href="{{ route('admin.users.create') }}" class="text-blue-600 hover:underline">Create one now</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-2">
        @forelse($users as $user)
            <div class="bg-white rounded-xl border border-gray-200 p-3 flex items-center gap-3">
                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                        @if($user->is_active)
                            <span class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>
                        @else
                            <span class="w-2 h-2 rounded-full bg-red-400 flex-shrink-0"></span>
                        @endif
                    </div>
                    <div class="flex items-center gap-1 mt-0.5">
                        @foreach($user->roles() as $role)
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-medium
                                @if($role === 'admin') bg-purple-50 text-purple-700
                                @elseif($role === 'director') bg-emerald-50 text-emerald-700
                                @elseif($role === 'dean') bg-blue-50 text-blue-700
                                @else bg-gray-100 text-gray-600
                                @endif
                            ">{{ ucfirst($role) }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="flex items-center gap-0.5 flex-shrink-0">
                    <a href="{{ route('admin.users.show', $user) }}" class="w-7 h-7 rounded-md flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition">
                        <i class="fas fa-eye text-xs"></i>
                    </a>
                    @if($user->employee_id !== 'URS26-ADM00001')
                        <a href="{{ route('admin.users.edit', $user) }}" class="w-7 h-7 rounded-md flex items-center justify-center text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 transition">
                            <i class="fas fa-pen text-xs"></i>
                        </a>
                        @if(auth()->user()->id !== $user->id)
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline deleteForm" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="openConfirmationModal('{{ $user->name }}', this.form)" class="w-7 h-7 rounded-md flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
                                    <i class="fas fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        @endif
                    @else
                        <span class="w-7 h-7 rounded-md flex items-center justify-center text-gray-300">
                            <i class="fas fa-lock text-xs"></i>
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-gray-400 py-12 text-sm">
                <i class="fas fa-users text-3xl mb-3 block text-gray-300"></i>
                No users found. <a href="{{ route('admin.users.create') }}" class="text-blue-600 hover:underline">Create one now</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="mt-4 flex items-center justify-between bg-white rounded-xl border border-gray-200 px-4 py-3">
            <p class="text-xs text-gray-500">
                Showing <span class="font-medium">{{ $users->firstItem() }}</span> to <span class="font-medium">{{ $users->lastItem() }}</span> of <span class="font-medium">{{ $users->total() }}</span>
            </p>
            <div class="flex items-center gap-1">
                @if($users->onFirstPage())
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-300 text-xs">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-500 hover:bg-gray-100 text-xs transition">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                @foreach($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                    @if($page == $users->currentPage())
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-600 text-white text-xs font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-600 hover:bg-gray-100 text-xs transition">{{ $page }}</a>
                    @endif
                @endforeach

                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-500 hover:bg-gray-100 text-xs transition">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-300 text-xs">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
    @endif
@endsection

@push('modals')
    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 p-4 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full animate-scale-in">
            <div class="p-5 text-center">
                <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i>
                </div>
                <h2 class="text-base font-bold text-gray-900 mb-1">Delete User</h2>
                <p class="text-sm text-gray-500">Are you sure you want to delete <span id="deleteUserName" class="font-semibold text-gray-700">this user</span>? This action cannot be undone.</p>
            </div>
            <div class="border-t border-gray-100 px-5 py-3 flex gap-2 justify-end">
                <button type="button" onclick="closeConfirmationModal()" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                    Cancel
                </button>
                <button type="button" onclick="confirmDelete()" class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition">
                    Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl mx-4 animate-scale-in relative flex flex-col max-h-[90vh]">
            <div class="p-6 border-b flex justify-between items-center shrink-0">
                <h2 class="text-xl font-bold text-gray-900">Create New User</h2>
                <button onclick="closeAddUserModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto">
                <!-- Validation Errors (Inline) -->
                <div id="modalErrors" class="hidden mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="text-red-800 font-semibold mb-2">Please fix the following errors:</h3>
                    <ul class="list-disc list-inside text-red-700 text-sm" id="modalErrorList"></ul>
                </div>

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <h3 class="text-red-800 font-semibold mb-2">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside text-red-700 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            openAddUserModal();
                        });
                    </script>
                @endif

                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                    @csrf
    
                    <!-- Personal Information -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
    
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
    
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
    
                            <!-- Username -->
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                                <input type="text" name="username" id="username" value="{{ old('username') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
    
                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
    
                    <!-- Account Information -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
    
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" required class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button type="button" onclick="togglePasswordVisibility('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 z-10 cursor-pointer">
                                        <i class="fas fa-eye" id="password_eye_open"></i>
                                        <i class="fas fa-eye-slash hidden" id="password_eye_closed"></i>
                                    </button>
                                </div>
                            </div>
    
                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button type="button" onclick="togglePasswordVisibility('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 z-10 cursor-pointer">
                                        <i class="fas fa-eye" id="password_confirmation_eye_open"></i>
                                        <i class="fas fa-eye-slash hidden" id="password_confirmation_eye_closed"></i>
                                    </button>
                                </div>
                            </div>
    
                            <!-- Roles (Multiple Selection) -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Roles *</label>
                                <div class="space-y-2 bg-gray-50 p-4 rounded-lg">
                                    @php
                                        $availableRoles = ['admin', 'hr', 'director', 'dean', 'faculty'];
                                        $selectedRoles = old('roles', []);
                                    @endphp
                                    
                                    @foreach($availableRoles as $role)
                                        <div class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                name="roles[]" 
                                                id="role_{{ $role }}" 
                                                value="{{ $role }}"
                                                {{ in_array($role, $selectedRoles) ? 'checked' : '' }}
                                                class="w-4 h-4 text-blue-600 rounded role-checkbox"
                                            >
                                            <label for="role_{{ $role }}" class="ml-2 text-sm text-gray-700 cursor-pointer select-none">
                                                {{ $role == 'hr' ? 'Human Resource' : ucfirst($role) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Select one or more roles</p>
                            </div>
    
                            <!-- Status -->
                            <div>
                                <label for="form_is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <div class="flex items-center gap-2 mt-2">
                                    <input type="checkbox" name="is_active" id="form_is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded">
                                    <label for="form_is_active" class="text-gray-700">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <!-- Department & Designation -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Department & Designation</h3>
    
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Department -->
                            <div>
                                <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                                <select name="department_id" id="department_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select a department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
    
                            <!-- Designation -->
                            <div>
                                <label for="designation_id" class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
                                <select name="designation_id" id="designation_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select a designation</option>
                                    @foreach($designations as $desig)
                                        <option value="{{ $desig->id }}" {{ old('designation_id') == $desig->id ? 'selected' : '' }}>{{ $desig->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
    
                    <!-- Form Actions -->
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="closeAddUserModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold flex items-center gap-2 transition shadow-sm">
                            <i class="fas fa-save"></i> Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush