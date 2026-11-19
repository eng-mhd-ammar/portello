/**
 * Sidebar capability map from shared auth (HandleInertiaRequests).
 * When the server omits keys (stale session / old deploy), treat user-facing links as visible
 * if the user is signed in; admin links stay off unless explicitly true.
 */
export function resolveAuthCan(auth) {
    if (!auth?.user) {
        return null;
    }

    const fromServer = auth.can ?? {};

    return {
        billing: 'billing' in fromServer ? Boolean(fromServer.billing) : true,
        templates: 'templates' in fromServer ? Boolean(fromServer.templates) : true,
        plansManage: Boolean(fromServer.plansManage),
        templatesManage: Boolean(fromServer.templatesManage),
    };
}