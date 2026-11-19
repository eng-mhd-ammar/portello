import { Link } from '@inertiajs/react';
import ThemeToggle from '@core/Components/ThemeToggle';

export default function AuthLayout({ title, description, children, footer }) {
    return (
        <div className="flex min-h-screen flex-col bg-surface lg:flex-row">
            <aside className="relative overflow-hidden bg-surface-inverse px-6 py-12 text-content-inverse lg:w-[42%] lg:px-12 lg:py-16">
                <div className="auth-marketing-glow pointer-events-none absolute inset-0 opacity-60" />
                <div className="relative mx-auto max-w-md lg:mx-0">
                    <Link href="/" className="inline-flex items-center gap-2 text-content-inverse/90 hover:text-content-inverse">
                        <span className="flex h-9 w-9 items-center justify-center rounded-xl bg-brand/20 text-sm font-semibold text-brand ring-1 ring-brand/30">
                            P
                        </span>
                        <span className="text-lg font-semibold">Portello</span>
                    </Link>
                    <h2 className="mt-10 text-3xl font-semibold tracking-tight sm:text-4xl">
                        Your portfolio, your brand, your domain.
                    </h2>
                    <p className="mt-4 text-base leading-relaxed text-content-on-inverse">
                        Pick a template, customize sections, publish on a subdomain or custom domain, and grow with
                        plans built for creators.
                    </p>
                    <ul className="mt-10 space-y-4 text-sm text-content-on-inverse">
                        <li className="flex gap-3">
                            <span className="mt-0.5 text-brand">✓</span>
                            Free and premium templates in one place
                        </li>
                        <li className="flex gap-3">
                            <span className="mt-0.5 text-brand">✓</span>
                            Subscriptions and one-time template purchases
                        </li>
                        <li className="flex gap-3">
                            <span className="mt-0.5 text-brand">✓</span>
                            Multiple portfolios from a single account
                        </li>
                    </ul>
                </div>
            </aside>
            <main className="relative flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:px-12">
                <div className="absolute right-4 top-4 sm:right-6 lg:right-12">
                    <ThemeToggle />
                </div>
                <div className="mx-auto w-full max-w-md">
                    <h1 className="text-2xl font-semibold tracking-tight text-content">{title}</h1>
                    {description && <p className="mt-2 text-sm text-content-muted">{description}</p>}
                    <div className="mt-8">{children}</div>
                    {footer && <div className="mt-8 text-center text-sm text-content-muted sm:text-left">{footer}</div>}
                </div>
            </main>
        </div>
    );
}