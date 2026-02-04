<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - IPCR/OPCR Module</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/dashboard_admin_index.css', 'resources/js/dashboard_admin_index.js'])
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="hidden lg:flex lg:w-64 bg-white border-r flex-col">
            <div class="px-6 py-6 border-b">
                <h1 class="text-xl font-bold text-gray-900">Admin Panel</h1>
                <p class="text-sm text-gray-500">IPCR/OPCR Module</p>
            </div>
            <nav class="px-4 py-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-700 font-semibold">
                    <i class="fas fa-home w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
                    <i class="fas fa-users w-5"></i>
                    <span>User Management</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="pt-4">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition w-full">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white border-b">
                <div class="px-6 sm:px-8 py-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Administrator Dashboard</h2>
                        <p class="text-sm text-gray-500">IPCR and OPCR Management System</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover">
                    </div>
                </div>
            </header>

            <main class="px-6 sm:px-8 py-6">
                <!-- Welcome Banner -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-500 rounded-lg shadow p-6 sm:p-8 text-white">
                    <h3 class="text-2xl sm:text-3xl font-bold">Welcome, {{ explode(' ', auth()->user()->name)[0] }}!</h3>
                    <p class="text-sm sm:text-base text-blue-100 mt-2">Manage the IPCR/OPCR system from the admin panel</p>
                </div>

                <!-- Quick Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mt-6">
                    <div class="bg-white rounded-lg shadow p-5 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Total Users</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Active Users</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::where('is_active', true)->count() }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Departments</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Department::count() }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-building text-purple-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Feature Cards -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="bg-blue-500 h-40 flex items-center justify-center">
                            <div class="w-20 h-20 rounded-full bg-blue-400 flex items-center justify-center">
                                <i class="fas fa-users text-white text-3xl"></i>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900">User Management</h3>
                            <p class="text-sm text-gray-600 mt-2">Create, edit, view, and manage all user accounts in the system</p>
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-blue-600 font-semibold mt-4">
                                Go to User Management <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="bg-purple-500 h-40 flex items-center justify-center">
                            <div class="w-20 h-20 rounded-full bg-purple-400 flex items-center justify-center">
                                <i class="fas fa-tasks text-white text-3xl"></i>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900">Assign Task</h3>
                            <p class="text-sm text-gray-600 mt-2">Assign tasks and reviews to faculty members</p>
                            <button class="inline-flex items-center gap-2 text-purple-600 font-semibold mt-4">
                                Assign Tasks <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- IPCR Submissions Table -->
                <div class="bg-white rounded-lg shadow mt-6">
                    <div class="px-6 py-4 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">IPCR Submissions</h3>
                            <p class="text-xs text-gray-500">Recent submissions from faculty</p>
                        </div>
                        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                            <label for="departmentFilter" class="text-xs text-gray-600">Filter</label>
                            <select id="departmentFilter" name="department_id" class="border border-gray-300 rounded-lg px-2 sm:px-3 py-2 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ (string) $selectedDepartmentId === (string) $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs sm:text-sm">
                            <thead>
                                <tr class="text-left text-gray-600 border-b">
                                    <th class="py-3 px-6">Name</th>
                                    <th class="py-3 px-6">Department</th>
                                    <th class="py-3 px-6">School Year</th>
                                    <th class="py-3 px-6">Semester</th>
                                    <th class="py-3 px-6">Submitted</th>
                                    <th class="py-3 px-6">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($submissions as $submission)
                                    <tr class="border-b last:border-b-0 hover:bg-gray-50">
                                        <td class="py-3 px-6 text-gray-900 font-medium">{{ $submission->user->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-6 text-gray-700">{{ $submission->user->department->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-6 text-gray-700">{{ $submission->school_year }}</td>
                                        <td class="py-3 px-6 text-gray-700">{{ $submission->semester }}</td>
                                        <td class="py-3 px-6 text-gray-700">{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y') : 'N/A' }}</td>
                                        <td class="py-3 px-6">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                                {{ ucfirst($submission->status ?? 'submitted') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 text-center text-gray-500">No IPCR submissions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>