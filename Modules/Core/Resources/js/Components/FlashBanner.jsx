import { usePage } from '@inertiajs/react';

export default function FlashBanner({ className = 'mb-6' }) {
    const { flash } = usePage().props;

    if (!flash?.success && !flash?.error) {
        return null;
    }

    if (flash.success) {
        return (
            <buttonAlert
                type="success"
                message={flash.success}
                className={className}
            />
        );
    }

    return (
        <buttonAlert
            type="error"
            message={flash.error}
            className={className}
        />
    );
}

function buttonAlert({ type, message, className }) {
    const styles =
        type === 'success'
            ? 'border-success-border bg-success-surface text-success-content'
            : 'border-danger/30 bg-danger/10 text-danger';

    return (
        <div
            className={`rounded-xl border px-4 py-3 text-sm ${styles} ${className}`}
            role={type === 'error' ? 'alert' : 'status'}
        >
            {message}
        </div>
    );
}