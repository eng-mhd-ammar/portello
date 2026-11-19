import { Head } from '@inertiajs/react';

export default function PublicPortfolioShow({ site }) {
    return (
        <>
            <Head title={site.headline || site.name} />
            <div className="min-h-screen bg-surface text-content">
                <header className="border-b border-border bg-surface-elevated/90 backdrop-blur">
                    <div className="mx-auto flex max-w-4xl items-center justify-between gap-4 px-4 py-4 sm:px-6">
                        <span className="text-lg font-semibold tracking-tight">{site.name}</span>
                        {site.show_branding && (
                            <a
                                href={centralAppUrl()}
                                className="text-xs font-medium text-content-muted hover:text-brand"
                            >
                                Powered by Portello
                            </a>
                        )}
                    </div>
                </header>

                <main className="mx-auto max-w-4xl px-4 py-16 sm:px-6 sm:py-24">
                    <p className="text-sm font-medium uppercase tracking-wide text-brand">{site.template?.name}</p>
                    <h1 className="mt-3 text-4xl font-semibold tracking-tight sm:text-5xl">{site.headline}</h1>
                    {site.bio && <p className="mt-6 max-w-2xl text-lg leading-relaxed text-content-muted">{site.bio}</p>}
                    {site.owner?.name && (
                        <p className="mt-10 text-sm text-content-subtle">— {site.owner.name}</p>
                    )}
                </main>

                <footer className="border-t border-border py-6 text-center text-xs text-content-muted">
                    {site.host}
                </footer>
            </div>
        </>
    );
}

function centralAppUrl() {
    if (typeof window !== 'undefined') {
        const base = (import.meta.env.VITE_APP_URL || '').replace(/\/$/, '');
        if (base) {
            return base;
        }
    }

    return '/';
}