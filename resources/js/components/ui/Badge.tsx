import { cn } from '@/utils';

type BadgeTone = 'neutral' | 'primary' | 'success' | 'danger' | 'warning' | 'info';

const tones: Record<BadgeTone, string> = {
    neutral: 'bg-neutral-light text-ink-muted dark:bg-white/10 dark:text-ink-inverse/80',
    primary: 'bg-primary/10 text-primary-dark dark:bg-primary/20 dark:text-primary-light',
    success: 'bg-entrada-soft text-entrada dark:bg-entrada/20 dark:text-emerald-300',
    danger: 'bg-saida-soft text-saida dark:bg-saida/20 dark:text-red-300',
    warning: 'bg-accent-subtle text-amber-900 dark:bg-accent/20 dark:text-accent-subtle',
    info: 'bg-secondary/15 text-secondary dark:bg-secondary/20 dark:text-sky-200',
};

export function Badge({
    tone = 'neutral',
    className,
    children,
}: {
    tone?: BadgeTone;
    className?: string;
    children: React.ReactNode;
}) {
    return (
        <span
            className={cn(
                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                tones[tone],
                className,
            )}
        >
            {children}
        </span>
    );
}
