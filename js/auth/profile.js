export function initProfile() {
    const editBtn = document.getElementById('editProfile');
    const cancelEditBtn = document.getElementById('cancelEdit');
    const profileEditForm = document.getElementById('profileEditForm');
    
    if (editBtn) {
        editBtn.addEventListener('click', () => {
            $('.auth-panel').removeClass('active');
            $('#profileEditPanel').addClass('active');
        });
    }
    
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', () => {
            $('.auth-panel').removeClass('active');
            $('#profileView').addClass('active');
        });
    }
    
    if (profileEditForm) {
        profileEditForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const response = await fetch('/FIFI/api/update_profile.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    firstName: profileEditForm.firstName.value.trim(),
                    lastName: profileEditForm.lastName.value.trim(),
                    middleName: profileEditForm.middleName.value.trim(),
                    email: profileEditForm.email.value.trim(),
                    phone: profileEditForm.phone.value.trim()
                })
            });
            
            const data = await response.json();
            
            if (!data.success) {
                window.showError(data.error);
                return;
            }
            
            window.reloadPage();
        });
    }
}