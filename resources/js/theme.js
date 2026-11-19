const STORAGE_KEY = 'portello-theme';

export function getSystemTheme() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

export function getStoredTheme() {
    const stored = localStorage.getItem(STORAGE_KEY);

    if (stored === 'dark' || stored === 'light') {
        return stored;
    }

    return null;
}

export function getTheme() {
    return getStoredTheme() ?? getSystemTheme();
}

export function applyTheme(theme) {
    document.documentElement.classList.toggle('dark', theme === 'dark');
    document.documentElement.dataset.theme = theme;
}

export function setTheme(theme) {
    localStorage.setItem(STORAGE_KEY, theme);
    applyTheme(theme);
}

export function toggleTheme() {
    const next = getTheme() === 'dark' ? 'light' : 'dark';
    setTheme(next);

    return next;
}

export function initTheme() {
    applyTheme(getTheme());
}