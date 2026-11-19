import { Link, usePage } from '@inertiajs/react';
import { useState } from 'react';
import DashboardSidebar from '@core/Components/DashboardSidebar';
import FlashBanner from '@core/Components/FlashBanner';
import ThemeToggle from '@core/Components/ThemeToggle';
import UserMenu from '@core/Components/UserMenu';

export default function AppLayout({ children, title }) {
    const { auth } = usePage().props;
    const [mobileNavOpen, setMobileNavOpen] = useState(false);

    const closeMobileNav = () => setMobileNavOpen(false);

    return (
        <div className="min-h-screen bg-surface-muted lg:flex">
            <div
                className={`fixed inset-0 z-40 bg-black/40 lg:hidden ${mobileNavOpen ? 'block' : 'hidden'}`}
                onClick={closeMobileNav}
                aria-hidden={!mobileNavOpen}
            />

            <aside
                className={`fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-border bg-surface-elevated transition-transform lg:static lg:z-0 lg:translate-x-0 ${
                    mobileNavOpen ? 'translate-x-0' : '-translate-x-full'
                }`}
            >
                <div className="flex h-14 items-center gap-2 border-b border-border px-4">
                    <Link href="/dashboard" className="flex items-center gap-2" onClick={closeMobileNav}>
                        <span className="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-brand to-brand-hover text-xs font-semibold text-brand-fg">
                            P
                        </span>
                        <span className="font-semibold text-content">Portello</span>
                    </Link>
                </div>
                <div className="flex-1 overflow-y-auto">
                    <DashboardSidebar onNavigate={closeMobileNav} />
                </div>
                <p className="hidden border-t border-border px-4 py-3 text-[11px] text-content-subtle lg:block">
                    Use the sidebar to switch modules.
                </p>
            </aside>

            <div className="flex min-h-screen min-w-0 flex-1 flex-col">
                <header className="sticky top-0 z-30 flex h-14 items-center justify-between gap-3 border-b border-border bg-surface-elevated/90 px-4 backdrop-blur-md sm:px-6">
                    <motionHeaderLeft>
                        <button
                            type="button"
                            className="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-border bg-surface-elevated text-content lg:hidden"
                            onClick={() => setMobileNavOpen(true)}
                            aria-label="Open navigation"
                        >
                            <MenuIcon />
                        </button>
                        {title && (
                            <h1 className="text-lg font-semibold tracking-tight text-content sm:text-xl">{title}</h1>
                        )}
                    </motionHeaderLeft>
                    <div className="flex items-center gap-2">
                        <ThemeToggle />
                        <UserMenu user={auth?.user} />
                    </div>
                </header>

                <main className="flex-1 px-4 py-8 sm:px-6 lg:px-8">
                    <div className="mx-auto max-w-6xl">
                        <FlashBanner />
                        {children}
                    </div>
                </main>
            </div>
        </div>
    );
}

function MenuIcon() {
    return (
        <svg className="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden>
            <path d="M4 7h16M4 12h16M4 17h16" strokeLinecap="round" />
        </svg>
    );
}

function motionHeaderLeft({ children }) {
    return <div className="flex items-center gap-3">{children}</div>;
}