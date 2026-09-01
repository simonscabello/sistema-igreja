import { ExternalLink as ExternalLinkIcon } from 'lucide-react';
import { cn } from '@/lib/utils';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';

export function DetailField({ label, value, children }: { label: string; value?: React.ReactNode; children?: React.ReactNode }) {
    const content = children ?? value;

    if (content == null || content === '') {
        return null;
    }

    return (
        <div>
            <dt className="text-sm text-muted-foreground">{label}</dt>
            <dd className="mt-0.5 text-sm">{content}</dd>
        </div>
    );
}

const columnClass = {
    1: 'grid-cols-1',
    2: 'grid-cols-1 sm:grid-cols-2',
    3: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    4: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
};

export function DetailGrid({ children, columns = 2 }: { children: React.ReactNode; columns?: 1 | 2 | 3 | 4 }) {
    return <dl className={cn('grid gap-4', columnClass[columns])}>{children}</dl>;
}

export function DetailSection({
    title,
    description,
    children,
    className,
}: {
    title?: string;
    description?: string;
    children: React.ReactNode;
    className?: string;
}) {
    return (
        <Card className={cn('shadow-none', className)}>
            {(title || description) && (
                <CardHeader>
                    {title && <CardTitle className="text-base">{title}</CardTitle>}
                    {description && <p className="text-sm text-muted-foreground">{description}</p>}
                </CardHeader>
            )}
            <CardContent className={cn(!(title || description) && 'pt-6')}>{children}</CardContent>
        </Card>
    );
}

export function DetailActions({ children }: { children: React.ReactNode }) {
    return (
        <>
            <Separator />
            <div className="flex flex-wrap gap-2">{children}</div>
        </>
    );
}

export function ProgressBar({ value, className }: { value: number; className?: string }) {
    const clamped = Math.min(100, Math.max(0, value));

    return (
        <div
            className={cn('h-2 overflow-hidden rounded-full bg-muted', className)}
            role="progressbar"
            aria-valuenow={Math.round(clamped)}
            aria-valuemin={0}
            aria-valuemax={100}
        >
            <div className="h-full rounded-full bg-primary transition-[width] duration-300" style={{ width: `${clamped}%` }} />
        </div>
    );
}

export function ExternalLink({ href, children }: { href: string; children: React.ReactNode }) {
    return (
        <a href={href} target="_blank" rel="noreferrer" className="inline-flex items-center gap-1.5 text-sm font-medium hover:underline">
            {children}
            <ExternalLinkIcon className="h-3.5 w-3.5" aria-hidden />
            <span className="sr-only">(abre em nova aba)</span>
        </a>
    );
}
