function themeSwitcher() {
  return {
    currentTheme: 'light',
    sidebarOpen: false,
    initTheme() {
      const saved = localStorage.getItem('sendflow_theme');
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      this.currentTheme = saved || (prefersDark ? 'dark' : 'light');
      this.applyTheme();
    },
    toggleTheme() {
      this.currentTheme = this.currentTheme === 'light' ? 'dark' : 'light';
      localStorage.setItem('sendflow_theme', this.currentTheme);
      this.applyTheme();
      window.dispatchEvent(new CustomEvent('sendflow-toast', {
        detail: {
          message: this.currentTheme === 'dark' ? 'Tema escuro ativado.' : 'Tema claro ativado.',
          type: 'info'
        }
      }));
    },
    applyTheme() {
      document.documentElement.classList.toggle('dark', this.currentTheme === 'dark');
      document.documentElement.dataset.theme = this.currentTheme;
      localStorage.setItem('sendflow_theme', this.currentTheme);
    }
  };
}

function toastManager() {
  return {
    toasts: [],
    add(message, type = 'info', duration = 4000) {
      const id = Date.now() + Math.random();
      this.toasts.push({ id, message, type });
      setTimeout(() => this.remove(id), duration);
    },
    remove(id) {
      this.toasts = this.toasts.filter((toast) => toast.id !== id);
    },
    success(message) { this.add(message, 'success'); },
    error(message) { this.add(message, 'error'); }
  };
}

document.body.addEventListener('htmx:configRequest', (event) => {
  const token = document.querySelector('meta[name="csrf-token"]');
  if (token) event.detail.headers['X-CSRF-TOKEN'] = token.content;
});

document.body.addEventListener('htmx:beforeRequest', () => {
  document.querySelectorAll('.htmx-indicator').forEach((indicator) => indicator.classList.remove('hidden'));
});

document.body.addEventListener('htmx:afterRequest', (event) => {
  document.querySelectorAll('.htmx-indicator').forEach((indicator) => indicator.classList.add('hidden'));
  if (event.detail.xhr.status >= 400) {
    window.dispatchEvent(new CustomEvent('sendflow-toast', {
      detail: { message: 'Ocorreu um erro. Tente novamente.', type: 'error' }
    }));
  }
});

window.addEventListener('sendflow-toast', (event) => {
  const alpineToast = document.querySelector('[x-data="toastManager()"]');
  if (alpineToast && alpineToast.__x) {
    alpineToast.__x.$data.add(event.detail.message, event.detail.type || 'info');
  }
});

function loadLottie(containerId, animationPath, loop = true) {
  if (!window.lottie) return;
  const element = document.getElementById(containerId);
  if (!element) return;
  window.lottie.loadAnimation({
    container: element,
    renderer: 'svg',
    loop,
    autoplay: true,
    path: animationPath,
  });
}

function fireCarbonAlert(options = {}) {
  if (!window.Swal) return;
  const isDark = document.documentElement.classList.contains('dark');
  return window.Swal.fire({
    background: isDark ? '#262626' : '#ffffff',
    color: isDark ? '#f4f4f4' : '#161616',
    confirmButtonColor: isDark ? '#4589ff' : '#0f62fe',
    ...options,
  });
}

document.addEventListener('DOMContentLoaded', () => {
  loadLottie('lottie-mail', '/assets/lottie/loading.json');
});
