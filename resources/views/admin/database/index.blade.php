@extends('layouts.admin')

@section('title', 'Database Management')

@section('header')
    <div class="flex-1">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900">Database Management</h2>
        <p class="text-gray-500 text-xs">Backup & restore your database</p>
    </div>
    <div class="flex items-center gap-2">
        <form method="POST" action="{{ route('admin.database.backup') }}">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 text-sm transition shadow-sm">
                <i class="fas fa-download text-xs"></i> Create Backup
            </button>
        </form>
    </div>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">DB Size</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $dbSize }} MB</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                <i class="fas fa-database text-blue-500 text-sm"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Tables</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $tableCount }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center">
                <i class="fas fa-table text-emerald-500 text-sm"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Backups</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">{{ $backupCount }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center">
                <i class="fas fa-box-archive text-purple-500 text-sm"></i>
            </div>
        </div>
    </div>

    <!-- Upload Bar -->
    <div class="bg-white rounded-xl border border-gray-200 p-3 mb-5 flex flex-wrap items-center gap-2">
        <form action="{{ route('admin.database.upload') }}" method="POST" enctype="multipart/form-data" class="flex gap-2 flex-1 min-w-[200px]">
            @csrf
            <input type="file" name="sql_file" accept=".sql" class="block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-lg file:border-0
                file:text-sm file:font-semibold
                file:bg-blue-50 file:text-blue-700
                hover:file:bg-blue-100
            " required>
            <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-2">
                <i class="fas fa-upload text-xs"></i> Upload
            </button>
        </form>
        <button onclick="openSettingsModal()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-2">
            <i class="fas fa-cog text-xs"></i> Settings
        </button>
    </div>

    <!-- Backups Table (Desktop) -->
    <div class="hidden md:block bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Filename</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Size</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($backups as $index => $backup)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-file-code text-blue-400 text-sm"></i>
                                <span class="font-medium text-gray-900 text-sm">{{ $backup['filename'] }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $backup['size_formatted'] }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $backup['created_at'] }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <!-- Download -->
                                <a href="{{ route('admin.database.download', $backup['filename']) }}" class="w-7 h-7 rounded-md flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition" title="Download">
                                    <i class="fas fa-download text-xs"></i>
                                </a>
                                <!-- Restore -->
                                <form method="POST" action="{{ route('admin.database.restore', $backup['filename']) }}" class="inline" style="margin:0;">
                                    @csrf
                                    <button type="button" onclick="openRestoreModal('{{ $backup['filename'] }}', this.form)" class="w-7 h-7 rounded-md flex items-center justify-center text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition" title="Restore">
                                        <i class="fas fa-rotate-left text-xs"></i>
                                    </button>
                                </form>
                                <!-- Delete -->
                                <form method="POST" action="{{ route('admin.database.delete', $backup['filename']) }}" class="inline" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="openDeleteModal('{{ $backup['filename'] }}', this.form)" class="w-7 h-7 rounded-md flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition" title="Delete">
                                        <i class="fas fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-gray-400 text-sm">
                            <i class="fas fa-box-open text-3xl mb-3 block text-gray-300"></i>
                            No backups yet. <button onclick="document.querySelector('form[action*=backup]').submit()" class="text-blue-600 hover:underline">Create one now</button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-2">
        @forelse($backups as $backup)
            <div class="bg-white rounded-xl border border-gray-200 p-3 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-code text-blue-400 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $backup['filename'] }}</p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-[10px] text-gray-500">{{ $backup['size_formatted'] }}</span>
                        <span class="text-[10px] text-gray-400">•</span>
                        <span class="text-[10px] text-gray-500">{{ $backup['created_at'] }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-0.5 flex-shrink-0">
                    <a href="{{ route('admin.database.download', $backup['filename']) }}" class="w-7 h-7 rounded-md flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition">
                        <i class="fas fa-download text-xs"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.database.restore', $backup['filename']) }}" class="inline" style="margin:0;">
                        @csrf
                        <button type="button" onclick="openRestoreModal('{{ $backup['filename'] }}', this.form)" class="w-7 h-7 rounded-md flex items-center justify-center text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition">
                            <i class="fas fa-rotate-left text-xs"></i>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.database.delete', $backup['filename']) }}" class="inline" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="openDeleteModal('{{ $backup['filename'] }}', this.form)" class="w-7 h-7 rounded-md flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
                            <i class="fas fa-trash-can text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-center text-gray-400 text-sm">
                <i class="fas fa-box-open text-3xl mb-3 block text-gray-300"></i>
                No backups yet.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $backups->links() }}
    </div>

    <!-- DB Info Footer -->
    <div class="mt-4 text-xs text-gray-400 flex items-center gap-2">
        <i class="fas fa-circle-info"></i>
        <span>Database: <strong class="text-gray-600">{{ $dbName }}</strong> • Backups stored in <code class="bg-gray-100 px-1 py-0.5 rounded text-[10px]">storage/app/backups/</code></span>
    </div>
@endsection

@push('modals')
    <!-- Restore Confirmation Modal -->
    <div id="restoreModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4 animate-scale-in">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center">
                    <i class="fas fa-rotate-left text-amber-500"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Restore Database</h3>
                    <p class="text-xs text-gray-500">This will overwrite current data</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-5">
                Are you sure you want to restore the database from <strong id="restoreFileName" class="text-gray-900"></strong>? This action will replace all current data.
            </p>
            <div class="flex justify-end gap-2">
                <button onclick="closeRestoreModal()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">Cancel</button>
                <button onclick="confirmRestore()" class="px-4 py-2 text-sm bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-medium transition">Restore</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4 animate-scale-in">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center">
                    <i class="fas fa-trash-can text-red-500"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Delete Backup</h3>
                    <p class="text-xs text-gray-500">This cannot be undone</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-5">
                Are you sure you want to delete <strong id="deleteFileName" class="text-gray-900"></strong>?
            </p>
            <div class="flex justify-end gap-2">
                <button onclick="closeDeleteModal()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">Cancel</button>
                <button onclick="confirmDelete()" class="px-4 py-2 text-sm bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition">Delete</button>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div id="settingsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md mx-4 animate-scale-in">
            <div class="flex items-center justify-between mb-4 border-b pb-4">
                <h3 class="text-lg font-bold text-gray-900">Backup Settings</h3>
                <button onclick="closeSettingsModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.database.settings') }}" method="POST">
                @csrf
                
                <!-- Enable Toggle -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <label class="font-medium text-gray-900 block">Automatic Backups</label>
                        <p class="text-xs text-gray-500">Enable scheduled system backups</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enabled" class="sr-only peer" {{ ($settings['enabled'] ?? false) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="space-y-4 mb-6">
                    <!-- Frequency -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Frequency</label>
                        <select name="frequency" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="daily" {{ ($settings['frequency'] ?? '') == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ ($settings['frequency'] ?? '') == 'weekly' ? 'selected' : '' }}>Weekly (Every Monday)</option>
                            <option value="monthly" {{ ($settings['frequency'] ?? '') == 'monthly' ? 'selected' : '' }}>Monthly (1st of month)</option>
                        </select>
                    </div>

                    <!-- Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                        <input type="time" name="time" value="{{ $settings['time'] ?? '00:00' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeSettingsModal()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
@endpush
