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

// Toggle password visibility
window.togglePasswordVisibility = function(fieldId) {
    const input = document.getElementById(fieldId);
    const eyeOpen = document.getElementById(fieldId + '_eye_open');
    const eyeClosed = document.getElementById(fieldId + '_eye_closed');
    
    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
};

// Change Password Modal Functions
window.openChangePasswordModal = function() {
    document.getElementById('changePasswordModal').classList.remove('hidden');
    // Reset form
    document.getElementById('changePasswordForm').reset();
    // Reset all password fields to hidden state
    ['current_password', 'new_password', 'new_password_confirmation'].forEach(fieldId => {
        const input = document.getElementById(fieldId);
        const eyeOpen = document.getElementById(fieldId + '_eye_open');
        const eyeClosed = document.getElementById(fieldId + '_eye_closed');
        input.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    });
    // Clear any previous messages
    document.getElementById('passwordMessage').classList.add('hidden');
    // Clear error messages
    ['current_password_error', 'new_password_error', 'new_password_confirmation_error'].forEach(id => {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).textContent = '';
    });
};

window.closeChangePasswordModal = function() {
    document.getElementById('changePasswordModal').classList.add('hidden');
};

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('changePasswordModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                window.closeChangePasswordModal();
            }
        });
    }

    // Handle form submission
    const form = document.getElementById('changePasswordForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('passwordMessage');
            
            // Clear previous errors
            ['current_password_error', 'new_password_error', 'new_password_confirmation_error'].forEach(id => {
                document.getElementById(id).classList.add('hidden');
                document.getElementById(id).textContent = '';
            });
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Success
                    messageDiv.className = 'rounded-lg p-3 text-sm bg-green-50 text-green-800 border border-green-200';
                    messageDiv.textContent = data.message || 'Password updated successfully!';
                    messageDiv.classList.remove('hidden');
                    
                    // Reset form and close after 2 seconds
                    setTimeout(() => {
                        window.closeChangePasswordModal();
                        this.reset();
                    }, 2000);
                } else {
                    // Error
                    if (data.errors) {
                        // Display validation errors
                        Object.keys(data.errors).forEach(key => {
                            const errorElement = document.getElementById(key + '_error');
                            if (errorElement) {
                                errorElement.textContent = data.errors[key][0];
                                errorElement.classList.remove('hidden');
                            }
                        });
                    } else {
                        messageDiv.className = 'rounded-lg p-3 text-sm bg-red-50 text-red-800 border border-red-200';
                        messageDiv.textContent = data.message || 'An error occurred. Please try again.';
                        messageDiv.classList.remove('hidden');
                    }
                }
            } catch (error) {
                messageDiv.className = 'rounded-lg p-3 text-sm bg-red-50 text-red-800 border border-red-200';
                messageDiv.textContent = 'An error occurred. Please try again.';
                messageDiv.classList.remove('hidden');
            }
        });
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        window.closeChangePasswordModal();
        window.closeEditProfileModal();
    }
});

// Photo Management
const photoInput = document.getElementById('photoInput');
const uploadForm = document.getElementById('photoUploadForm');
const uploadProgress = document.getElementById('uploadProgress');
const progressBar = document.getElementById('progressBar');
const uploadMessage = document.getElementById('uploadMessage');
const uploadText = document.getElementById('uploadText');

let pendingPhotoId = null;

// Photo input change event
if (photoInput) {
    photoInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            uploadText.textContent = this.files[0].name;
            uploadPhoto(this.files[0]);
        }
    });
}

// Upload photo via AJAX
function uploadPhoto(file) {
    const formData = new FormData();
    formData.append('photo', file);
    formData.append('_token', document.querySelector('input[name="_token"]').value);

    uploadProgress.classList.remove('hidden');
    uploadMessage.innerHTML = '';

    fetch('/faculty/profile/photo/upload', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        uploadProgress.classList.add('hidden');
        if (data.success) {
            uploadMessage.innerHTML = '<span class=\"text-green-600 text-xs\"><i class=\"fas fa-check-circle mr-1\"></i>' + data.message + '</span>';
            photoInput.value = '';
            uploadText.textContent = 'Choose Photo';
            loadPhotos();
        } else {
            uploadMessage.innerHTML = '<span class=\"text-red-600 text-xs\"><i class=\"fas fa-times-circle mr-1\"></i>' + data.message + '</span>';
        }
    })
    .catch(error => {
        uploadProgress.classList.add('hidden');
        uploadMessage.innerHTML = '<span class=\"text-red-600 text-xs\"><i class=\"fas fa-times-circle mr-1\"></i>Upload failed</span>';
        console.error('Error:', error);
    });
}

