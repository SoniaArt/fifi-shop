export function initChangePassword(){
    const btn=document.getElementById('changePassword');
    const cancel=document.getElementById('cancelChangePassword');
    const form=document.getElementById('changePasswordForm');

    if(btn){
        btn.addEventListener('click',()=>{
            $('.auth-panel').removeClass('active');
            $('#changePasswordPanel').addClass('active');
        });
    }

    if(cancel){
        cancel.addEventListener('click',()=>{
            $('.auth-panel').removeClass('active');
            $('#profileView').addClass('active');

        });
    }

    if(form){
        form.addEventListener('submit', async e=>{
            e.preventDefault();

            const response=await fetch('/FIFI/api/change_password.php',{
                method:'POST',
                headers:{
                    'Content-Type':'application/json'
                },

                body:JSON.stringify({
                    currentPassword:
                    form.currentPassword.value,
                    newPassword:
                    form.newPassword.value,
                    confirmPassword:
                    form.confirmPassword.value
                })
            });

            const data=await response.json();

            if(!data.success){
                window.showError(data.error);
                return;
            }

            alert('Пароль успешно изменён');
            form.reset();

            $('.auth-panel').removeClass('active');
            $('#profileView').addClass('active');
        });
    }
}