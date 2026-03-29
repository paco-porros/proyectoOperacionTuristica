/* ════════════════════════════════════════════════════
   LOGIN.PHP — Toggle contraseña + envío AJAX
════════════════════════════════════════════════════ */

/* ── Toggle contraseña ── */
document.getElementById('togglePassword').addEventListener('click', function () {
  const inp  = document.getElementById('contrasena');
  const show = inp.type === 'password';
  inp.type   = show ? 'text' : 'password';
  this.textContent = show ? 'visibility_off' : 'visibility';
});

/* ── Alerta helper ── */
function mostrarAlerta(msg, tipo) {
  const el = document.getElementById('alerta-login');
  el.textContent = msg;
  el.style.display = 'block';
  if (tipo === 'ok') {
    el.style.background = 'rgba(16,185,129,.15)';
    el.style.color = '#065f46';
    el.style.border = '1px solid #6ee7b7';
  } else {
    el.style.background = 'rgba(239,68,68,.12)';
    el.style.color = '#7f1d1d';
    el.style.border = '1px solid #fca5a5';
  }
}

/* ── Envío AJAX del formulario ── */
document.getElementById('formulario-login').addEventListener('submit', async function (e) {
  e.preventDefault();

  const btn   = document.getElementById('btn-login');
  const email = document.getElementById('correo').value.trim();
  const pass  = document.getElementById('contrasena').value.trim();

  if (!email || !pass) {
    mostrarAlerta('Por favor completa todos los campos.', 'error');
    return;
  }

  btn.disabled     = true;
  btn.textContent  = 'Iniciando…';

  try {
    const res  = await fetch('/ajax/login.php', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ email, password: pass }),
    });
    const data = await res.json();

    if (data.ok) {
      mostrarAlerta(data.msg, 'ok');
      setTimeout(() => { window.location.href = data.redirect; }, 800);
    } else {
      mostrarAlerta(data.msg, 'error');
      btn.disabled    = false;
      btn.textContent = 'Iniciar Sesión';
    }
  } catch (err) {
    mostrarAlerta('Error de conexión. Intenta de nuevo.', 'error');
    btn.disabled    = false;
    btn.textContent = 'Iniciar Sesión';
  }
});