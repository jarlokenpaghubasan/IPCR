<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - IPCR/OPCR Module</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/urs_logo.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/dashboard_admin_index.css', 'resources/js/dashboard_admin_index.js'])
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Desktop Sidebar (hidden on mobile) -->
        <div class="w-64 bg-white shadow-lg hidden lg:block">
            <div class="p-6 border-b">
                <h1 class="text-xl font-bold text-gray-900">Admin Panel</h1>
                <p class="text-sm text-gray-600">IPCR/OPCR Module</p>
            </div>

            <nav class="p-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-semibold">
                    <i class="fas fa-home w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="fas fa-users w-5"></i>
                    <span>User Management</span>
                </a>

                <hr class="my-4">

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition w-full">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 opacity-0 pointer-events-none lg:hidden"></div>

        <!-- Mobile Sidebar -->
        <div id="sidebar" class="fixed lg:hidden inset-y-0 left-0 w-64 bg-white shadow-lg z-40 -translate-x-full">
            <div class="p-6 border-b flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Admin Panel</h1>
                    <p class="text-sm text-gray-600">IPCR/OPCR Module</p>
                </div>
                <button onclick="toggleSidebar()" class="p-2 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-times text-gray-700 text-xl"></i>
                </button>
            </div>

            <nav class="p-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-semibold">
                    <i class="fas fa-home w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="fas fa-users w-5"></i>
                    <span>User Management</span>
                </a>

                <hr class="my-4">

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition w-full">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="px-4 sm:px-6 lg:px-8 py-3 sm:py-4 flex justify-between items-center gap-3 sm:gap-4">
                    <!-- Hamburger (mobile/tablet only) -->
                    <button id="hamburgerBtn" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 flex-shrink-0">
                        <i class="fas fa-bars text-gray-700 text-xl"></i>
                    </button>

                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">Administrator Dashboard</h2>
                        <p class="text-gray-600 text-xs sm:text-sm">IPCR and OPCR Management System</p>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3 text-right whitespace-nowrap hidden sm:flex flex-shrink-0">
                        <div class="text-right hidden md:block">
                            <p class="text-gray-900 font-semibold text-sm">{{ auth()->user()->name }}</p>
                            <p class="text-gray-600 text-xs">Admin</p>
                        </div>
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover flex-shrink-0">
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
                                    <th class="py-3 px-6">Actions</th>
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
                                        <td class="py-3 px-6">
                                            @if(auth()->user() && auth()->user()->role === 'admin')
                                                <button onclick="adminDeleteSubmission({{ $submission->id }})" class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold py-1 px-3 rounded">
                                                    Delete
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-500">No IPCR submissions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function adminDeleteSubmission(submissionId) {
            console.log('Admin delete submission:', submissionId);
            
            if (confirm('Are you sure you want to delete this IPCR submission? This action cannot be undone.')) {
                try {
                    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfTokenElement) {
                        alert('Error: CSRF token not found');
                        console.error('CSRF token meta tag not found');
                        return;
                    }
                    
                    const csrfToken = csrfTokenElement.getAttribute('content');
                    console.log('CSRF token:', csrfToken ? 'found' : 'not found');
                    
                    const url = `/faculty/ipcr/submissions/${submissionId}`;
                    console.log('Sending DELETE request to:', url);
                    
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json().catch(() => ({ success: false, message: 'Invalid response' }));
                    })
                    .then(data => {
                        console.log('Delete response:', data);
                        if (data.success) {
                            alert('Submission deleted successfully.');
                            setTimeout(() => {
                                location.reload();
                            }, 500);
                        } else {
                            alert('Failed to delete submission: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Delete error:', error);
                        alert('An error occurred while deleting the submission: ' + error.message);
                    });
                } catch (error) {
                    console.error('Exception:', error);
                    alert('An error occurred: ' + error.message);
                }
            }
        }
    </script>
</body>
</html>