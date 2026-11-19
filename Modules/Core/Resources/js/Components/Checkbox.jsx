function CheckIcon({ className }) {
    return (
        <svg className={className} viewBox="0 0 16 16" fill="none" aria-hidden>
            <path
                d="M3.5 8.2 6.4 11 12.5 4.8"
                stroke="currentColor"
                strokeWidth="2"
                strokeLinecap="round"
                strokeLinejoin="round"
            />
        </svg>
    );
}

export default function Checkbox({
    id,
    label,
    description,
    checked = false,
    onChange,
    disabled = false,
    className = '',
    name,
    value,
}) {
    return (
        <label
            htmlFor={id}
            className={`group inline-flex cursor-pointer items-start gap-3 rounded-lg py-0.5 focus-within:outline-none focus-within:ring-2 focus-within:ring-brand/25 ${disabled ? 'cursor-not-allowed opacity-60' : ''} ${className}`}
        >
            <input
                id={id}
                name={name}
                type="checkbox"
                value={value}
                className="peer sr-only"
                checked={checked}
                disabled={disabled}
                onChange={onChange}
            />
            <span
                aria-hidden
                className={`relative mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-md border-2 shadow-sm transition ${
                    checked
                        ? 'border-brand bg-brand'
                        : 'border-border-strong bg-surface-elevated group-hover:border-brand/50'
                } peer-focus-visible:ring-2 peer-focus-visible:ring-brand/35`}
            >
                <CheckIcon
                    className={`h-3.5 w-3.5 text-brand-fg transition ${checked ? 'scale-100 opacity-100' : 'scale-75 opacity-0'}`}
                />
            </span>
            {(label || description) && (
                <span className="select-none">
                    {label && <span className="block text-sm font-medium text-content">{label}</span>}
                    {description && <span className="mt-0.5 block text-sm text-content-muted">{description}</span>}
                </span>
            )}
        </label>
    );
}