import { cn } from '@/lib/utils';
import { Badge as UiBadge } from '@/components/ui/badge';

type BadgeTone = 'neutral' | 'primary' | 'success' | 'danger' | 'warning' | 'info';

const toneToVariant: Record<BadgeTone, 'secondary' | 'default' | 'success' | 'destructive' | 'warning' | 'info'> = {
    neutral: 'secondary',
    primary: 'default',
    success: 'success',
    danger: 'destructive',
    warning: 'warning',
    info: 'info',
};

export function Badge({ tone = 'neutral', className, children }: { tone?: BadgeTone; className?: string; children: React.ReactNode }) {
    return (
        <UiBadge variant={toneToVariant[tone]} className={cn('rounded-full', className)}>
            {children}
        </UiBadge>
    );
}
