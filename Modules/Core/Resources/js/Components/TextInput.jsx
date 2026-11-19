export default function TextInput({
    id,
    label,
    type = 'text',
    error,
    className = '',
    inputClassName = '',
    ...props
}) {
    return (
        <div className={className}>
            {label && (
                <label htmlFor={id} className="block text-sm font-medium text-content">
                    {label}
                </label>
            )}
            <input
                id={id}
                type={type}
                className={`mt-1.5 w-full rounded-xl border border-border-strong bg-surface-elevated px-3.5 py-2.5 text-sm text-content shadow-sm placeholder:text-content-subtle focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 ${inputClassName}`}
                {...props}
            />
            {error && <p className="mt-1.5 text-sm text-danger">{error}</p>}
        </div>
    );
}