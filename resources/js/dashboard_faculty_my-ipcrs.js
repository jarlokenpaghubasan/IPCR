// Mobile menu toggle
window.toggleMobileMenu = function() {
    const menu = document.querySelector('.mobile-menu');
    const overlay = document.querySelector('.mobile-menu-overlay');
    menu.classList.toggle('active');
    overlay.classList.toggle('active');
};

// Notification popup toggle for desktop
window.toggleNotificationPopup = function() {
    const popup = document.getElementById('notificationPopup');
    popup.classList.toggle('active');
};

// Notification popup toggle for mobile
window.toggleNotificationPopupMobile = function() {
    const popup = document.getElementById('notificationPopupMobile');
    popup.classList.toggle('active');
};

// Close notification popups when clicking outside
document.addEventListener('click', function(e) {
    const popup = document.getElementById('notificationPopup');
    const popupMobile = document.getElementById('notificationPopupMobile');
    const notificationBtn = e.target.closest('button[onclick*="toggleNotificationPopup"]');
    
    if (!notificationBtn) {
        if (popup && !popup.contains(e.target)) {
            popup.classList.remove('active');
        }
        if (popupMobile && !popupMobile.contains(e.target)) {
            popupMobile.classList.remove('active');
        }
    }
});

// Create IPCR modal controls
window.openCreateIpcrModal = function() {
    const modal = document.getElementById('createIpcrModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
};

window.closeCreateIpcrModal = function() {
    const modal = document.getElementById('createIpcrModal');
    if (modal) {
        modal.classList.add('hidden');
    }
};
// Create OPCR modal controls
window.openCreateOpcrModal = function() {
    console.log('openCreateOpcrModal called');
    const modal = document.getElementById('createOpcrModal');
    console.log('Modal element:', modal);
    if (modal) {
        modal.classList.remove('hidden');
    } else {
        console.error('createOpcrModal not found in DOM');
    }
};

window.closeCreateOpcrModal = function() {
    const modal = document.getElementById('createOpcrModal');
    if (modal) {
        modal.classList.add('hidden');
    }
};

// Tab switching function
window.switchTab = function(tab) {
    const ipcrTab = document.getElementById('ipcrTab');
    const opcrTab = document.getElementById('opcrTab');
    const ipcrButtonArea = document.getElementById('createIpcrButtonArea');
    const opcrButtonArea = document.getElementById('createOpcrButtonArea');
    const ipcrSavedSection = document.getElementById('ipcrSavedCopiesSection');
    const opcrSavedSection = document.getElementById('opcrSavedCopiesSection');
    const opcrTemplatesSection = document.getElementById('opcrTemplatesSection');
    const ipcrTemplatesSection = document.querySelector('.bg-white.rounded-lg.shadow-sm.p-4.sm\\:p-6:has(#templatesContainer)');
    const submitIpcrSection = document.getElementById('submitIpcrSection');
    const submitOpcrSection = document.getElementById('submitOpcrSection');
    
    if (tab === 'ipcr') {
        // Activate IPCR tab
        ipcrTab.classList.remove('border-transparent', 'text-gray-500');
        ipcrTab.classList.add('border-blue-600', 'text-blue-600');
        
        // Deactivate OPCR tab
        if (opcrTab) {
            opcrTab.classList.remove('border-blue-600', 'text-blue-600');
            opcrTab.classList.add('border-transparent', 'text-gray-500');
        }
        
        // Show IPCR button area, hide OPCR
        if (ipcrButtonArea) {
            ipcrButtonArea.classList.remove('hidden');
            ipcrButtonArea.style.display = 'flex';
        }
        if (opcrButtonArea) {
            opcrButtonArea.classList.add('hidden');
            opcrButtonArea.style.display = 'none';
        }

        // Show IPCR saved copies, hide OPCR saved copies
        if (ipcrSavedSection) ipcrSavedSection.classList.remove('hidden');
        if (opcrSavedSection) opcrSavedSection.classList.add('hidden');

        // Show IPCR templates, hide OPCR templates
        if (ipcrTemplatesSection) ipcrTemplatesSection.classList.remove('hidden');
        if (opcrTemplatesSection) opcrTemplatesSection.classList.add('hidden');

        // Show IPCR submit, hide OPCR submit
        if (submitIpcrSection) submitIpcrSection.classList.remove('hidden');
        if (submitOpcrSection) submitOpcrSection.classList.add('hidden');
    } else if (tab === 'opcr') {
        // Activate OPCR tab
        if (opcrTab) {
            opcrTab.classList.remove('border-transparent', 'text-gray-500');
            opcrTab.classList.add('border-blue-600', 'text-blue-600');
        }
        
        // Deactivate IPCR tab
        ipcrTab.classList.remove('border-blue-600', 'text-blue-600');
        ipcrTab.classList.add('border-transparent', 'text-gray-500');
        
        // Show OPCR button area, hide IPCR
        if (ipcrButtonArea) {
            ipcrButtonArea.classList.add('hidden');
            ipcrButtonArea.style.display = 'none';
        }
        if (opcrButtonArea) {
            opcrButtonArea.classList.remove('hidden');
            opcrButtonArea.style.display = 'flex';
        }

        // Show OPCR saved copies, hide IPCR saved copies
        if (ipcrSavedSection) ipcrSavedSection.classList.add('hidden');
        if (opcrSavedSection) opcrSavedSection.classList.remove('hidden');

        // Hide IPCR templates, show OPCR templates
        if (ipcrTemplatesSection) ipcrTemplatesSection.classList.add('hidden');
        if (opcrTemplatesSection) opcrTemplatesSection.classList.remove('hidden');

        // Hide IPCR submit, show OPCR submit
        if (submitIpcrSection) submitIpcrSection.classList.add('hidden');
        if (submitOpcrSection) submitOpcrSection.classList.remove('hidden');
        
        // Fetch and render OPCR templates
        if (window.renderOpcrTemplates) {
            window.renderOpcrTemplates();
        }
    }
};