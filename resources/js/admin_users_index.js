let pendingDeleteForm = null;

// Search and Filter Functionality (server-side)
const searchInput = document.getElementById('searchInput');
const departmentFilter = document.getElementById('departmentFilter');
const filterForm = document.getElementById('filterForm');

let searchTimeout = null;

// Debounced search — submits form after 500ms of no typing
searchInput.addEventListener('input', function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        filterForm.submit();
    }, 500);
});

// Submit on Enter key immediately
searchInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
        clearTimeout(searchTimeout);
        filterForm.submit();
    }
});

// Department filter — submit immediately on change
departmentFilter.addEventListener('change', function () {
    filterForm.submit();
});

// Confirmation modal functions
window.openConfirmationModal = function (userName, form) {
    document.getElementById('deleteUserName').textContent = userName;
    pendingDeleteForm = form;
    document.getElementById('confirmationModal').classList.remove('hidden');
};

window.closeConfirmationModal = function () {
    document.getElementById('confirmationModal').classList.add('hidden');
    pendingDeleteForm = null;
};

window.confirmDelete = function () {
    if (pendingDeleteForm) {
        pendingDeleteForm.submit();
    }
    window.closeConfirmationModal();
};

document.getElementById('confirmationModal').addEventListener('click', function (e) {
    if (e.target === this) {
        window.closeConfirmationModal();
    }
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        window.closeConfirmationModal();
        window.closeAddUserModal();
    }
});

// Add User Modal
window.openAddUserModal = function () {
    document.getElementById('addUserModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
};

window.closeAddUserModal = function () {
    document.getElementById('addUserModal').classList.add('hidden');
    document.body.style.overflow = ''; // Restore background scrolling
};

// Close modal on outside click
document.getElementById('addUserModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeAddUserModal();
});

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