// Load all photos
function loadPhotos() {
    fetch('/faculty/profile/photos')
        .then(response => response.json())
        .then(data => {
            const allPhotos = document.getElementById('allPhotos');
            const photoCount = document.getElementById('photoCount');
            photoCount.textContent = data.photos.length;
            
            if (data.photos.length === 0) {
                allPhotos.innerHTML = '<p class=\"text-xs text-gray-500 col-span-3 text-center py-4\">No photos uploaded</p>';
                return;
            }

            allPhotos.innerHTML = '';
            data.photos.forEach(photo => {
                const photoDiv = document.createElement('div');
                photoDiv.className = 'relative group';
                photoDiv.innerHTML = `
                    <img src="${photo.url}" alt="Profile photo" class="w-full aspect-square object-cover rounded cursor-pointer">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition rounded flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100">
                        <button onclick="openSetProfileModal(${photo.id})" class="bg-blue-600 hover:bg-blue-700 text-white p-1.5 rounded text-xs" title="Set as profile">
                            <i class="fas fa-check"></i>
                        </button>
                        <button onclick="openDeletePhotoModal(${photo.id})" class="bg-red-600 hover:bg-red-700 text-white p-1.5 rounded text-xs" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    ${photo.is_profile ? '<div class="absolute top-1 right-1 bg-green-500 text-white px-1.5 py-0.5 rounded text-xs"><i class="fas fa-check"></i></div>' : ''}
                `;
                allPhotos.appendChild(photoDiv);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Set as profile modal
window.openSetProfileModal = function(photoId) {
    pendingPhotoId = photoId;
    document.getElementById('setProfileModal').classList.remove('hidden');
};

window.closeSetProfileModal = function() {
    document.getElementById('setProfileModal').classList.add('hidden');
    pendingPhotoId = null;
};

window.confirmSetProfile = function() {
    if (!pendingPhotoId) return;

    fetch('/faculty/profile/photo/set-profile', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name=\"_token\"]').value
        },
        body: JSON.stringify({ photo_id: pendingPhotoId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeSetProfileModal();
            window.location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
};

// Delete photo modal
window.openDeletePhotoModal = function(photoId) {
    pendingPhotoId = photoId;
    document.getElementById('deletePhotoModal').classList.remove('hidden');
};

window.closeDeletePhotoModal = function() {
    document.getElementById('deletePhotoModal').classList.add('hidden');
    pendingPhotoId = null;
};

window.confirmDeletePhoto = function() {
    if (!pendingPhotoId) return;

    fetch(`/faculty/profile/photo/${pendingPhotoId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name=\"_token\"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeDeletePhotoModal();
            loadPhotos();
        }
    })
    .catch(error => console.error('Error:', error));
};

// Load photos on page load
if (document.getElementById('allPhotos')) {
    loadPhotos();
}

// Edit Profile Modal Functions
window.openEditProfileModal = function() {
    document.getElementById('editProfileModal').classList.remove('hidden');
    // Clear any previous messages
    document.getElementById('editProfileMessage').classList.add('hidden');
    // Clear error messages
    ['edit_name_error', 'edit_email_error', 'edit_username_error', 'edit_phone_error', 'edit_department_id_error', 'edit_designation_id_error'].forEach(id => {
        const errorElement = document.getElementById(id);
        if (errorElement) {
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
        }
    });
};

window.closeEditProfileModal = function() {
    document.getElementById('editProfileModal').classList.add('hidden');
};

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editProfileModal');
    if (editModal) {
        editModal.addEventListener('click', function(e) {
            if (e.target === this) {
                window.closeEditProfileModal();
            }
        });
    }

    // Handle edit profile form submission
    const editForm = document.getElementById('editProfileForm');
    if (editForm) {
        editForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('editProfileMessage');
            const actionUrl = this.dataset.action;
            
            // Clear previous errors
            ['edit_name_error', 'edit_email_error', 'edit_username_error', 'edit_phone_error', 'edit_department_id_error', 'edit_designation_id_error'].forEach(id => {
                const errorElement = document.getElementById(id);
                if (errorElement) {
                    errorElement.classList.add('hidden');
                    errorElement.textContent = '';
                }
            });
            
            try {
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Success
                    messageDiv.className = 'rounded-lg p-3 text-sm bg-green-50 text-green-800 border border-green-200';
                    messageDiv.innerHTML = '<i class="fas fa-check-circle mr-2"></i>' + (data.message || 'Profile updated successfully!');
                    messageDiv.classList.remove('hidden');
                    
                    // Close modal and reload page after 2 seconds
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    // Error
                    if (data.errors) {
                        // Display validation errors
                        Object.keys(data.errors).forEach(key => {
                            const errorElement = document.getElementById('edit_' + key + '_error');
                            if (errorElement) {
                                errorElement.textContent = data.errors[key][0];
                                errorElement.classList.remove('hidden');
                            }
                        });
                    } else {
                        messageDiv.className = 'rounded-lg p-3 text-sm bg-red-50 text-red-800 border border-red-200';
                        messageDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>' + (data.message || 'An error occurred. Please try again.');
                        messageDiv.classList.remove('hidden');
                    }
                }
            } catch (error) {
                messageDiv.className = 'rounded-lg p-3 text-sm bg-red-50 text-red-800 border border-red-200';
                messageDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>An error occurred. Please try again.';
                messageDiv.classList.remove('hidden');
                console.error('Error:', error);
            }
        });
    }
});

// Modal photo upload functionality with crop
let cropper = null;
let currentFile = null;

document.addEventListener('DOMContentLoaded', function() {
    const modalPhotoInput = document.getElementById('modalPhotoInput');
    
    if (modalPhotoInput) {
        modalPhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                showModalMessage('File size must be less than 5MB', 'error');
                this.value = '';
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                showModalMessage('Please upload a valid image file (JPEG, PNG, GIF, WebP)', 'error');
                this.value = '';
                return;
            }

            // Store file and open crop modal
            currentFile = file;
            openCropModal(file);
        });
    }
});

window.openCropModal = function(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const cropImage = document.getElementById('cropImage');
        cropImage.src = e.target.result;
        
        // Show modal
        document.getElementById('cropModal').classList.remove('hidden');
        
        // Initialize cropper after a short delay to ensure image is loaded
        setTimeout(() => {
            if (cropper) {
                cropper.destroy();
            }
            
            cropper = new Cropper(cropImage, {
                aspectRatio: 1, // Square crop for profile picture
                viewMode: 2,
                dragMode: 'move',
                autoCropArea: 0.8,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                minContainerWidth: 200,
                minContainerHeight: 200,
            });
        }, 100);
    };
    reader.readAsDataURL(file);
};

window.closeCropModal = function() {
    document.getElementById('cropModal').classList.add('hidden');
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    // Reset file input
    const modalPhotoInput = document.getElementById('modalPhotoInput');
    if (modalPhotoInput) {
        modalPhotoInput.value = '';
    }
    currentFile = null;
};

window.cropperZoomIn = function() {
    if (cropper) {
        cropper.zoom(0.1);
    }
};

window.cropperZoomOut = function() {
    if (cropper) {
        cropper.zoom(-0.1);
    }
};

window.cropperRotateLeft = function() {
    if (cropper) {
        cropper.rotate(-90);
    }
};

window.cropperRotateRight = function() {
    if (cropper) {
        cropper.rotate(90);
    }
};

window.cropperReset = function() {
    if (cropper) {
        cropper.reset();
    }
};

window.applyCropAndUpload = function() {
    if (!cropper) return;
    
    // Get cropped canvas
    const canvas = cropper.getCroppedCanvas({
        width: 400,
        height: 400,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    });
    
    // Convert canvas to blob
    canvas.toBlob(function(blob) {
        if (!blob) {
            showModalMessage('Failed to process image', 'error');
            return;
        }
        
        // Create a file from blob
        const croppedFile = new File([blob], currentFile.name, {
            type: currentFile.type,
            lastModified: Date.now(),
        });
        
        // Close crop modal
        closeCropModal();
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('modalPhotoPreview');
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(croppedFile);
        
        // Upload the cropped photo
        uploadModalPhoto(croppedFile);
    }, currentFile.type, 0.9);
}

function uploadModalPhoto(file) {
    const formData = new FormData();
    formData.append('photo', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]').value);

    const progressContainer = document.getElementById('modalUploadProgress');
    const progressBar = document.getElementById('modalProgressBar');

    progressContainer.classList.remove('hidden');
    progressBar.style.width = '0%';

    fetch('/faculty/profile/photo/upload', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        progressBar.style.width = '100%';
        setTimeout(() => {
            progressContainer.classList.add('hidden');
            if (data.success) {
                showModalMessage(data.message, 'success');
                // Update all profile images on the page
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showModalMessage(data.message || 'Failed to upload photo', 'error');
            }
        }, 500);
    })
    .catch(error => {
        progressContainer.classList.add('hidden');
        showModalMessage('An error occurred while uploading', 'error');
        console.error('Upload error:', error);
    });
}

function showModalMessage(message, type) {
    const messageDiv = document.getElementById('modalUploadMessage');
    messageDiv.textContent = message;
    messageDiv.className = type === 'success' ? 'text-xs mt-1 text-green-600' : 'text-xs mt-1 text-red-600';
    
    setTimeout(() => {
        messageDiv.textContent = '';
        messageDiv.className = 'text-xs mt-1';
    }, 3000);
}
