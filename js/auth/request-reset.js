export function initRequestReset() {
    const resetForm = document.getElementById('resetForm');
    
    if (!resetForm) return;
    
    resetForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const response = await fetch('/FIFI/api/reset_password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'request',
                login: resetForm.login.value.trim()
            })
        });
        
        const data = await response.json();
        
        if (!data.success) {
            window.showError(data.error);
            return;
        }
        
        document.getElementById('resetResult').innerHTML = `
            <p>Ссылка для восстановления:</p>
            <a href="${data.link}" target="_blank" class="text-center mt-2" style="color: #1F1F1F">
                ОТКРЫТЬ СТРАНИЦУ СМЕНЫ ПАРОЛЯ
            </a>
        `;
    });
}