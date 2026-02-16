import * as Turbo from "@hotwired/turbo";
Turbo.start();

// Sidebar toggle
window.toggleSidebar = function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('sidebar-hidden');
    overlay.classList.toggle('hidden');
};

// Initialize Sidebar & Global Listeners on Turbo Load
document.addEventListener('turbo:load', () => {
    // 1. Sidebar Logic
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (sidebar) {
        // Set initial state based on width
        if (window.innerWidth < 1280) {
            sidebar.classList.add('sidebar-hidden');
        } else {
            sidebar.classList.remove('sidebar-hidden');
            overlay.classList.add('hidden');
        }

        // Close sidebar when clicking a link (mobile only)
        document.querySelectorAll('#sidebar a, #sidebar button[type="submit"]').forEach(element => {
            element.addEventListener('click', () => {
                if (window.innerWidth < 1280) {
                    sidebar.classList.add('sidebar-hidden');
                    overlay.classList.add('hidden');
                }
            });
        });
    }

    // 2. User Management - Search & Filters
    const searchInput = document.getElementById('searchInput');
    const departmentFilter = document.getElementById('departmentFilter');
    const filterForm = document.getElementById('filterForm');

    if (searchInput && filterForm) {
        let searchTimeout = null;
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterForm.submit();
            }, 500);
        });

        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                clearTimeout(searchTimeout);
                filterForm.submit();
                e.preventDefault(); // Prevent default since we handle submit
            }
        });
    }

    if (departmentFilter && filterForm) {
        departmentFilter.addEventListener('change', function () {
            filterForm.submit();
        });
    }

    // 3. Modals (Re-attach close listeners on outside click/escape)
    // Note: Global open/close functions are defined below and don't need re-definition
});

// Handle window resize (Persistent listener)
window.addEventListener('resize', () => {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (!sidebar) return;

    if (window.innerWidth >= 1280) {
        sidebar.classList.remove('sidebar-hidden');
        overlay.classList.add('hidden');
    } else {
        sidebar.classList.add('sidebar-hidden');
        overlay.classList.add('hidden');
    }
});

// --- GLOBAL MODAL FUNCTIONS (users & database) ---

// User Management Actions
let pendingDeleteUserForm = null;
window.openConfirmationModal = function (userName, form) {
    document.getElementById('deleteUserName').textContent = userName;
    pendingDeleteUserForm = form;
    document.getElementById('confirmationModal').classList.remove('hidden');
};
window.closeConfirmationModal = function () {
    const modal = document.getElementById('confirmationModal');
    if (modal) modal.classList.add('hidden');
    pendingDeleteUserForm = null;
};
window.confirmDelete = function () { // Overloaded name, checking context
    if (pendingDeleteUserForm) {
        pendingDeleteUserForm.submit();
    } else if (typeof deleteBackupForm !== 'undefined' && deleteBackupForm) {
        // Fallback for database delete if they share the same function name
        deleteBackupForm.submit();
    }
    window.closeConfirmationModal();
    if (window.closeDeleteModal) window.closeDeleteModal(); // for database
};

// Add User Modal
window.openAddUserModal = function () {
    document.getElementById('addUserModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
};
window.closeAddUserModal = function () {
    document.getElementById('addUserModal').classList.add('hidden');
    document.body.style.overflow = '';
};

// Toggle Password Visibility
window.togglePasswordVisibility = function (fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const eyeOpen = document.getElementById(fieldId + '_eye_open');
    const eyeClosed = document.getElementById(fieldId + '_eye_closed');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeOpen?.classList.add('hidden');
        eyeClosed?.classList.remove('hidden');
    } else {
        passwordInput.type = 'password';
        eyeOpen?.classList.remove('hidden');
        eyeClosed?.classList.add('hidden');
    }
};

// Database Management Actions
let restoreBackupForm = null;
window.openRestoreModal = function (filename, form) {
    restoreBackupForm = form;
    document.getElementById('restoreFileName').textContent = filename;
    document.getElementById('restoreModal').classList.remove('hidden');
};
window.closeRestoreModal = function () {
    document.getElementById('restoreModal').classList.add('hidden');
    restoreBackupForm = null;
};
window.confirmRestore = function () {
    if (restoreBackupForm) restoreBackupForm.submit();
};

let deleteBackupForm = null;
window.openDeleteModal = function (filename, form) { // Specific to database
    deleteBackupForm = form;
    document.getElementById('deleteFileName').textContent = filename;
    document.getElementById('deleteModal').classList.remove('hidden');
};
window.closeDeleteModal = function () {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteBackupForm = null;
};
// confirmDelete is shared/handled above

window.openSettingsModal = function () {
    document.getElementById('settingsModal').classList.remove('hidden');
};
window.closeSettingsModal = function () {
    document.getElementById('settingsModal').classList.add('hidden');
};

// Global Event Delegation for Modal Close (Backdrop Click & Escape)
document.addEventListener('click', function (e) {
    // User Modals
    if (e.target.id === 'confirmationModal') window.closeConfirmationModal();
    if (e.target.id === 'addUserModal') window.closeAddUserModal();

    // Database Modals
    if (e.target.id === 'restoreModal') window.closeRestoreModal();
    if (e.target.id === 'deleteModal') window.closeDeleteModal();
    if (e.target.id === 'settingsModal') window.closeSettingsModal();
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        window.closeConfirmationModal();
        window.closeAddUserModal();
        if (window.closeRestoreModal) window.closeRestoreModal();
        if (window.closeDeleteModal) window.closeDeleteModal();
        if (window.closeSettingsModal) window.closeSettingsModal();
    }
});
