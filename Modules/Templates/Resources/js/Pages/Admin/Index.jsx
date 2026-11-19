import AppLayout from '@core/Layouts/AppLayout';
import Button from '@core/Components/Button';
import { Head, Link } from '@inertiajs/react';

export default function AdminTemplatesIndex({ auth, templates, flash }) {
    return (
        <AppLayout auth={auth} title="Manage templates">
            <Head title="Manage templates" />

            {flash?.success && (
                <div className="mb-6 rounded-xl border border-success-border bg-success-surface px-4 py-3 text-sm text-success-content">
                    {flash.success}
                </div>
            )}

            <div className="mb-6 flex justify-end">
                <Button href="/admin/templates/create" variant="primary">
                    New template
                </Button>
            </div>

            <p className="mb-4 text-sm text-content-muted">
                Templates shown in the catalog when marked active. Premium flag blocks selection until billing is wired.
            </p>

            <div className="overflow-x-auto rounded-2xl border border-border bg-surface-elevated shadow-sm">
                <table className="min-w-full text-left text-sm">
                    <thead className="border-b border-border bg-surface-muted text-content-muted">
                        <tr>
                            <th className="px-4 py-3 font-medium">Name</th>
                            <th className="px-4 py-3 font-medium">Slug</th>
                            <th className="px-4 py-3 font-medium">Tier</th>
                            <th className="px-4 py-3 font-medium">Price</th>
                            <th className="px-4 py-3 font-medium">Active</th>
                            <th className="px-4 py-3 font-medium" />
                        </tr>
                    </thead>
                    <tbody>
                        {templates.map((template) => (
                            <tr key={template.id} className="border-b border-border last:border-0">
                                <td className="px-4 py-3 font-medium text-content">{template.name}</td>
                                <td className="px-4 py-3 font-mono text-xs text-content-muted">{template.slug}</td>
                                <td className="px-4 py-3 text-content">{template.is_premium ? 'Premium' : 'Free'}</td>
                                <td className="px-4 py-3 text-content">
                                    {Number(template.price) === 0 ? '—' : `${template.currency} ${template.price}`}
                                </td>
                                <td className="px-4 py-3">
                                    <span
                                        className={`rounded-full px-2 py-0.5 text-xs font-medium ${
                                            template.is_active
                                                ? 'bg-success-surface text-success-content'
                                                : 'bg-surface-muted text-content-muted'
                                        }`}
                                    >
                                        {template.is_active ? 'Yes' : 'No'}
                                    </span>
                                </td>
                                <td className="px-4 py-3 text-right">
                                    <Link
                                        href={`/admin/templates/${template.id}/edit`}
                                        className="text-sm font-medium text-brand hover:text-brand-hover"
                                    >
                                        Edit
                                    </Link>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </AppLayout>
    );
}