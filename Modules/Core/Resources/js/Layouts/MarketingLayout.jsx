import { Link } from '@inertiajs/react';
import Button from '@core/Components/Button';
import ThemeToggle from '@core/Components/ThemeToggle';
import UserMenu from '@core/Components/UserMenu';

export default function MarketingLayout({ auth, children }) {
    const user = auth?.user;

    return (
        <div className="min-h-screen bg-surface text-content">
            <header className="sticky top-0 z-40 border-b border-border/80 bg-surface-elevated/85 backdrop-blur-xl">
                <div className="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3.5 sm:px-6 lg:px-8">
                    <Link href="/" className="group flex items-center gap-2.5">
                        <span className="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-brand to-brand-hover text-sm font-semibold text-brand-fg shadow-lg shadow-brand/25">
                            P
                        </span>
                        <span className="text-lg font-semibold tracking-tight text-content group-hover:text-brand">
                            Portello
                        </span>
                    </Link>

                    <nav className="hidden items-center gap-8 text-sm font-medium text-content-muted md:flex">
                        <a href="#features" className="transition hover:text-content">
                            Features
                        </a>
                        <a href="#how-it-works" className="transition hover:text-content">
                            How it works
                        </a>
                        <a href="#pricing" className="transition hover:text-content">
                            Pricing
                        </a>
                    </nav>

                    <div className="flex items-center gap-2 sm:gap-3">
                        <ThemeToggle />
                        {user ? (
                            <UserMenu user={user} />
                        ) : (
                            <>
                                <Button href="/login" variant="ghost" className="hidden sm:inline-flex">
                                    Log in
                                </Button>
                                <Button href="/register" variant="primary">
                                    Create account
                                </Button>
                            </>
                        )}
                    </div>
                </div>
            </header>
            {children}
            <footer className="border-t border-border bg-surface-elevated">
                <div className="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-12 text-sm text-content-muted sm:flex-row sm:items-center sm:justify-between lg:px-8">
                    <p>© {new Date().getFullYear()} Portello · Portfolio publishing for modern creators.</p>
                    {!user && (
                        <div className="flex gap-6">
                            <Link href="/login" className="hover:text-content">
                                Log in
                            </Link>
                            <Link href="/register" className="hover:text-content">
                                Sign up
                            </Link>
                        </div>
                    )}
                </div>
            </footer>
        </div>
    );
}