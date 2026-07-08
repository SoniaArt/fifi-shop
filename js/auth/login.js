export function initLogin() {
    const loginForm = document.getElementById('loginForm');
    
    if (!loginForm) return;
    
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const response = await fetch('/FIFI/api/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                login: loginForm.login.value.trim(),
                password: loginForm.password.value,
                remember: loginForm.remember.checked
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