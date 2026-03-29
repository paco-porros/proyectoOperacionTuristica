/* ════════════════════════════════════════════════════
   REGISTRO.PHP — Toggle contraseñas + envío AJAX
════════════════════════════════════════════════════ */

/* ── Toggle contraseñas ── */
const camposContrasena = {
  togglePassword: document.getElementById('contrasena'),
  togglePassword2: document.getElementById('confirmar-contrasena'),
};

Object.entries(camposContrasena).forEach(([btnId, inp]) => {
  const btn = document.getElementById(btnId);
  if (!btn || !inp) return;

  inp.style.color = '#000';
  btn.addEventListener('click', function () {
    const show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    this.textContent = show ? 'visibility_off' : 'visibility';
    inp.style.color = '#000';
    inp.focus();
  });
});

/* ── Alerta helper ── */
function mostrarAlerta(msg, tipo) {
  const el = document.getElementById('alerta-registro');
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

/* ── Envío AJAX ── */
document.getElementById('formulario-registro').addEventListener('submit', async function (e) {
  e.preventDefault();

  const btn       = document.getElementById('btn-registro');
  const nombre    = document.getElementById('nombre-completo').value.trim();
  const email     = document.getElementById('correo').value.trim();
  const password  = document.getElementById('contrasena').value.trim();
  const confirmar = document.getElementById('confirmar-contrasena').value.trim();
  const terminos  = document.getElementById('terminos').checked;

  if (!nombre || !email || !password || !confirmar) {
    mostrarAlerta('Por favor completa todos los campos.', 'error'); return;
  }
  if (!terminos) {
    mostrarAlerta('Debes aceptar los Términos de Servicio.', 'error'); return;
  }

  btn.disabled = true;
  btn.querySelector('span').textContent = 'Creando cuenta…';

  try {
    const res  = await fetch('/ajax/registro.php', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ nombre, email, password, confirmar }),
    });
    const data = await res.json();

    if (data.ok) {
      mostrarAlerta(data.msg, 'ok');
      setTimeout(() => { window.location.href = data.redirect; }, 900);
    } else {
      mostrarAlerta(data.msg, 'error');
      btn.disabled = false;
      btn.querySelector('span').textContent = 'Registrarse';
    }
  } catch (err) {
    mostrarAlerta('Error de conexión. Intenta de nuevo.', 'error');
    btn.disabled = false;
    btn.querySelector('span').textContent = 'Registrarse';
  }
});