import { cn } from '@/utils';

export function FormSection({
    title,
    description,
    children,
    columns = 2,
}: {
    title: string;
    description?: string;
    children: React.ReactNode;
    columns?: 1 | 2 | 4;
}) {
    const columnClass = {
        1: 'grid-cols-1',
        2: 'grid-cols-1 sm:grid-cols-2',
        4: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
    }[columns];

    return (
        <section className="space-y-4">
            <div>
                <h2 className="text-base font-semibold text-ink dark:text-ink-inverse">{title}</h2>
                {description && (
                    <p className="mt-1 text-sm text-ink-muted dark:text-ink-inverse/70">{description}</p>
                )}
            </div>
            <div className={cn('grid gap-4', columnClass)}>{children}</div>
        </section>
    );
}

export function FormPanel({ children, className }: { children: React.ReactNode; className?: string }) {
    return (
        <div
            className={cn(
                'rounded-xl border border-line bg-surface p-4 sm:p-6 dark:border-line-dark dark:bg-surface-dark',
                className,
            )}
        >
            {children}
        </div>
    );
}

export function FormActions({ children, className }: { children: React.ReactNode; className?: string }) {
    return (
        <div
            className={cn(
                'sticky bottom-0 z-10 -mx-1 mt-8 flex flex-col-reverse gap-3 border-t border-line bg-canvas/95 px-1 py-4 backdrop-blur sm:flex-row sm:justify-end dark:border-line-dark dark:bg-canvas-dark/95',
                className,
            )}
        >
            {children}
        </div>
    );
}
