const authOverlay = document.getElementById('authOverlay');
const authModal = document.getElementById('authModal');
const openAuth = document.getElementById('openAuth');
const closeAuth = document.getElementById('closeAuth');
const toggleFormBtn = document.getElementById('toggleFormBtn');
const tabLogin = document.getElementById('tabLogin');
const tabRegister = document.getElementById('tabRegister');
const formLogin = document.getElementById('formLogin');
const formRegister = document.getElementById('formRegister');
const feedback = document.getElementById('feedback');

document.addEventListener('DOMContentLoaded', function () {
  try {
    // se o servidor pediu para abrir o login automaticamente
    if (window.autoOpenLoginFromServer === true) {
      // se o servidor forneceu um redirect, salvamos para uso pós-login
      if (window.postLoginRedirectFromServer) {
        try {
          sessionStorage.setItem('postLoginRedirect', window.postLoginRedirectFromServer);
        } catch (err) {
          console.warn('sessionStorage não disponível:', err);
        }
      }
      // abre o modal (openModal está definido no restante do login.js)
      if (typeof openModal === 'function') {
        openModal();
      } else {
        // fallback: clicar no botão caso exista
        const btn = document.getElementById('openAuth');
        if (btn) btn.click();
      }
    }
  } catch (e) {
    console.error('Erro ao processar autoOpenLoginFromServer:', e);
  }
});

function openModal() {
    authOverlay.classList.remove('hidden');
    authModal.classList.remove('hidden');
    authModal.style.left = '50%';
    authModal.style.top = '50%';
    authModal.style.transform = 'translate(-50%, -50%)';
    clearFeedback();
    showLogin();
}
function closeModal() {
    authOverlay.classList.add('hidden');
    authModal.classList.add('hidden');
}
openAuth.addEventListener('click', openModal);
closeAuth.addEventListener('click', closeModal);
authOverlay.addEventListener('click', closeModal);
function showLogin() {
    formLogin.classList.remove('hidden');
    formRegister.classList.add('hidden');
    tabLogin.classList.add('bg-red-600','text-white');
    tabLogin.classList.remove('border','text-red-600');
    tabRegister.classList.remove('bg-red-600','text-white');
    tabRegister.classList.add('border','text-red-600');
    clearFeedback();
}
function showRegister() {
    formLogin.classList.add('hidden');
    formRegister.classList.remove('hidden');
    tabRegister.classList.add('bg-red-600','text-white');
    tabRegister.classList.remove('border','text-red-600');
    tabLogin.classList.remove('bg-red-600','text-white');
    tabLogin.classList.add('border','text-red-600');
    clearFeedback();
}
tabLogin.addEventListener('click', showLogin);
tabRegister.addEventListener('click', showRegister);
toggleFormBtn.addEventListener('click', () => {
    if (formLogin.classList.contains('hidden')) showLogin(); else showRegister();
});
function showFeedback(msg, ok = true) {
    feedback.textContent = msg;
    feedback.className = ok ? 'text-sm mb-3 text-green-700' : 'text-sm mb-3 text-red-600';
}
function clearFeedback() {
    feedback.textContent = '';
    feedback.className = '';
}
// Register via fetch -> register.php
document.getElementById('formRegister').addEventListener('submit', async function(e) {
    e.preventDefault();
    const nome = document.getElementById('regName').value.trim();
    const email = document.getElementById('regEmail').value.trim();
    const password = document.getElementById('regPassword').value;
    const passwordConfirm = document.getElementById('regPasswordConfirm').value;
    if (!nome || !email || !password) { showFeedback('Preencha todos os campos.', false); return; }
    if (password.length < 6) { showFeedback('Senha deve ter ao menos 6 caracteres.', false); return; }
    if (password !== passwordConfirm) { showFeedback('As senhas não coincidem.', false); return; }
    const fd = new FormData();
    fd.append('nome', nome);
    fd.append('email', email);
    fd.append('password', password);
    try {
    const res = await fetch('register.php', { method: 'POST', body: fd });
    const data = await res.json();
    if (data.success) {
        showFeedback(data.message || 'Cadastro OK', true);
        document.getElementById('regName').value = '';
        document.getElementById('regEmail').value = '';
        document.getElementById('regPassword').value = '';
        document.getElementById('regPasswordConfirm').value = '';
        setTimeout(showLogin, 900);
    } else {
        showFeedback(data.message || 'Erro no cadastro', false);
    }
    } catch (err) {
    console.error(err);
    showFeedback('Erro de comunicação com o servidor.', false);
    }
});
// Login via fetch -> login.php
document.getElementById('formLogin').addEventListener('submit', async function(e) {
    e.preventDefault();
    const email = document.getElementById('loginEmail').value.trim();
    const password = document.getElementById('loginPassword').value;
    const remember = document.getElementById('remember').checked ? '1' : '0';
    if (!email || !password) { showFeedback('Preencha e-mail e senha.', false); return; }
    const fd = new FormData();
    fd.append('email', email);
    fd.append('password', password);
    fd.append('remember', remember);
    try {
    const res = await fetch('login.php', { method: 'POST', body: fd });
    const data = await res.json();
    if (data.success) {
        showFeedback(data.message || 'Login realizado!', true);
        setTimeout(() => {
        closeModal();
        if (data.redirect) {
            window.location.href = data.redirect;
        } else {
            location.reload();
        }
        }, 700);
    } else {
        showFeedback(data.message || 'E-mail ou senha incorretos.', false);
    }
    } catch (err) {
    console.error(err);
    showFeedback('Erro de comunicação com o servidor.', false);
    }
});
// mostrar/ocultar senha
function togglePassword(btn) {
    const targetId = btn.getAttribute('data-target');
    const input = document.getElementById(targetId);
    if (!input) return;
    if (input.type === 'password') {
    input.type = 'text';
    btn.innerHTML = '<i class="fa-regular fa-eye-slash"></i>';
    } else {
    input.type = 'password';
    btn.innerHTML = '<i class="fa-regular fa-eye"></i>';
    }
}
