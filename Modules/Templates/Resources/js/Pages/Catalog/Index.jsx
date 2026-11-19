import AppLayout from '@core/Layouts/AppLayout';
import Button from '@core/Components/Button';
import { Head, Link } from '@inertiajs/react';

export default function TemplatesCatalogIndex({ auth, templates }) {
    return (
        <AppLayout auth={auth} title="Template catalog">
            <Head title="Template catalog" />

            <p className="mb-8 max-w-2xl text-sm text-content-muted">
                Pick a layout for your public portfolio site. Free templates are available on every plan; premium
                templates will require purchase when the store is enabled.
            </p>

            {templates.length === 0 ? (
                <p className="text-sm text-content-muted">No templates in the catalog yet.</p>
            ) : (
                <ul className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    {templates.map((template) => (
                        <li
                            key={template.id}
                            className="flex flex-col overflow-hidden rounded-2xl border border-border bg-surface-elevated shadow-sm"
                        >
                            <TemplatePreview template={template} />
                            <div className="flex flex-1 flex-col p-5">
                                <div className="flex items-start justify-between gap-2">
                                    <h2 className="text-lg font-semibold text-content">{template.name}</h2>
                                    {template.is_premium ? (
                                        <span className="rounded-full bg-brand-soft px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-brand">
                                            Premium
                                        </span>
                                    ) : (
                                        <span className="rounded-full bg-surface-muted px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-content-muted">
                                            Free
                                        </span>
                                    )}
                                </div>
                                <p className="mt-2 flex-1 text-sm text-content-muted">{template.description}</p>
                                {template.sections?.length > 0 && (
                                    <p className="mt-3 text-xs text-content-subtle">
                                        Sections: {template.sections.join(' · ')}
                                    </p>
                                )}
                                <div className="mt-5 flex flex-wrap gap-2">
                                    <Button href={`/templates/${template.id}`} variant="secondary" className="flex-1">
                                        Details
                                    </Button>
                                    <Button
                                        href={`/portfolios/create?template_id=${template.id}`}
                                        variant="primary"
                                        className="flex-1"
                                        disabled={template.is_premium}
                                    >
                                        {template.is_premium ? 'Premium' : 'Use template'}
                                    </Button>
                                </div>
                            </div>
                        </li>
                    ))}
                </ul>
            )}

            <p className="mt-8 text-sm text-content-muted">
                Managing sites?{' '}
                <Link href="/portfolios" className="font-medium text-brand hover:text-brand-hover">
                    Go to portfolios
                </Link>
            </p>
        </AppLayout>
    );
}

function TemplatePreview({ template }) {
    const initial = template.name?.charAt(0) ?? 'T';

    return (
        <div className="relative aspect-[16/10] border-b border-border bg-gradient-to-br from-surface-muted via-surface-elevated to-brand-soft">
            {template.preview_image ? (
                <img src={template.preview_image} alt="" className="h-full w-full object-cover" />
            ) : (
                <div className="flex h-full flex-col justify-between p-5">
                    <span className="text-xs font-medium uppercase tracking-wide text-content-subtle">Preview</span>
                    <div>
                        <span className="flex h-12 w-12 items-center justify-center rounded-xl bg-brand text-lg font-semibold text-brand-fg shadow-md shadow-brand/20">
                            {initial}
                        </span>
                        <p className="mt-3 text-sm font-medium text-content">{template.slug}</p>
                    </div>
                </div>
            )}
        </div>
    );
}