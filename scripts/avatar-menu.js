/* ════════════════════════════════════════════════════
   AVATAR-MENU.JS — Menú desplegable de avatar + logout AJAX
   Reutilizado en: nosotros.php, servicios.php, legales.php
════════════════════════════════════════════════════ */

document.getElementById('avatar-usuario').addEventListener('click', e => {
  e.stopPropagation();
  document.getElementById('menu-avatar').classList.toggle('navegacion__dropdown--visible');
});

document.addEventListener('click', () => {
  const m = document.getElementById('menu-avatar');
  if (m) m.classList.remove('navegacion__dropdown--visible');
});

document.getElementById('btn-logout').addEventListener('click', async () => {
  await fetch('/ajax/logout.php', { method: 'POST' });
  window.location.href = '/index.php';
});