import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import { initTheme } from '@theme';
import { resolvePortelloPage } from './inertia-resolve';

initTheme();

const appName = import.meta.env.VITE_APP_NAME || 'Portello';

createInertiaApp({
    title: (title) => (title ? `${title} · ${appName}` : appName),
    resolve: (name) => resolvePortelloPage(name),
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
    progress: {
        color: '#ea580c',
        includeCSS: true,
        showProgress: true,
        delay: 0,
    },
});