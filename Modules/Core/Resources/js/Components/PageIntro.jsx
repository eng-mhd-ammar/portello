export default function PageIntro({ title, description, actions = null, className = 'mb-8' }) {
    return (
        <div className={`flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between ${className}`}>
            <div className="max-w-2xl">
                {title && <h2 className="text-lg font-semibold text-content">{title}</h2>}
                {description && <p className="mt-1 text-sm text-content-muted">{description}</p>}
            </div>
            {actions && <span className="shrink-0">{actions}</span>}
        </div>
    );
}