import { getTheme, toggleTheme } from '@theme';
import { useEffect, useState } from 'react';

function SunIcon({ className }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.75" aria-hidden>
            <circle cx="12" cy="12" r="4" />
            <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
        </svg>
    );
}

function MoonIcon({ className }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.75" aria-hidden>
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
        </svg>
    );
}

export default function ThemeToggle({ className = '' }) {
    const [theme, setThemeState] = useState('light');

    useEffect(() => {
        setThemeState(getTheme());
    }, []);

    const onToggle = () => {
        setThemeState(toggleTheme());
    };

    const isDark = theme === 'dark';

    return (
        <button
            type="button"
            onClick={onToggle}
            className={`flex h-10 w-10 items-center justify-center rounded-xl border border-border bg-surface-elevated text-content-muted transition hover:border-border-strong hover:bg-surface-muted hover:text-content focus:outline-none focus-visible:ring-2 focus-visible:ring-brand/30 ${className}`}
            aria-label={isDark ? 'Switch to light theme' : 'Switch to dark theme'}
        >
            {isDark ? <SunIcon className="h-5 w-5" /> : <MoonIcon className="h-5 w-5" />}
        </button>
    );
}