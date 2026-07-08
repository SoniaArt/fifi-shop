import { initLogin } from './auth/login.js';
import { initRegister } from './auth/register.js';
import { initLogout } from './auth/logout.js';
import { initProfile } from './auth/profile.js';
import { initRequestReset } from './auth/request-reset.js';
import { initChangePassword } from './auth/change-password.js';

document.addEventListener('DOMContentLoaded', function() {
    initLogin();
    initRegister();
    initLogout();
    initProfile();
    initRequestReset();
    initChangePassword();
});