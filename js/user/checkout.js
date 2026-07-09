import { getBasketTotal, loadBasketItems } from './basket.js';

export function initCheckout() {
    const form = document.getElementById('checkoutForm');
    if (!form) return;

    loadBasketItems().then(() => {
        document.getElementById('checkoutTotal').textContent = 
            `${getBasketTotal().toLocaleString('ru-RU')} ₽`;
    });

    function validateField(input, isValid) {
        if (isValid) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            input.classList.remove('is-valid');
            if (input.value.trim().length > 0) {
                input.classList.add('is-invalid');
            }
        }
    }

    function validateCardExpiry(value) {
        if (value.length !== 5) return false;
        
        const parts = value.split('/');
        if (parts.length !== 2) return false;
        
        const month = parseInt(parts[0]);
        const year = parseInt(parts[1]);
        
        if (isNaN(month) || isNaN(year)) return false;
        if (month < 1 || month > 12) return false;
        
        const now = new Date();
        const currentYear = now.getFullYear() % 100;
        const currentMonth = now.getMonth() + 1;
        
        return (year > currentYear) || (year === currentYear && month >= currentMonth);
    }

    document.getElementById('cardNumber').oninput = function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 0) {
            value = value.match(/.{1,4}/g).join(' ');
        }
        this.value = value;
        
        const isValid = value.replace(/\s/g, '').length === 16;
        validateField(this, isValid);
    };

    document.getElementById('cardExpiry').oninput = function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        this.value = value;
        
        const isValid = validateCardExpiry(value);
        validateField(this, isValid);
        
        const errorEl = document.getElementById('cardExpiryError');
        if (value.length === 5 && !isValid) {
            errorEl.textContent = 'Карта просрочена или неверная дата';
            errorEl.style.display = 'block';
        } else {
            errorEl.textContent = 'Введите корректную дату (ММ/ГГ)';
            errorEl.style.display = value.length > 0 && !isValid ? 'block' : 'none';
        }
    };

    document.getElementById('cardCvv').oninput = function() {
        this.value = this.value.replace(/\D/g, '');
        const isValid = this.value.length === 3;
        validateField(this, isValid);
    };

    document.getElementById('cardHolder').oninput = function() {
        let val = this.value.replace(/[^A-Za-z\s]/g, '');
        val = val.toUpperCase();
        this.value = val;
        
        const words = val.trim().split(/\s+/).filter(w => w.length > 0);
        const isValid = words.length === 2 && words.every(w => w.length >= 2);
        
        validateField(this, isValid);
        
        const errorEl = document.getElementById('cardHolderError');
        if (val.length > 0 && !isValid) {
            if (words.length !== 2) {
                errorEl.textContent = 'Введите имя и фамилию (ровно 2 слова)';
            } else {
                errorEl.textContent = 'Каждое слово должно содержать минимум 2 буквы';
            }
            errorEl.style.display = 'block';
        } else {
            errorEl.style.display = 'none';
        }
    };

    document.getElementById('checkoutCancel').onclick = () => {
        const form = document.getElementById('checkoutForm');
        form.classList.remove('active');
    };

    form.onsubmit = async (e) => {
        e.preventDefault();
        
        const inputs = form.querySelectorAll('.form-control');
        let valid = true;
        
        inputs.forEach(inp => {
            inp.dispatchEvent(new Event('input'));
            
            if (!inp.value.trim() || !inp.classList.contains('is-valid')) {
                inp.classList.add('is-invalid');
                valid = false;
            } else {
                inp.classList.remove('is-invalid');
            }
        });
        
        if (!valid) {
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
            }
            return;
        }

        const btn = form.querySelector('[type="submit"]');
        const originalText = btn.textContent;
        btn.textContent = 'Обработка...';
        btn.disabled = true;

        try {
            const res = await fetch('/FIFI/api/checkout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'pay' })
            });
            
            const data = await res.json();

            if (data.success) {
                form.innerHTML = `
                    <div class="text-center py-5">
                        <div style="font-size:64px;color:#28a745;">✓</div>
                        <h3 class="fw-bold mt-3">УСПЕШНО ОПЛАЧЕНО!</h3>
                        <p class="text-secondary">Спасибо за покупку</p>
                        <button class="btn btn-dark mt-4" onclick="location.reload()">Закрыть</button>
                    </div>
                `;
            } else {
                alert(data.error || 'Ошибка оплаты');
                btn.textContent = originalText;
                btn.disabled = false;
            }
        } catch (error) {
            console.error('Payment error:', error);
            alert('Ошибка при оплате');
            btn.textContent = originalText;
            btn.disabled = false;
        }
    };
}