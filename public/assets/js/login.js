(() => {
    const form = document.getElementById('loginForm');
    if (!form) return;

    const submit = document.getElementById('btnSubmit');
    const password = document.getElementById('password');
    const alertBox = document.getElementById('alertMessage');
    const defaultSubmit = submit.innerHTML;
    let countdown = null;

    const showError = message => {
        const box = document.createElement('div');
        box.className = 'login-alert';
        box.setAttribute('role', 'alert');
        box.append(document.createTextNode(String(message || 'Login gagal diproses.')));
        alertBox.replaceChildren(box);
    };

    const retryAfter = seconds => {
        let remaining = Math.max(0, Number(seconds || 0));
        if (!remaining) return;
        clearInterval(countdown);
        const tick = () => {
            submit.disabled = true;
            submit.innerHTML = `<i class="bi bi-clock me-1"></i>Coba lagi dalam ${remaining} detik`;
            remaining -= 1;
            if (remaining < 0) {
                clearInterval(countdown);
                countdown = null;
                submit.disabled = false;
                submit.innerHTML = defaultSubmit;
            }
        };
        tick();
        countdown = setInterval(tick, 1000);
    };

    form.addEventListener('submit', async event => {
        event.preventDefault();
        if (!form.reportValidity()) return;
        alertBox.replaceChildren();
        submit.disabled = true;
        submit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memverifikasi...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {'X-Requested-With': 'XMLHttpRequest'},
            });
            if (response.status === 403) {
                location.reload();
                return;
            }
            const result = await window.KerangkaHTTP.readJSON(response);
            const csrfInput = form.querySelector('input[type="hidden"]');
            if (csrfInput && result.csrf_token) csrfInput.value = result.csrf_token;
            if (result.success) {
                submit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Membuka dashboard...';
                location.assign(result.redirect);
                return;
            }
            showError(result.message);
            password.focus();
            password.select();
            retryAfter(result.retry_after);
        } catch (_) {
            showError('Layanan login tidak dapat dihubungi. Muat ulang halaman lalu coba kembali.');
        } finally {
            if (!countdown) {
                submit.disabled = false;
                submit.innerHTML = defaultSubmit;
            }
        }
    });

    document.getElementById('togglePassword')?.addEventListener('click', event => {
        const visible = password.type === 'text';
        password.type = visible ? 'password' : 'text';
        event.currentTarget.innerHTML = `<i class="bi ${visible ? 'bi-eye' : 'bi-eye-slash'}"></i>`;
        event.currentTarget.setAttribute('aria-label', visible ? 'Tampilkan password' : 'Sembunyikan password');
        password.focus();
    });
    password.addEventListener('keyup', event => {
        document.getElementById('capsWarning').textContent = event.getModifierState?.('CapsLock') ? 'Caps Lock sedang aktif.' : '';
    });
})();
