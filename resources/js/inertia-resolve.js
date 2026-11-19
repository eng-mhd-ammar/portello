import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const modulePages = import.meta.glob('../../Modules/**/Resources/js/Pages/**/*.jsx');

/**
 * Inertia page name: {Module}/{path}
 * File: Modules/{Module}/Resources/js/Pages/{path}.jsx
 */
export function resolvePortelloPage(name) {
    const slash = name.indexOf('/');

    if (slash === -1) {
        throw new Error(`Inertia page "${name}" must use {Module}/{path} (example: IAM/Auth/Login).`);
    }

    const moduleName = name.slice(0, slash);
    const pagePath = name.slice(slash + 1);
    const paths = [
        `../../Modules/${moduleName}/Resources/js/Pages/${pagePath}.jsx`,
        `/Modules/${moduleName}/Resources/js/Pages/${pagePath}.jsx`,
        `Modules/${moduleName}/Resources/js/Pages/${pagePath}.jsx`,
    ];

    return resolvePageComponent(paths, modulePages);
}