// assets/js/theme-toggle.js
document.addEventListener('DOMContentLoaded', () => {
  const themeBtn = document.getElementById('themeToggleBtn');
  const htmlTag = document.documentElement;

  // Baca preferensi tersimpan di localStorage
  const savedTheme = localStorage.getItem('theme_mode') || 'light';
  htmlTag.setAttribute('data-theme', savedTheme);
  updateIcon(savedTheme);

  if (themeBtn) {
    themeBtn.addEventListener('click', () => {
      const currentTheme = htmlTag.getAttribute('data-theme');
      const newTheme = currentTheme === 'light' ? 'dark' : 'light';

      htmlTag.setAttribute('data-theme', newTheme);
      localStorage.setItem('theme_mode', newTheme);
      updateIcon(newTheme);
    });
  }

  function updateIcon(theme) {
    if (!themeBtn) return;
    const iconSpan = themeBtn.querySelector('.theme-icon');
    if (iconSpan) {
      iconSpan.textContent = theme === 'light' ? '🌙' : '☀️';
    }
  }
});