import AppLayout from '@core/Layouts/AppLayout';
import Button from '@core/Components/Button';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

export default function BillingIndex({ auth, subscription, plans, flash }) {
    const [switching, setSwitching] = useState(false);

    const currentPlanId = subscription?.plan?.id;

    const selectPlan = (planId) => {
        if (planId === currentPlanId) {
            return;
        }

        setSwitching(true);
        router.post(
            '/billing/subscription',
            { plan_id: planId },
            {
                preserveScroll: true,
                onFinish: () => setSwitching(false),
            },
        );
    };

    return (
        <AppLayout auth={auth} title="Subscription">
            <Head title="Subscription" />

            {flash?.success && (
                <div className="mb-6 rounded-xl border border-success-border bg-success-surface px-4 py-3 text-sm text-success-content">
                    {flash.success}
                </div>
            )}

            {subscription ? (
                <section className="mb-8 rounded-2xl border border-border bg-surface-elevated p-6 shadow-sm">
                    <p className="text-sm font-medium text-content-muted">Current plan</p>
                    <h2 className="mt-1 text-2xl font-semibold text-content">{subscription.plan?.name}</h2>
                    <p className="mt-2 text-sm text-content-muted">{subscription.plan?.description}</p>
                    <dl className="mt-4 grid gap-3 text-sm sm:grid-cols-3">
                        <ContentItem label="Status" value={subscription.status_label} />
                        <ContentItem label="Portfolio limit" value={subscription.portfolio_limit} />
                        <ContentItem
                            label="Price"
                            value={formatPrice(subscription.plan?.price, subscription.plan?.currency, subscription.plan?.interval)}
                        />
                    </dl>
                </section>
            ) : (
                <p className="mb-8 text-sm text-content-muted">You do not have an active subscription yet.</p>
            )}

            <h2 className="text-lg font-semibold text-content">Available plans</h2>
            <p className="mt-1 text-sm text-content-muted">
                Switch plans instantly (payment integration coming later).
            </p>

            <ul className="mt-6 grid gap-4 lg:grid-cols-2">
                {plans.map((plan) => {
                    const isCurrent = plan.id === currentPlanId;

                    return (
                        <li
                            key={plan.id}
                            className={`rounded-2xl border p-6 shadow-sm ${
                                isCurrent ? 'border-brand bg-brand-soft' : 'border-border bg-surface-elevated'
                            }`}
                        >
                            <div className="flex items-start justify-between gap-3">
                                <div>
                                    <h3 className="text-xl font-semibold text-content">{plan.name}</h3>
                                    <p className="mt-2 text-sm text-content-muted">{plan.description}</p>
                                </div>
                                {isCurrent && (
                                    <span className="rounded-full bg-brand px-2.5 py-0.5 text-xs font-medium text-brand-fg">
                                        Current
                                    </span>
                                )}
                            </div>
                            <p className="mt-4 text-3xl font-semibold text-content">
                                {formatPrice(plan.price, plan.currency, plan.interval)}
                            </p>
                            <ul className="mt-4 space-y-1 text-sm text-content-muted">
                                <li>Up to {plan.portfolio_limit} portfolio(s)</li>
                                <li>{plan.custom_domain_enabled ? 'Custom domain' : 'Subdomain only'}</li>
                                <li>{plan.remove_branding ? 'No Portello branding' : 'Portello branding on sites'}</li>
                            </ul>
                            <Button
                                type="button"
                                variant={isCurrent ? 'secondary' : 'primary'}
                                className="mt-6 w-full"
                                disabled={isCurrent || switching}
                                onClick={() => selectPlan(plan.id)}
                            >
                                {isCurrent ? 'Current plan' : 'Switch to this plan'}
                            </Button>
                        </li>
                    );
                })}
            </ul>
        </AppLayout>
    );
}

function ContentItem({ label, value }) {
    return (
        <div>
            <dt className="text-content-muted">{label}</dt>
            <dd className="font-medium text-content">{value}</dd>
        </div>
    );
}

function formatPrice(price, currency, interval) {
    const amount = Number(price);

    if (amount === 0) {
        return 'Free';
    }

    return `${currency} ${amount.toFixed(2)} / ${interval}`;
}