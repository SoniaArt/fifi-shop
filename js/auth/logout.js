export function initLogout() {
    const logoutBtn = document.getElementById('logoutBtn');
    
    if (!logoutBtn) return;
    
    logoutBtn.addEventListener('click', async () => {
        const response = await fetch('/FIFI/api/logout.php', {
            method: 'POST'
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.reloadPage();
        }
    });
}