import AppLayout from '@core/Layouts/AppLayout';
import Button from '@core/Components/Button';
import PageIntro from '@core/Components/PageIntro';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

export default function PortfoliosIndex({ portfolios, baseDomain }) {
    const [publishingId, setPublishingId] = useState(null);

    const publish = (id) => {
        setPublishingId(id);
        router.post(`/portfolios/${id}/publish`, {}, {
            preserveScroll: true,
            onFinish: () => setPublishingId(null),
        });
    };

    return (
        <AppLayout title="Portfolios">
            <Head title="Portfolios" />

            <PageIntro
                description={
                    <>
                        Each portfolio lives on its own host, e.g.{' '}
                        <span className="font-mono text-content">slug.{baseDomain}</span>.
                    </>
                }
                actions={
                    <Button href="/portfolios/create" variant="primary">
                        New portfolio
                    </Button>
                }
                className="mb-6"
            />

            {portfolios.length === 0 ? (
                <EmptyState baseDomain={baseDomain} />
            ) : (
                <ul className="grid gap-4 sm:grid-cols-2">
                    {portfolios.map((portfolio) => (
                        <li
                            key={portfolio.id}
                            className="rounded-2xl border border-border bg-surface-elevated p-6 shadow-sm"
                        >
                            <div className="flex items-start justify-between gap-3">
                                <div>
                                    <h2 className="text-lg font-semibold text-content">{portfolio.name}</h2>
                                    <p className="mt-1 font-mono text-xs text-content-muted">
                                        {portfolio.slug}.{baseDomain}
                                    </p>
                                </div>
                                <StatusBadge status={portfolio.status} label={portfolio.status_label} />
                            </div>
                            <p className="mt-3 text-sm text-content-muted">
                                Template: {portfolio.template?.name ?? '—'}
                            </p>
                            <div className="mt-5 flex flex-wrap gap-2">
                                {portfolio.status !== 1 && (
                                    <Button
                                        type="button"
                                        variant="primary"
                                        disabled={publishingId === portfolio.id}
                                        onClick={() => publish(portfolio.id)}
                                    >
                                        {publishingId === portfolio.id ? 'Publishing…' : 'Publish'}
                                    </Button>
                                )}
                                {portfolio.public_url && portfolio.status === 1 && (
                                    <a
                                        href={portfolio.public_url}
                                        target="_blank"
                                        rel="noreferrer"
                                        className="inline-flex items-center rounded-xl border border-border-strong px-4 py-2 text-sm font-medium text-content hover:bg-surface-muted"
                                    >
                                        View live site
                                    </a>
                                )}
                            </div>
                        </li>
                    ))}
                </ul>
            )}
        </AppLayout>
    );
}

function StatusBadge({ status, label }) {
    const isPublished = status === 1;

    return (
        <span
            className={`rounded-full px-2.5 py-0.5 text-xs font-medium ${
                isPublished ? 'bg-success-surface text-success-content' : 'bg-brand-soft text-brand'
            }`}
        >
            {label}
        </span>
    );
}

function EmptyState({ baseDomain }) {
    return (
        <div className="rounded-2xl border border-dashed border-border-strong bg-surface-muted px-6 py-12 text-center">
            <h2 className="text-lg font-semibold text-content">No portfolios yet</h2>
            <p className="mx-auto mt-2 max-w-md text-sm text-content-muted">
                Create your first site—it will get a dedicated subdomain on {baseDomain}.
            </p>
            <Button href="/portfolios/create" variant="primary" className="mt-6">
                Create portfolio
            </Button>
        </div>
    );
}