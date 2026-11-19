import { useState } from 'react';

function EyeIcon({ className }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.75" aria-hidden>
            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
            <circle cx="12" cy="12" r="3" />
        </svg>
    );
}

function EyeOffIcon({ className }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.75" aria-hidden>
            <path d="M3 3l18 18M9.88 9.88A3 3 0 0 0 12 15a3 3 0 0 0 2.12-.88M6.23 6.23C4.06 7.66 2.46 9.76 2 12s3.5 7 10 7c1.78 0 3.43-.47 4.88-1.3M10.73 5.08A10.73 10.73 0 0 1 12 5c6.5 0 10 7 10 7a16.2 16.2 0 0 1-2.67 3.66" />
        </svg>
    );
}

export default function PasswordInput({
    id,
    label,
    error,
    className = '',
    inputClassName = '',
    value,
    onChange,
    autoComplete,
    ...props
}) {
    const [visible, setVisible] = useState(false);

    return (
        <div className={className}>
            {label && (
                <label htmlFor={id} className="block text-sm font-medium text-content">
                    {label}
                </label>
            )}
            <div className="relative mt-1.5">
                <input
                    id={id}
                    type={visible ? 'text' : 'password'}
                    value={value}
                    onChange={onChange}
                    autoComplete={autoComplete}
                    className={`w-full rounded-xl border border-border-strong bg-surface-elevated py-2.5 pl-3.5 pr-11 text-sm text-content shadow-sm placeholder:text-content-subtle focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 ${inputClassName}`}
                    {...props}
                />
                <button
                    type="button"
                    onClick={() => setVisible((current) => !current)}
                    className="absolute right-2 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-lg text-content-muted transition hover:bg-surface-muted hover:text-content"
                    aria-label={visible ? 'Hide password' : 'Show password'}
                    tabIndex={-1}
                >
                    {visible ? <EyeOffIcon className="h-5 w-5" /> : <EyeIcon className="h-5 w-5" />}
                </button>
            </div>
            {error && <p className="mt-1.5 text-sm text-danger">{error}</p>}
        </div>
    );
}