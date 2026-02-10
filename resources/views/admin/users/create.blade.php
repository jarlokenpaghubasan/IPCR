<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - Admin Dashboard</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/urs_logo.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/admin_users_create.css', 'resources/js/admin_users_create.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="text-gray-600 text-sm">Create New User</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <span class="text-gray-900 font-semibold text-sm">{{ auth()->user()->name }}</span>
                        <p class="text-gray-600 text-xs">Admin</p>
                    </div>
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover">
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-700 font-semibold">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-8">
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-900 mb-6 inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>

            <h2 class="text-2xl font-bold text-gray-900 mb-6">Create New User</h2>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="text-red-800 font-semibold mb-2">Please fix the following errors:</h3>
                    <ul class="list-disc list-inside text-red-700 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
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
                            @error('name')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('email')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                            <input type="text" name="username" id="username" value="{{ old('username') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('username')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('phone')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
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
                                    <svg id="password_eye_open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg id="password_eye_closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('password')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <button type="button" onclick="togglePasswordVisibility('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 z-10 cursor-pointer">
                                    <svg id="password_confirmation_eye_open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg id="password_confirmation_eye_closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
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
                                            onchange="handleRoleSelection()"
                                        >
                                        <label for="role_{{ $role }}" class="ml-2 text-sm text-gray-700 cursor-pointer">
                                            {{ $role == 'hr' ? 'Human Resource' : ucfirst($role) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Select one or more roles</p>
                            @error('roles')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div class="flex items-center gap-2 mt-2">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded">
                                <label for="is_active" class="text-gray-700">Active</label>
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
                            @error('department_id')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
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
                            @error('designation_id')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold flex items-center gap-2">
                        <i class="fas fa-save"></i> Create User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>