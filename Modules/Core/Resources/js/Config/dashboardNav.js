/**
 * Dashboard sidebar: module groups and sub-items.
 * permission: key on auth.can (see HandleInertiaRequests).
 */
export const dashboardNav = [
    {
        module: 'Core',
        label: 'Overview',
        items: [
            { label: 'Dashboard', href: '/dashboard', match: 'exact' },
            { label: 'Marketing site', href: '/', match: 'exact', external: true },
        ],
    },
    {
        module: 'Portfolios',
        label: 'Portfolios',
        items: [
            { label: 'All portfolios', href: '/portfolios', match: 'prefix' },
            { label: 'Create portfolio', href: '/portfolios/create', match: 'exact' },
        ],
    },
    {
        module: 'Plans',
        label: 'Plans & billing',
        items: [
            { label: 'Subscription', href: '/billing', match: 'exact' },
            { label: 'Manage plans', href: '/admin/plans', match: 'prefix', permission: 'plansManage' },
        ],
    },
    {
        module: 'IAM',
        label: 'Account',
        items: [{ label: 'Profile', href: '/profile', match: 'exact' }],
    },
    {
        module: 'Templates',
        label: 'Templates',
        items: [
            { label: 'Browse catalog', href: '/templates', match: 'prefix' },
            { label: 'Manage templates', href: '/admin/templates', match: 'prefix', permission: 'templatesManage' },
        ],
    },
];

export function pathMatches(href, currentUrl, match = 'exact') {
    if (!href || currentUrl == null || currentUrl === '') {
        return false;
    }

    const path = String(currentUrl).split('?')[0];

    if (match === 'prefix') {
        return path === href || path.startsWith(`${href}/`);
    }

    return path === href;
}

export function isGroupActive(group, currentUrl) {
    return group.items.some((item) => item.href && pathMatches(item.href, currentUrl, item.match));
}

export function visibleNavGroups(nav, can) {
    return nav
        .map((group) => ({
            ...group,
            items: group.items.filter((item) => {
                if (!item.permission) {
                    return true;
                }

                if (can == null) {
                    return false;
                }

                return Boolean(can[item.permission]);
            }),
        }))
        .filter((group) => group.items.length > 0);
}