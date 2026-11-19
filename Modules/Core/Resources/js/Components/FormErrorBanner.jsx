import { usePage } from '@inertiajs/react';

/**
 * Shown when Inertia shares validation errors without a field mapping (e.g. router.post).
 */
export default function FormErrorBanner({ fields = [], className = 'mb-6' }) {
    const { errors } = usePage().props;

    if (!errors || typeof errors !== 'object') {
        return null;
    }

    const messages = fields.length
        ? fields.map((key) => errors[key]).filter(Boolean)
        : Object.values(errors).filter(Boolean);

    if (messages.length === 0) {
        return null;
    }

    return (
        <div className={`rounded-xl border border-danger/30 bg-danger/10 px-4 py-3 text-sm text-danger ${className}`} role="alert">
            <ul className={messages.length > 1 ? 'list-inside list-disc space-y-1' : undefined}>
                {messages.map((message) => (
                    <li key={message}>{message}</li>
                ))}
            </ul>
        </div>
    );
}