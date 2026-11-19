import { Link, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { dashboardNav, isGroupActive, pathMatches, visibleNavGroups } from '@core/Config/dashboardNav';
import { resolveAuthCan } from '@core/Config/navCapabilities';

export default function DashboardSidebar({ className = '', onNavigate }) {
    const page = usePage();
    const { auth } = page.props;
    const url = page.url;
    const can = resolveAuthCan(auth);
    const groups = visibleNavGroups(dashboardNav, can);
    const [openModules, setOpenModules] = useState(() => initialOpenState(url, can));

    useEffect(() => {
        setOpenModules((current) => {
            const next = { ...current };
            groups.forEach((group) => {
                if (isGroupActive(group, url)) {
                    next[group.module] = true;
                }
            });

            return next;
        });
    }, [url]);

    const toggleModule = (module) => {
        setOpenModules((current) => ({ ...current, [module]: !current[module] }));
    };

    return (
        <nav className={`flex flex-col gap-1 px-3 py-4 ${className}`} aria-label="Dashboard">
            {groups.map((group) => {
                const expanded = openModules[group.module] ?? true;
                const groupActive = isGroupActive(group, url);

                return (
                    <motionModule key={group.module} className="mb-2">
                        <button
                            type="button"
                            onClick={() => toggleModule(group.module)}
                            className={`flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-left text-sm font-semibold transition ${
                                groupActive ? 'bg-brand-soft text-brand' : 'text-content hover:bg-surface-muted'
                            }`}
                            aria-expanded={expanded}
                        >
                            <span>
                                <span className="block text-[10px] font-medium uppercase tracking-wider text-content-subtle">
                                    {group.module}
                                </span>
                                <span>{group.label}</span>
                            </span>
                            <Chevron className={`h-4 w-4 shrink-0 transition ${expanded ? 'rotate-180' : ''}`} />
                        </button>

                        {expanded && (
                            <ul className="mt-1 space-y-0.5 border-l border-border pl-3 ml-3">
                                {group.items.map((item) => (
                                    <li key={`${group.module}-${item.label}`}>
                                        <NavItem item={item} url={url} onNavigate={onNavigate} />
                                    </li>
                                ))}
                            </ul>
                        )}
                    </motionModule>
                );
            })}
        </nav>
    );
}

function NavItem({ item, url, onNavigate }) {
    if (item.disabled || !item.href) {
        return (
            <span className="flex items-center justify-between gap-2 rounded-lg px-3 py-2 text-sm text-content-subtle">
                <span>{item.label}</span>
                {item.hint && (
                    <span className="rounded-md bg-surface-muted px-1.5 py-0.5 text-[10px] font-medium uppercase tracking-wide">
                        {item.hint}
                    </span>
                )}
            </span>
        );
    }

    const active = pathMatches(item.href, url, item.match);

    const className = `flex items-center rounded-lg px-3 py-2 text-sm font-medium transition ${
        active ? 'bg-brand text-brand-fg shadow-sm shadow-brand/20' : 'text-content-muted hover:bg-surface-muted hover:text-content'
    }`;

    if (item.external) {
        return (
            <a href={item.href} className={className} onClick={onNavigate}>
                {item.label}
            </a>
        );
    }

    return (
        <Link href={item.href} className={className} onClick={onNavigate}>
            {item.label}
        </Link>
    );
}

function Chevron({ className }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden>
            <path d="M6 9l6 6 6-6" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
    );
}

function motionModule({ children, className = '' }) {
    return <div className={className}>{children}</div>;
}

function initialOpenState(url, can) {
    return Object.fromEntries(
        visibleNavGroups(dashboardNav, can).map((group) => [group.module, isGroupActive(group, url)]),
    );
}