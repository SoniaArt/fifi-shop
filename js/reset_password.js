document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newPasswordForm');
    const result = document.getElementById('result');

    if (!form) return;

    const tokenElement = document.getElementById('tokenData');
    const token = tokenElement ? tokenElement.dataset.token : '';

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const password = form.querySelector('input[name="password"]');
        const confirm = form.querySelector('input[name="confirm"]');

        if (!password || !confirm) return;

        if (password.value.length < 6) {
            alert('Пароль должен быть не менее 6 символов');
            return;
        }

        if (password.value !== confirm.value) {
            alert('Пароли не совпадают');
            return;
        }

        try {
            const response = await fetch('/FIFI/api/reset_password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'reset',
                    token: token,
                    password: password.value,
                    confirm: confirm.value
                })
            });

            const data = await response.json();

            if (!data.success) {
                alert(data.error);
                return;
            }

            result.innerHTML = `
                <div class="alert alert-success text-center">
                    Пароль успешно изменён.<br><br>
                    Теперь можете войти в аккаунт.
                </div>
            `;

            form.remove();

        } catch (error) {
            alert('Ошибка соединения. Попробуйте позже.');
        }
    });
});