export function initRegister() {
    const registerForm = document.getElementById('registerForm');
    
    if (!registerForm) return;
    
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if (registerForm.password.value !== registerForm.password2.value) {
            window.showError('Пароли не совпадают');
            return;
        }
        
        const response = await fetch('/FIFI/api/register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                email: registerForm.email.value.trim(),
                phone: registerForm.phone.value.trim(),
                firstName: registerForm.firstName.value.trim(),
                lastName: registerForm.lastName.value.trim(),
                middleName: registerForm.middleName.value.trim(),
                password: registerForm.password.value
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