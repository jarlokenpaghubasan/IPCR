let pendingDeleteForm = null;
let currentViewUserId = null;

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
        window.closeViewUserModal();
        window.closeEditUserModal();
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

// Toggle Password Visibility (for Add User modal)
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

// ============================================
// VIEW USER MODAL
// ============================================

function getRoleBadgeClasses(role) {
    switch (role) {
        case 'admin': return 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300';
        case 'director': return 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300';
        case 'dean': return 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300';
        default: return 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300';
    }
}

window.openViewUserModal = function (userId) {
    currentViewUserId = userId;
    const modal = document.getElementById('viewUserModal');
    const loading = document.getElementById('viewUserLoading');
    const data = document.getElementById('viewUserData');

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    loading.classList.remove('hidden');
    data.classList.add('hidden');

    fetch(`/admin/panel/users/${userId}/json`)
        .then(response => response.json())
        .then(user => {
            // Populate header
            document.getElementById('viewUserPhoto').src = user.profile_photo_url;
            document.getElementById('viewUserPhoto').alt = user.name;
            document.getElementById('viewUserName').textContent = user.name;
            document.getElementById('viewUserEmployeeId').textContent = user.employee_id || 'No Employee ID';

            // Roles badges
            const rolesContainer = document.getElementById('viewUserRoles');
            rolesContainer.innerHTML = user.roles.map(role =>
                `<span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium ${getRoleBadgeClasses(role)}">${role.charAt(0).toUpperCase() + role.slice(1)}</span>`
            ).join('');

            // Personal info
            document.getElementById('viewUserFullName').textContent = user.name;
            document.getElementById('viewUserEmail').textContent = user.email;
            document.getElementById('viewUserUsername').textContent = user.username;
            document.getElementById('viewUserPhone').textContent = user.phone || 'N/A';

            // Account & Organization
            if (user.is_active) {
                document.getElementById('viewUserStatus').innerHTML = '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active</span>';
            } else {
                document.getElementById('viewUserStatus').innerHTML = '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Inactive</span>';
            }
            document.getElementById('viewUserDepartment').textContent = user.department_name || 'N/A';
            document.getElementById('viewUserDesignation').textContent = user.designation_name || 'N/A';

            // Show/hide Edit button based on whether this is the protected admin
            const editBtn = document.getElementById('viewToEditBtn');
            if (user.employee_id === 'URS26-ADM00001') {
                editBtn.classList.add('hidden');
            } else {
                editBtn.classList.remove('hidden');
            }

            loading.classList.add('hidden');
            data.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading user:', error);
            loading.innerHTML = '<p class="text-red-500 text-sm">Failed to load user data.</p>';
        });
};

window.closeViewUserModal = function () {
    document.getElementById('viewUserModal').classList.add('hidden');
    document.body.style.overflow = '';
    currentViewUserId = null;
    // Reset loading state for next open
    document.getElementById('viewUserLoading').innerHTML = '<div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>';
};

// Close view modal on outside click
document.getElementById('viewUserModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeViewUserModal();
});

// View to Edit transition button
document.getElementById('viewToEditBtn')?.addEventListener('click', function () {
    if (currentViewUserId) {
        closeViewUserModal();
        openEditUserModal(currentViewUserId);
    }
});

// ============================================
// EDIT USER MODAL
// ============================================

window.openEditUserModal = function (userId) {
    const modal = document.getElementById('editUserModal');
    const loading = document.getElementById('editUserLoading');
    const formWrapper = document.getElementById('editUserFormWrapper');
    const errorsDiv = document.getElementById('editModalErrors');

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    loading.classList.remove('hidden');
    formWrapper.classList.add('hidden');
    errorsDiv.classList.add('hidden');

    fetch(`/admin/panel/users/${userId}/json`)
        .then(response => response.json())
        .then(user => {
            // Set form action
            document.getElementById('editUserForm').action = `/admin/panel/users/${user.id}`;
            document.getElementById('editUserSubtitle').textContent = user.name;

            // Populate fields
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_phone').value = user.phone || '';
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_password_confirmation').value = '';

            // Status
            document.getElementById('edit_is_active').checked = user.is_active;

            // Roles
            document.querySelectorAll('.edit-role-checkbox').forEach(cb => {
                cb.checked = user.roles.includes(cb.value);
            });

            // Department & Designation
            document.getElementById('edit_department_id').value = user.department_id || '';
            document.getElementById('edit_designation_id').value = user.designation_id || '';

            // Handle role-based dept/desig visibility
            handleEditRoleSelection();

            loading.classList.add('hidden');
            formWrapper.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading user:', error);
            loading.innerHTML = '<p class="text-red-500 text-sm">Failed to load user data.</p>';
        });
};

window.closeEditUserModal = function () {
    document.getElementById('editUserModal').classList.add('hidden');
    document.body.style.overflow = '';
    // Reset loading state
    document.getElementById('editUserLoading').innerHTML = '<div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>';
    document.getElementById('editUserLoading').classList.remove('hidden');
    document.getElementById('editUserFormWrapper').classList.add('hidden');
};

// Close edit modal on outside click
document.getElementById('editUserModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeEditUserModal();
});

// Handle role selection to show/hide department and designation in edit modal
window.handleEditRoleSelection = function () {
    const hrCheckbox = document.getElementById('edit_role_hr');
    const directorCheckbox = document.getElementById('edit_role_director');
    const deptSection = document.getElementById('editDeptSection');

    if (!deptSection) return;

    const isHrOrDirector = (hrCheckbox && hrCheckbox.checked) || (directorCheckbox && directorCheckbox.checked);

    if (isHrOrDirector) {
        deptSection.style.display = 'none';
        document.getElementById('edit_department_id').value = '';
        document.getElementById('edit_designation_id').value = '';
    } else {
        deptSection.style.display = '';
    }
};

// Toggle Password Visibility for Edit modal
window.toggleEditPasswordVisibility = function (fieldId) {
    const input = document.getElementById(fieldId);
    const eyeOpen = document.getElementById(fieldId + '_eye_open');
    const eyeClosed = document.getElementById(fieldId + '_eye_closed');

    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen?.classList.add('hidden');
        eyeClosed?.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeOpen?.classList.remove('hidden');
        eyeClosed?.classList.add('hidden');
    }
};
