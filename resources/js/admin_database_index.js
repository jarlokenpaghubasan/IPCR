// Restore confirmation modal
let restoreForm = null;
window.openRestoreModal = function (filename, form) {
    restoreForm = form;
    document.getElementById('restoreFileName').textContent = filename;
    document.getElementById('restoreModal').classList.remove('hidden');
};
window.closeRestoreModal = function () {
    document.getElementById('restoreModal').classList.add('hidden');
    restoreForm = null;
};
window.confirmRestore = function () {
    if (restoreForm) restoreForm.submit();
};

// Delete confirmation modal
let deleteForm = null;
window.openDeleteModal = function (filename, form) {
    deleteForm = form;
    document.getElementById('deleteFileName').textContent = filename;
    document.getElementById('deleteModal').classList.remove('hidden');
};
window.closeDeleteModal = function () {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteForm = null;
};
window.confirmDelete = function () {
    if (deleteForm) deleteForm.submit();
};

// Settings Modal
window.openSettingsModal = function () {
    document.getElementById('settingsModal').classList.remove('hidden');
};
window.closeSettingsModal = function () {
    document.getElementById('settingsModal').classList.add('hidden');
};

// Close modals on backdrop click
document.addEventListener('click', function (e) {
    const restoreModal = document.getElementById('restoreModal');
    const deleteModal = document.getElementById('deleteModal');
    const settingsModal = document.getElementById('settingsModal');

    if (e.target === restoreModal) closeRestoreModal();
    if (e.target === deleteModal) closeDeleteModal();
    if (e.target === settingsModal) closeSettingsModal();
});
