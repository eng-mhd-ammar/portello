import { Link, router } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';

function initials(name, email) {
    const source = (name || email || '?').trim();

    if (!source.includes(' ')) {
        return source.slice(0, 2).toUpperCase();
    }

    return source
        .split(/\s+/)
        .slice(0, 2)
        .map((part) => part[0])
        .join('')
        .toUpperCase();
}

export default function UserMenu({ user }) {
    const [open, setOpen] = useState(false);
    const rootRef = useRef(null);

    useEffect(() => {
        const handlePointerDown = (event) => {
            if (!rootRef.current?.contains(event.target)) {
                setOpen(false);
            }
        };

        const handleEscape = (event) => {
            if (event.key === 'Escape') {
                setOpen(false);
            }
        };

        document.addEventListener('mousedown', handlePointerDown);
        document.addEventListener('keydown', handleEscape);

        return () => {
            document.removeEventListener('mousedown', handlePointerDown);
            document.removeEventListener('keydown', handleEscape);
        };
    }, []);

    if (!user) {
        return null;
    }

    const logout = () => {
        setOpen(false);
        router.post('/logout');
    };

    return (
        <div ref={rootRef} className="relative">
            <button
                type="button"
                onClick={() => setOpen((current) => !current)}
                className="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-brand to-brand-hover text-sm font-semibold text-brand-fg shadow-md shadow-brand/30 ring-2 ring-surface-elevated transition hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 focus:ring-offset-surface"
                aria-expanded={open}
                aria-haspopup="menu"
            >
                {initials(user.name, user.email)}
            </button>

            {open && (
                <div
                    role="menu"
                    className="absolute right-0 z-50 mt-2 w-52 origin-top-right rounded-2xl border border-border bg-surface-elevated p-1.5 shadow-xl shadow-black/10 dark:shadow-black/40"
                >
                    <div className="border-b border-border px-3 py-2.5">
                        <p className="truncate text-sm font-semibold text-content">{user.name}</p>
                        <p className="truncate text-xs text-content-muted">{user.email}</p>
                    </div>
                    <div className="py-1">
                        <MenuLink href="/profile" onSelect={() => setOpen(false)}>
                            Profile settings
                        </MenuLink>
                    </div>
                    <div className="border-t border-border py-1">
                        <button
                            type="button"
                            role="menuitem"
                            onClick={logout}
                            className="flex w-full items-center rounded-xl px-3 py-2 text-left text-sm text-danger transition hover:bg-danger/10"
                        >
                            Log out
                        </button>
                    </div>
                </div>
            )}
        </div>
    );
}

function MenuLink({ href, children, onSelect }) {
    return (
        <Link
            href={href}
            role="menuitem"
            onClick={onSelect}
            className="flex w-full items-center rounded-xl px-3 py-2 text-sm text-content-muted transition hover:bg-surface-muted hover:text-content"
        >
            {children}
        </Link>
    );
}