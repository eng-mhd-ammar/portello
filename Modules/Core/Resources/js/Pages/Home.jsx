import { Head, Link } from '@inertiajs/react';
import Button from '@core/Components/Button';
import MarketingLayout from '@core/Layouts/MarketingLayout';

const features = [
    {
        title: 'Template marketplace',
        description: 'Free starters and premium layouts—buy once or unlock with your plan.',
    },
    {
        title: 'Custom domains',
        description: 'Subdomain on day one. Connect your domain with guided DNS when you are ready.',
    },
    {
        title: 'Portfolio editor',
        description: 'Structured sections first, blocks later—ship a credible site without a blank canvas.',
    },
    {
        title: 'Built-in billing',
        description: 'Subscriptions for publishing limits and one-time template purchases in one account.',
    },
];

const plans = [
    {
        name: 'Free',
        price: '$0',
        detail: 'One portfolio, starter templates, Portello subdomain.',
        highlighted: false,
    },
    {
        name: 'Pro',
        price: 'Soon',
        detail: 'Multiple sites, premium templates, custom domain & priority support.',
        highlighted: true,
    },
];

export default function Home({ auth }) {
    const user = auth?.user;

    return (
        <MarketingLayout auth={auth}>
            <Head title="Portfolio sites on your domain" />

            <section className="relative overflow-hidden">
                <div className="landing-hero-glow pointer-events-none absolute inset-0" />
                <div className="landing-grid pointer-events-none absolute inset-0 [mask-image:radial-gradient(ellipse_at_center,black,transparent_75%)]" />

                <div className="relative mx-auto max-w-7xl px-4 pb-24 pt-16 sm:px-6 lg:px-8 lg:pb-32 lg:pt-20">
                    <div className="mx-auto max-w-3xl text-center">
                        <p className="inline-flex items-center gap-2 rounded-full border border-brand-muted bg-surface-elevated/80 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-brand shadow-sm backdrop-blur">
                            <span className="h-1.5 w-1.5 rounded-full bg-brand" />
                            Portfolio SaaS · Inertia + React
                        </p>
                        <h1 className="mt-8 text-4xl font-semibold tracking-tight text-content sm:text-5xl lg:text-6xl lg:leading-[1.08]">
                            Publish work that wins trust{' '}
                            <span className="bg-gradient-to-r from-brand to-brand-hover bg-clip-text text-transparent">
                                on your own domain
                            </span>
                        </h1>
                        <p className="mx-auto mt-6 max-w-2xl text-lg leading-relaxed text-content-muted">
                            Portello is the control panel for your portfolio brand—templates, domains, billing, and
                            multiple sites without stitching tools together.
                        </p>
                        <div className="mt-10 flex flex-col items-center justify-center gap-3 sm:flex-row">
                            {user ? (
                                <Button href="/dashboard" variant="primary" className="min-w-[200px] px-6 py-3 text-base">
                                    Open dashboard
                                </Button>
                            ) : (
                                <>
                                    <Button href="/register" variant="primary" className="min-w-[200px] px-6 py-3 text-base">
                                        Start free
                                    </Button>
                                    <Button href="/login" variant="secondary" className="min-w-[160px] px-6 py-3 text-base">
                                        Log in
                                    </Button>
                                </>
                            )}
                        </div>
                    </div>

                    <HeroPreview />
                </div>
            </section>

            <section className="border-y border-border bg-surface-elevated py-12">
                <div className="mx-auto grid max-w-7xl gap-10 px-4 sm:grid-cols-3 sm:px-6 lg:px-8">
                    {[
                        { value: '< 5 min', label: 'Typical signup to dashboard' },
                        { value: '6-digit', label: 'Email OTP verification' },
                        { value: '∞', label: 'Room to grow with plans' },
                    ].map((item) => (
                        <div key={item.label} className="text-center">
                            <p className="text-3xl font-semibold tracking-tight text-content">{item.value}</p>
                            <p className="mt-2 text-sm text-content-muted">{item.label}</p>
                        </div>
                    ))}
                </div>
            </section>

            <section id="features" className="mx-auto max-w-7xl scroll-mt-24 px-4 py-24 sm:px-6 lg:px-8">
                <div className="max-w-2xl">
                    <h2 className="text-3xl font-semibold tracking-tight text-content sm:text-4xl">
                        Built for portfolios, not generic pages
                    </h2>
                    <p className="mt-4 text-lg text-content-muted">
                        Every screen is designed around how freelancers, agencies, and creators actually ship work online.
                    </p>
                </div>
                <div className="mt-14 grid gap-5 sm:grid-cols-2">
                    {features.map((feature) => (
                        <article
                            key={feature.title}
                            className="group relative overflow-hidden rounded-3xl border border-border bg-surface-elevated p-8 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-brand/10"
                        >
                            <div className="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-gradient-to-br from-brand/25 to-brand-muted blur-2xl transition group-hover:scale-110" />
                            <h3 className="relative text-xl font-semibold text-content">{feature.title}</h3>
                            <p className="relative mt-3 text-sm leading-relaxed text-content-muted">{feature.description}</p>
                        </article>
                    ))}
                </div>
            </section>

            <section id="how-it-works" className="scroll-mt-24 bg-surface-inverse py-24 text-content-inverse">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <h2 className="text-3xl font-semibold tracking-tight sm:text-4xl">Three steps to go live</h2>
                    <div className="mt-14 grid gap-6 lg:grid-cols-3">
                        {[
                            {
                                step: '1',
                                title: 'Create & verify',
                                body: 'Register with email OTP—same flow you expect from modern apps.',
                            },
                            {
                                step: '2',
                                title: 'Design with templates',
                                body: 'Pick a layout and tune fixed sections before advanced blocks land.',
                            },
                            {
                                step: '3',
                                title: 'Publish everywhere',
                                body: 'Share your Portello link or connect DNS for your custom domain.',
                            },
                        ].map((item) => (
                            <div
                                key={item.step}
                                className="rounded-2xl border border-border/20 bg-surface-elevated/5 p-8 backdrop-blur-sm"
                            >
                                <span className="inline-flex h-8 w-8 items-center justify-center rounded-full bg-brand/25 text-sm font-semibold text-brand">
                                    {item.step}
                                </span>
                                <h3 className="mt-5 text-xl font-semibold">{item.title}</h3>
                                <p className="mt-3 text-sm leading-relaxed text-content-on-inverse">{item.body}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            <section id="pricing" className="mx-auto max-w-7xl scroll-mt-24 px-4 py-24 sm:px-6 lg:px-8">
                <div className="max-w-2xl">
                    <h2 className="text-3xl font-semibold tracking-tight text-content sm:text-4xl">Simple pricing to start</h2>
                    <p className="mt-4 text-lg text-content-muted">Free tier today. Pro tier unlocks as billing modules go live.</p>
                </div>
                <div className="mt-12 grid gap-6 md:grid-cols-2">
                    {plans.map((plan) => (
                        <div
                            key={plan.name}
                            className={`rounded-3xl border p-8 ${
                                plan.highlighted
                                    ? 'border-brand/40 bg-gradient-to-b from-brand-soft to-surface-elevated shadow-xl shadow-brand/15'
                                    : 'border-border bg-surface-elevated shadow-sm'
                            }`}
                        >
                            <h3 className="text-lg font-semibold text-content">{plan.name}</h3>
                            <p className="mt-3 text-4xl font-semibold tracking-tight text-content">{plan.price}</p>
                            <p className="mt-4 text-sm leading-relaxed text-content-muted">{plan.detail}</p>
                            {!user && (
                                <Button
                                    href="/register"
                                    variant={plan.highlighted ? 'primary' : 'secondary'}
                                    className="mt-8 w-full py-3"
                                >
                                    {plan.highlighted ? 'Join waitlist' : 'Get started'}
                                </Button>
                            )}
                        </div>
                    ))}
                </div>
            </section>

            <section className="border-t border-border bg-surface-elevated py-20">
                <div className="mx-auto max-w-3xl px-4 text-center sm:px-6">
                    <h2 className="text-3xl font-semibold tracking-tight text-content">Your next client visit starts here</h2>
                    <p className="mt-4 text-content-muted">
                        Spin up an account, verify your email, and land in the dashboard with a free plan—portfolio modules
                        ship on top of this foundation.
                    </p>
                    <div className="mt-8 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
                        {user ? (
                            <Button href="/dashboard" variant="primary" className="px-8 py-3">
                                Dashboard
                            </Button>
                        ) : (
                            <>
                                <Button href="/register" variant="primary" className="px-8 py-3">
                                    Create account
                                </Button>
                                <Link href="/login" className="text-sm font-medium text-brand hover:text-brand-hover">
                                    Sign in instead
                                </Link>
                            </>
                        )}
                    </div>
                </div>
            </section>
        </MarketingLayout>
    );
}

function HeroPreview() {
    return (
        <div className="mx-auto mt-16 max-w-5xl lg:mt-20">
            <div className="rounded-[2rem] border border-border bg-surface-elevated/90 p-2 shadow-2xl shadow-brand/10 ring-1 ring-border backdrop-blur">
                <div className="rounded-[1.5rem] bg-gradient-to-br from-surface-muted via-surface-elevated to-brand-soft p-6 sm:p-10">
                    <div className="flex items-center justify-between gap-4">
                        <div className="flex gap-1.5">
                            <span className="h-3 w-3 rounded-full bg-red-400/90" />
                            <span className="h-3 w-3 rounded-full bg-amber-400/90" />
                            <span className="h-3 w-3 rounded-full bg-emerald-400/90" />
                        </div>
                        <span className="rounded-full bg-surface-elevated px-3 py-1 text-xs font-medium text-content-muted shadow-sm">
                            yourname.portello.app
                        </span>
                    </div>
                    <div className="mt-8 grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                        <div className="space-y-4">
                            <div className="h-8 w-2/3 max-w-xs rounded-lg bg-surface-inverse/90" />
                            <div className="h-4 w-full max-w-md rounded bg-border" />
                            <div className="h-4 w-5/6 max-w-sm rounded bg-surface-muted" />
                            <div className="flex gap-2 pt-2">
                                <span className="h-9 w-28 rounded-xl bg-brand/90" />
                                <span className="h-9 w-24 rounded-xl bg-surface-elevated ring-1 ring-border" />
                            </div>
                        </div>
                        <div className="grid grid-cols-2 gap-3">
                            <PreviewCard brand />
                            <PreviewCard />
                            <PreviewCard className="col-span-2 h-28" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

function PreviewCard({ brand = false, className = '' }) {
    const toneClass = brand
        ? 'bg-gradient-to-br from-brand/40 to-brand-muted'
        : 'bg-border/80';

    return <div className={`aspect-[4/3] rounded-2xl ${toneClass} ${className}`} />;
}