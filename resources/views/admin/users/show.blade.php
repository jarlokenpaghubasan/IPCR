<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="text-gray-600 text-sm">View User</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="flex items-center gap-4">
                @csrf
                <span class="text-gray-600">Welcome, {{ auth()->user()->name }}</span>
                <button type="submit" class="text-red-600 hover:text-red-700 font-semibold">Logout</button>
            </form>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-8">
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-900 mb-6 inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>

            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600 mt-1">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($user->role === 'admin') bg-purple-100 text-purple-800
                            @elseif($user->role === 'director') bg-green-100 text-green-800
                            @elseif($user->role === 'dean') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800
                            @endif
                        ">
                            {{ ucfirst($user->role) }}
                        </span>
                    </p>
                </div>
                <div class="space-x-3">
                    <a href="{{ route('admin.users.edit', $user) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold inline-flex items-center gap-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @if(auth()->user()->id !== $user->id)
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold inline-flex items-center gap-2">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Profile Photo -->
            <div class="mb-8 pb-8 border-b">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Photo</h3>
                @if($user->hasProfilePhoto())
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-64 h-64 object-cover rounded-lg border-4 border-blue-500 shadow-lg">
                @else
                    <div class="w-64 h-64 bg-gray-300 rounded-lg flex items-center justify-center border-4 border-dashed border-gray-400">
                        <i class="fas fa-user text-gray-500 text-6xl"></i>
                    </div>
                @endif
            </div>

            <!-- Personal Information Section -->
            <div class="border-b pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Full Name</label>
                        <p class="text-gray-900 mt-1">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Email</label>
                        <p class="text-gray-900 mt-1">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Username</label>
                        <p class="text-gray-900 mt-1">{{ $user->username }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Phone</label>
                        <p class="text-gray-900 mt-1">{{ $user->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Account Information Section -->
            <div class="border-b pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Role</label>
                        <p class="text-gray-900 mt-1">{{ ucfirst($user->role) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Status</label>
                        <p class="mt-1">
                            @if($user->is_active)
                                <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Department & Designation Section -->
            <div class="border-b pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Department & Designation</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Department</label>
                        <p class="text-gray-900 mt-1">{{ $user->department?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Designation</label>
                        <p class="text-gray-900 mt-1">{{ $user->designation?->title ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- User Photos Gallery -->
            <div class="border-b pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Photo Gallery</h3>
                @if($user->photos->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($user->photos as $photo)
                            <div class="relative group">
                                <img src="{{ $photo->photo_url }}" alt="User photo" class="w-full aspect-square object-cover rounded-lg border-2 {{ $photo->is_profile_photo ? 'border-blue-500' : 'border-gray-300' }} shadow hover:shadow-lg transition">
                                @if($photo->is_profile_photo)
                                    <div class="absolute top-2 right-2 bg-blue-500 text-white px-2 py-1 rounded text-xs font-semibold">
                                        <i class="fas fa-check mr-1"></i>Profile
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 rounded-lg transition flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                                    @if(!$photo->is_profile_photo)
                                        <a href="{{ route('admin.users.photo.setProfile', [$user, $photo]) }}" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition" title="Set as profile photo">
                                            <i class="fas fa-star"></i>
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.users.photo.delete', [$user, $photo]) }}" class="inline" onsubmit="return confirm('Delete this photo?');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition" title="Delete photo">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-100 rounded-lg p-8 text-center">
                        <i class="fas fa-image text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-600">No photos uploaded yet</p>
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900 mt-2 inline-block">
                            Go to edit page to upload photos
                        </a>
                    </div>
                @endif
            </div>

            <!-- System Information Section -->
            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">System Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Created</label>
                        <p class="text-gray-900 mt-1">{{ $user->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Last Updated</label>
                        <p class="text-gray-900 mt-1">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-8">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>
        </div>
    </div>
</body>
</html>