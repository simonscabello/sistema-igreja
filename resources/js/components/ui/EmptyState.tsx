import { Link } from '@inertiajs/react';
import { cn } from '@/utils';

export function EmptyState({
    title,
    description,
    actionLabel,
    actionHref,
    icon,
    className,
}: {
    title: string;
    description?: string;
    actionLabel?: string;
    actionHref?: string;
    icon?: React.ReactNode;
    className?: string;
}) {
    return (
        <div className={cn('flex flex-col items-center justify-center px-4 py-12 text-center', className)}>
            {icon && <div className="mb-3 text-ink-muted">{icon}</div>}
            <h3 className="text-base font-semibold text-ink dark:text-ink-inverse">{title}</h3>
            {description && (
                <p className="mt-1 max-w-sm text-sm text-ink-muted dark:text-ink-inverse/70">{description}</p>
            )}
            {actionHref && actionLabel && (
                <Link
                    href={actionHref}
                    className="mt-4 inline-flex min-h-touch items-center rounded-lg bg-primary px-4 text-sm font-medium text-white hover:bg-primary-dark"
                >
                    {actionLabel}
                </Link>
            )}
        </div>
    );
}
