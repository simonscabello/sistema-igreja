import { cn } from '@/lib/utils';
import { CreateButton } from './Button';

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
        <div
            className={cn(
                'flex flex-col items-center justify-center rounded-xl border border-dashed bg-card/40 px-4 py-16 text-center',
                className,
            )}
        >
            {icon && <div className="mb-3 text-muted-foreground">{icon}</div>}
            <h3 className="text-base font-semibold">{title}</h3>
            {description && <p className="mt-1 max-w-sm text-sm text-muted-foreground">{description}</p>}
            {actionHref && actionLabel && (
                <CreateButton href={actionHref} className="mt-4">
                    {actionLabel}
                </CreateButton>
            )}
        </div>
    );
}
