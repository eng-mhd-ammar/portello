import { Link } from '@inertiajs/react';

const variants = {
    primary:
        'bg-brand text-brand-fg shadow-sm shadow-brand/25 hover:bg-brand-hover focus-visible:outline-brand',
    secondary:
        'border border-border-strong bg-surface-elevated text-content hover:border-border hover:bg-surface-muted focus-visible:outline-brand',
    ghost: 'text-content-muted hover:bg-surface-muted hover:text-content focus-visible:outline-brand',
    inverse:
        'border border-white/20 bg-white/10 text-white backdrop-blur hover:bg-white/15 focus-visible:outline-white',
};

const base =
    'inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 disabled:pointer-events-none disabled:opacity-50';

export default function Button({
    variant = 'primary',
    className = '',
    href,
    method,
    as = 'button',
    type = 'button',
    children,
    ...props
}) {
    const classes = `${base} ${variants[variant] ?? variants.primary} ${className}`.trim();

    if (href) {
        return (
            <Link href={href} method={method} as={as} className={classes} {...props}>
                {children}
            </Link>
        );
    }

    return (
        <button type={type} className={classes} {...props}>
            {children}
        </button>
    );
}