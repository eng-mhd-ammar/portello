import AppLayout from '@core/Layouts/AppLayout';
import Button from '@core/Components/Button';
import TextInput from '@core/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';

export default function PortfoliosCreate({ templates, baseDomain, initialTemplateId }) {
    const defaultTemplate =
        initialTemplateId && templates.some((t) => t.id === initialTemplateId)
            ? initialTemplateId
            : templates.find((t) => !t.is_premium)?.id ?? templates[0]?.id ?? '';

    const { data, setData, post, processing, errors } = useForm({
        name: '',
        slug: '',
        template_id: defaultTemplate,
    });

    const submit = (e) => {
        e.preventDefault();
        post('/portfolios');
    };

    const selectableTemplates = templates.filter((t) => !t.is_premium);

    return (
        <AppLayout title="New portfolio">
            <Head title="New portfolio" />

            {templates.length === 0 ? (
                <div className="rounded-2xl border border-dashed border-border-strong bg-surface-muted px-6 py-10 text-center">
                    <p className="text-sm text-content-muted">No templates available. Ask an admin to seed the catalog.</p>
                    <Button href="/portfolios" variant="secondary" className="mt-4">
                        Back to portfolios
                    </Button>
                </div>
            ) : (
                <>
                    <p className="mb-6 text-sm text-content-muted">
                        Your subdomain will be{' '}
                        <span className="font-mono text-content">
                            {data.slug || 'your-slug'}.{baseDomain}
                        </span>
                    </p>

                    <form onSubmit={submit} className="max-w-lg space-y-5">
                        <TextInput
                            id="name"
                            label="Portfolio name"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            error={errors.name}
                        />
                        <TextInput
                            id="slug"
                            label="Subdomain slug"
                            value={data.slug}
                            onChange={(e) => setData('slug', e.target.value)}
                            placeholder="e.g. jane-doe"
                            error={errors.slug}
                        />
                        <div>
                            <FieldLabelRow>
                                <label htmlFor="template_id" className="block text-sm font-medium text-content">
                                    Template
                                </label>
                                <Link href="/templates" className="text-xs font-medium text-brand hover:text-brand-hover">
                                    Browse catalog
                                </Link>
                            </FieldLabelRow>
                            <select
                                id="template_id"
                                value={data.template_id}
                                onChange={(e) => setData('template_id', e.target.value)}
                                className="mt-1.5 w-full rounded-xl border border-border-strong bg-surface-elevated px-3.5 py-2.5 text-sm text-content shadow-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20"
                            >
                                {selectableTemplates.length === 0 ? (
                                    <option value="">No free templates</option>
                                ) : (
                                    selectableTemplates.map((template) => (
                                        <option key={template.id} value={template.id}>
                                            {template.name}
                                        </option>
                                    ))
                                )}
                                {templates.some((t) => t.is_premium) && (
                                    <optgroup label="Premium (coming soon)">
                                        {templates
                                            .filter((t) => t.is_premium)
                                            .map((template) => (
                                                <option key={template.id} value={template.id} disabled>
                                                    {template.name} (premium)
                                                </option>
                                            ))}
                                    </optgroup>
                                )}
                            </select>
                            {errors.template_id && <p className="mt-1.5 text-sm text-danger">{errors.template_id}</p>}
                        </div>
                        <div className="flex flex-wrap gap-3 pt-2">
                            <Button
                                type="submit"
                                variant="primary"
                                disabled={processing || selectableTemplates.length === 0}
                            >
                                Create portfolio
                            </Button>
                            <Link href="/portfolios" className="text-sm font-medium text-brand hover:text-brand-hover">
                                Cancel
                            </Link>
                        </div>
                    </form>
                </>
            )}
        </AppLayout>
    );
}

function FieldLabelRow({ children }) {
    return <div className="mb-1.5 flex items-center justify-between gap-2">{children}</div>;
}