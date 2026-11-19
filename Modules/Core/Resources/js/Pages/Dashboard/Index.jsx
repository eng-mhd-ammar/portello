import AppLayout from '@core/Layouts/AppLayout';
import { resolveAuthCan } from '@core/Config/navCapabilities';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Dashboard() {
    const { auth } = usePage().props;
    const can = resolveAuthCan(auth);

    const quickLinks = [
        {
            title: 'Portfolios',
            description: 'Create sites on your own subdomain, publish, and share.',
            href: '/portfolios',
            cta: 'Manage portfolios',
        },
        {
            title: 'Subscription',
            description: 'View your plan, limits, and switch billing tier.',
            href: '/billing',
            cta: 'Open billing',
        },
        {
            title: 'Templates',
            description: 'Browse layouts before creating or editing a portfolio.',
            href: '/templates',
            cta: 'Browse catalog',
        },
        {
            title: 'Profile',
            description: 'Update your name, email, and password.',
            href: '/profile',
            cta: 'Account settings',
        },
    ];

    const adminLinks = [
        can?.plansManage && {
            title: 'Manage plans',
            description: 'Edit catalog plans, prices, and portfolio limits.',
            href: '/admin/plans',
            cta: 'Admin plans',
        },
        can?.templatesManage && {
            title: 'Manage templates',
            description: 'Add templates, premium flags, and JSON schema.',
            href: '/admin/templates',
            cta: 'Admin templates',
        },
    ].filter(Boolean);

    return (
        <AppLayout title="Dashboard">
            <Head title="Dashboard" />

            <article className="mb-8 rounded-2xl border border-border bg-surface-elevated p-6 shadow-sm">
                <h2 className="text-sm font-medium text-content-muted">Signed in as</h2>
                <p className="mt-1 text-lg font-semibold text-content">{auth?.user?.name}</p>
                <p className="text-sm text-content-muted">{auth?.user?.email}</p>
            </article>

            <h2 className="mb-4 text-sm font-semibold uppercase tracking-wide text-content-muted">Modules</h2>
            <div className="mb-10 grid gap-6 sm:grid-cols-2">
                {quickLinks.map((item) => (
                    <DashboardCard key={item.href} {...item} />
                ))}
            </div>

            {adminLinks.length > 0 && (
                <>
                    <h2 className="mb-4 text-sm font-semibold uppercase tracking-wide text-content-muted">Administration</h2>
                    <div className="grid gap-6 sm:grid-cols-2">
                        {adminLinks.map((item) => (
                            <DashboardCard key={item.href} {...item} />
                        ))}
                    </div>
                </>
            )}
        </AppLayout>
    );
}

function DashboardCard({ title, description, href, cta }) {
    return (
        <article className="flex flex-col rounded-2xl border border-border bg-surface-elevated p-6 shadow-sm">
            <h3 className="font-semibold text-content">{title}</h3>
            <p className="mt-2 flex-1 text-sm leading-relaxed text-content-muted">{description}</p>
            <Link href={href} className="mt-4 inline-flex text-sm font-medium text-brand hover:text-brand-hover">
                {cta} →
            </Link>
        </article>
    );
}