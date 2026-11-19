import AppLayout from '@core/Layouts/AppLayout';
import Button from '@core/Components/Button';
import Checkbox from '@core/Components/Checkbox';
import TextInput from '@core/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';

export default function AdminPlanForm({ auth, plan }) {
    const isEdit = Boolean(plan?.id);

    const { data, setData, post, put, processing, errors } = useForm({
        name: plan?.name ?? '',
        slug: plan?.slug ?? '',
        description: plan?.description ?? '',
        price: plan?.price ?? '0',
        currency: plan?.currency ?? 'USD',
        interval: plan?.interval ?? 'month',
        portfolio_limit: plan?.portfolio_limit ?? 1,
        sort_order: plan?.sort_order ?? 0,
        custom_domain_enabled: plan?.custom_domain_enabled ?? false,
        remove_branding: plan?.remove_branding ?? false,
        is_active: plan?.is_active ?? true,
    });

    const submit = (e) => {
        e.preventDefault();

        if (isEdit) {
            put(`/admin/plans/${plan.id}`);
        } else {
            post('/admin/plans');
        }
    };

    return (
        <AppLayout auth={auth} title={isEdit ? 'Edit plan' : 'New plan'}>
            <Head title={isEdit ? 'Edit plan' : 'New plan'} />

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
                <div className="grid gap-5 sm:grid-cols-2">
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
                </div>
                <FieldGrid>
                    <TextInput
                        id="interval"
                        label="Interval"
                        value={data.interval}
                        onChange={(e) => setData('interval', e.target.value)}
                        error={errors.interval}
                    />
                    <TextInput
                        id="portfolio_limit"
                        label="Portfolio limit"
                        type="number"
                        min={1}
                        value={data.portfolio_limit}
                        onChange={(e) => setData('portfolio_limit', Number(e.target.value))}
                        error={errors.portfolio_limit}
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
                    id="custom_domain_enabled"
                    label="Custom domain enabled"
                    checked={data.custom_domain_enabled}
                    onChange={(e) => setData('custom_domain_enabled', e.target.checked)}
                />
                <Checkbox
                    id="remove_branding"
                    label="Remove Portello branding"
                    checked={data.remove_branding}
                    onChange={(e) => setData('remove_branding', e.target.checked)}
                />
                <Checkbox
                    id="is_active"
                    label="Plan is active (visible in catalog)"
                    checked={data.is_active}
                    onChange={(e) => setData('is_active', e.target.checked)}
                />
                <div className="flex flex-wrap gap-3 pt-2">
                    <Button type="submit" variant="primary" disabled={processing}>
                        {isEdit ? 'Save changes' : 'Create plan'}
                    </Button>
                    <Link href="/admin/plans" className="text-sm font-medium text-brand hover:text-brand-hover">
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