const theme = localStorage.getItem('user-theme') || 'light-theme';

document.documentElement.classList.add(theme);