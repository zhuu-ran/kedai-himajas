const overlay = document.getElementById('authOverlay');

function openAuth(panel) {
   showPanel(panel || 'login');
   overlay.classList.add('open');
   document.body.style.overflow = 'hidden';
}
function closeAuth() {
   overlay.classList.remove('open');
   document.body.style.overflow = '';
}
function showPanel(which) {
   document.getElementById('panelLogin').classList.toggle('active', which === 'login');
   document.getElementById('panelRegister').classList.toggle('active', which === 'register');
   document.getElementById('loginMsg').textContent = '';
   document.getElementById('registerMsg').textContent = '';
}
function showMsg(id, text, type) {
   const el = document.getElementById(id);
   el.textContent = text;
   el.className = 'auth-msg ' + type;
}

// Buka modal
document.getElementById('btnOpenAuth').addEventListener('click', () => openAuth('login'));

// Tutup
document.getElementById('btnCloseAuth').addEventListener('click', closeAuth);

// Klik backdrop
overlay.addEventListener('click', e => { if (e.target === overlay) closeAuth(); });

// ESC
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAuth(); });

// Toggle panel
document.getElementById('goRegister').addEventListener('click', e => { e.preventDefault(); showPanel('register'); });
document.getElementById('goLogin').addEventListener('click', e => { e.preventDefault(); showPanel('login'); });

// Show/hide password
document.querySelectorAll('.auth-eye').forEach(btn => {
   btn.addEventListener('click', function() {
      const input = document.getElementById(this.dataset.target);
      const isPass = input.type === 'password';
      input.type = isPass ? 'text' : 'password';
      this.querySelector('i').className = isPass ? 'fas fa-eye-slash' : 'fas fa-eye';
   });
});

// Login
document.getElementById('btnLogin').addEventListener('click', () => {
   const email = document.getElementById('loginEmail').value.trim();
   const pass  = document.getElementById('loginPass').value;
   if (!email || !pass) return showMsg('loginMsg', 'Email dan password wajib diisi.', 'error');
   showMsg('loginMsg', 'Memproses...', '');
   fetch('/project/auth/process.php', {
      method: 'POST',
      body: new URLSearchParams({ action: 'login', email, password: pass })
   })
   .then(r => r.json())
   .then(data => {
      if (data.status === 'success') {
         if (data.role === 'admin') {
            window.location.href = '/project/admin/dashboard.php';
         } else {
            window.location.href = '/project/index.php';
         }
      } else {
         showMsg('loginMsg', data.message, 'error');
      }
   })
   .catch(err => showMsg('loginMsg', 'Error: ' + err.message, 'error'));
});

// Register
document.getElementById('btnRegister').addEventListener('click', () => {
   const name  = document.getElementById('regName').value.trim();
   const email = document.getElementById('regEmail').value.trim();
   const pass  = document.getElementById('regPass').value;
   const pass2 = document.getElementById('regPass2').value;
   if (!name || !email || !pass || !pass2) return showMsg('registerMsg', 'Semua field wajib diisi.', 'error');
   if (pass.length < 8) return showMsg('registerMsg', 'Password minimal 8 karakter.', 'error');
   if (pass !== pass2) return showMsg('registerMsg', 'Konfirmasi password tidak cocok.', 'error');
   showMsg('registerMsg', 'Memproses...', '');
   fetch('/project/auth/process.php', {
      method: 'POST',
      body: new URLSearchParams({ action: 'register', name: name, email, password: pass })
   })
   .then(r => r.json())
   .then(data => {
      showMsg('registerMsg', data.message, data.status);
      if (data.status === 'success') setTimeout(() => showPanel('login'), 1500);
   });
});