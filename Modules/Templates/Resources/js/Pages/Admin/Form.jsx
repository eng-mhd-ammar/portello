import AppLayout from '@core/Layouts/AppLayout';
import Button from '@core/Components/Button';
import Checkbox from '@core/Components/Checkbox';
import TextInput from '@core/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';

const defaultSchema = JSON.stringify({ sections: ['hero', 'projects', 'contact'] }, null, 2);

export default function AdminTemplateForm({ auth, template }) {
    const isEdit = Boolean(template?.id);

    const schemaText =
        template?.schema != null ? JSON.stringify(template.schema, null, 2) : isEdit ? '{}' : defaultSchema;

    const { data, setData, post, put, processing, errors } = useForm({
        name: template?.name ?? '',
        slug: template?.slug ?? '',
        description: template?.description ?? '',
        preview_image: template?.preview_image ?? '',
        price: template?.price ?? '0',
        currency: template?.currency ?? 'USD',
        sort_order: template?.sort_order ?? 0,
        is_premium: template?.is_premium ?? false,
        is_active: template?.is_active ?? true,
        schema: schemaText,
    });

    const submit = (e) => {
        e.preventDefault();

        if (isEdit) {
            put(`/admin/templates/${template.id}`);
        } else {
            post('/admin/templates');
        }
    };

    return (
        <AppLayout auth={auth} title={isEdit ? 'Edit template' : 'New template'}>
            <Head title={isEdit ? 'Edit template' : 'New template'} />

            <form onSubmit={submit} className="mx-auto max-w-2xl space-y-5">
                <TextInput
                    id="name"
                    label="Name"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    error={errors.name}
                />
                <TextInput
                    id="slug"
                    label="Slug"
                    value={data.slug}
                    onChange={(e) => setData('slug', e.target.value)}
                    error={errors.slug}
                />
                <div>
                    <label htmlFor="description" className="block text-sm font-medium text-content">
                        Description
                    </label>
                    <textarea
                        id="description"
                        rows={3}
                        value={data.description}
                        onChange={(e) => setData('description', e.target.value)}
                        className="mt-1.5 w-full rounded-xl border border-border-strong bg-surface-elevated px-3.5 py-2.5 text-sm text-content shadow-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20"
                    />
                    {errors.description && <p className="mt-1.5 text-sm text-danger">{errors.description}</p>}
                </div>
                <TextInput
                    id="preview_image"
                    label="Preview image URL"
                    value={data.preview_image}
                    onChange={(e) => setData('preview_image', e.target.value)}
                    placeholder="https://…"
                    error={errors.preview_image}
                />
                <FieldGrid>
                    <TextInput
                        id="price"
                        label="Price"
                        type="number"
                        step="0.01"
                        min="0"
                        value={data.price}
                        onChange={(e) => setData('price', e.target.value)}
                        error={errors.price}
                    />
                    <TextInput
                        id="currency"
                        label="Currency"
                        value={data.currency}
                        onChange={(e) => setData('currency', e.target.value.toUpperCase())}
                        maxLength={3}
                        error={errors.currency}
                    />
                </FieldGrid>
                <TextInput
                    id="sort_order"
                    label="Sort order"
                    type="number"
                    min={0}
                    value={data.sort_order}
                    onChange={(e) => setData('sort_order', Number(e.target.value))}
                    error={errors.sort_order}
                />
                <Checkbox
                    id="is_premium"
                    label="Premium template (not selectable until purchased)"
                    checked={data.is_premium}
                    onChange={(e) => setData('is_premium', e.target.checked)}
                />
                <Checkbox
                    id="is_active"
                    label="Active in catalog"
                    checked={data.is_active}
                    onChange={(e) => setData('is_active', e.target.checked)}
                />
                <div>
                    <label htmlFor="schema" className="block text-sm font-medium text-content">
                        Schema (JSON)
                    </label>
                    <textarea
                        id="schema"
                        rows={8}
                        value={data.schema}
                        onChange={(e) => setData('schema', e.target.value)}
                        className="mt-1.5 w-full rounded-xl border border-border-strong bg-surface-elevated px-3.5 py-2.5 font-mono text-xs text-content shadow-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20"
                        spellCheck={false}
                    />
                    {errors.schema && <p className="mt-1.5 text-sm text-danger">{errors.schema}</p>}
                </div>
                <div className="flex flex-wrap gap-3 pt-2">
                    <Button type="submit" variant="primary" disabled={processing}>
                        {isEdit ? 'Save changes' : 'Create template'}
                    </Button>
                    <Link href="/admin/templates" className="text-sm font-medium text-brand hover:text-brand-hover">
                        Cancel
                    </Link>
                </div>
            </form>
        </AppLayout>
    );
}

function FieldGrid({ children }) {
    return <div className="grid gap-5 sm:grid-cols-2">{children}</div>;
}