import AppLayout from '@core/Layouts/AppLayout';
import Button from '@core/Components/Button';
import { Head, Link } from '@inertiajs/react';

export default function AdminPlansIndex({ auth, plans, flash }) {
    return (
        <AppLayout auth={auth} title="Manage plans">
            <Head title="Manage plans" />

            {flash?.success && (
                <div className="mb-6 rounded-xl border border-success-border bg-success-surface px-4 py-3 text-sm text-success-content">
                    {flash.success}
                </div>
            )}

            <div className="mb-6 flex justify-end">
                <Button href="/admin/plans/create" variant="primary">
                    New plan
                </Button>
            </div>

            <p className="mb-4 text-sm text-content-muted">Billing prices and limits applied to subscriptions.</p>

            <div className="overflow-x-auto rounded-2xl border border-border bg-surface-elevated shadow-sm">
                <table className="min-w-full text-left text-sm">
                    <thead className="border-b border-border bg-surface-muted text-content-muted">
                        <tr>
                            <th className="px-4 py-3 font-medium">Name</th>
                            <th className="px-4 py-3 font-medium">Slug</th>
                            <th className="px-4 py-3 font-medium">Price</th>
                            <th className="px-4 py-3 font-medium">Portfolios</th>
                            <th className="px-4 py-3 font-medium">Active</th>
                            <th className="px-4 py-3 font-medium" />
                        </tr>
                    </thead>
                    <tbody>
                        {plans.map((plan) => (
                            <tr key={plan.id} className="border-b border-border last:border-0">
                                <td className="px-4 py-3 font-medium text-content">{plan.name}</td>
                                <td className="px-4 py-3 font-mono text-xs text-content-muted">{plan.slug}</td>
                                <td className="px-4 py-3 text-content">
                                    {Number(plan.price) === 0 ? 'Free' : `${plan.currency} ${plan.price}`}
                                </td>
                                <td className="px-4 py-3 text-content">{plan.portfolio_limit}</td>
                                <td className="px-4 py-3">
                                    <span
                                        className={`rounded-full px-2 py-0.5 text-xs font-medium ${
                                            plan.is_active
                                                ? 'bg-success-surface text-success-content'
                                                : 'bg-surface-muted text-content-muted'
                                        }`}
                                    >
                                        {plan.is_active ? 'Yes' : 'No'}
                                    </span>
                                </td>
                                <td className="px-4 py-3 text-right">
                                    <Link
                                        href={`/admin/plans/${plan.id}/edit`}
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