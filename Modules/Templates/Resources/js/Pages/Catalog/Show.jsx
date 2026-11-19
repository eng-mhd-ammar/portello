import AppLayout from '@core/Layouts/AppLayout';
import Button from '@core/Components/Button';
import { Head, Link } from '@inertiajs/react';

export default function TemplatesCatalogShow({ auth, template, canManage }) {
    return (
        <AppLayout auth={auth} title={template.name}>
            <Head title={template.name} />

            <Link href="/templates" className="text-sm font-medium text-brand hover:text-brand-hover">
                ← Back to catalog
            </Link>

            <div className="mt-6 overflow-hidden rounded-2xl border border-border bg-surface-elevated shadow-sm lg:grid lg:grid-cols-2">
                <div className="aspect-[4/3] border-b border-border bg-gradient-to-br from-surface-muted via-surface-elevated to-brand-soft lg:aspect-auto lg:border-b-0 lg:border-r">
                    {template.preview_image ? (
                        <img src={template.preview_image} alt="" className="h-full w-full object-cover" />
                    ) : (
                        <motionPlaceholder name={template.name} slug={template.slug} />
                    )}
                </div>

                <motionDetails className="p-6 sm:p-8">
                    <div className="flex flex-wrap items-center gap-2">
                        <h1 className="text-2xl font-semibold text-content">{template.name}</h1>
                        {template.is_premium ? (
                            <span className="rounded-full bg-brand-soft px-2.5 py-0.5 text-xs font-medium text-brand">
                                Premium · {formatPrice(template.price, template.currency)}
                            </span>
                        ) : (
                            <span className="rounded-full bg-surface-muted px-2.5 py-0.5 text-xs font-medium text-content-muted">
                                Free
                            </span>
                        )}
                    </div>

                    <p className="mt-1 font-mono text-xs text-content-subtle">{template.slug}</p>

                    {template.description && (
                        <p className="mt-4 text-sm leading-relaxed text-content-muted">{template.description}</p>
                    )}

                    {template.sections?.length > 0 && (
                        <section className="mt-6">
                            <h2 className="text-sm font-semibold text-content">Included sections</h2>
                            <ul className="mt-2 flex flex-wrap gap-2">
                                {template.sections.map((section) => (
                                    <li
                                        key={section}
                                        className="rounded-lg border border-border bg-surface-muted px-2.5 py-1 text-xs font-medium capitalize text-content-muted"
                                    >
                                        {section}
                                    </li>
                                ))}
                            </ul>
                        </section>
                    )}

                    <motionActions className="mt-8 flex flex-wrap gap-3">
                        {!template.is_premium && (
                            <Button href={`/portfolios/create?template_id=${template.id}`} variant="primary">
                                Create portfolio with this template
                            </Button>
                        )}
                        {template.is_premium && (
                            <p className="text-sm text-content-muted">
                                Premium purchase is not enabled yet. Choose a free template or upgrade your plan.
                            </p>
                        )}
                        {canManage && (
                            <Button href={`/admin/templates/${template.id}/edit`} variant="secondary">
                                Edit template (admin)
                            </Button>
                        )}
                    </motionActions>
                </motionDetails>
            </div>
        </AppLayout>
    );
}

function motionPlaceholder({ name, slug }) {
    return (
        <div className="flex h-full min-h-[240px] flex-col justify-center p-8">
            <p className="text-sm font-medium uppercase tracking-wide text-brand">Layout preview</p>
            <p className="mt-4 text-3xl font-semibold text-content">{name}</p>
            <p className="mt-2 font-mono text-sm text-content-muted">{slug}</p>
        </div>
    );
}

function motionDetails({ children, className = '' }) {
    return <div className={className}>{children}</div>;
}

function motionActions({ children, className = '' }) {
    return <div className={className}>{children}</div>;
}

function formatPrice(price, currency) {
    const amount = Number(price);
    if (amount === 0) {
        return 'Free';
    }

    return `${currency} ${amount.toFixed(2)}`;
}