// Toggle password visibility
window.togglePasswordVisibility = function(fieldId) {
    const input = document.getElementById(fieldId);
    if (!input) return;
    
    const eyeOpen = document.getElementById(fieldId + '_eye_open');
    const eyeClosed = document.getElementById(fieldId + '_eye_closed');
    
    if (input.type === 'password') {
        input.type = 'text';
        if (eyeOpen) eyeOpen.classList.add('hidden');
        if (eyeClosed) eyeClosed.classList.remove('hidden');
    } else {
        input.type = 'password';
        if (eyeOpen) eyeOpen.classList.remove('hidden');
        if (eyeClosed) eyeClosed.classList.add('hidden');
    }
};

// Handle role selection to show/hide department and designation
window.handleRoleSelection = function() {
    const hrCheckbox = document.getElementById('role_hr');
    const directorCheckbox = document.getElementById('role_director');
    const departmentField = document.getElementById('department_id');
    const designationField = document.getElementById('designation_id');
    
    if (!departmentField || !designationField) return;
    
    const isHrOrDirector = (hrCheckbox && hrCheckbox.checked) || (directorCheckbox && directorCheckbox.checked);
    
    // Get the parent divs
    const departmentDiv = departmentField.closest('.grid').querySelector('div:nth-child(1)');
    const designationDiv = departmentField.closest('.grid').querySelector('div:nth-child(2)');
    
    if (isHrOrDirector) {
        // Hide department and designation fields
        if (departmentDiv) departmentDiv.style.display = 'none';
        if (designationDiv) designationDiv.style.display = 'none';
        
        // Clear their values
        departmentField.value = '';
        designationField.value = '';
    } else {
        // Show department and designation fields
        if (departmentDiv) departmentDiv.style.display = 'block';
        if (designationDiv) designationDiv.style.display = 'block';
    }
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    handleRoleSelection();
});
