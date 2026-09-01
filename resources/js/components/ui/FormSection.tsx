import { cn } from '@/lib/utils';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

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
                <h2 className="text-base font-semibold">{title}</h2>
                {description && <p className="mt-1 text-sm text-muted-foreground">{description}</p>}
            </div>
            <div className={cn('grid gap-4', columnClass)}>{children}</div>
        </section>
    );
}

export function FormPanel({ children, className }: { children: React.ReactNode; className?: string }) {
    return (
        <Card className={cn('shadow-none', className)}>
            <CardContent className="p-4 sm:p-6">{children}</CardContent>
        </Card>
    );
}

export function FormCard({
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
                    {title && <CardTitle>{title}</CardTitle>}
                    {description && <CardDescription>{description}</CardDescription>}
                </CardHeader>
            )}
            <CardContent className={cn(!(title || description) && 'pt-6')}>{children}</CardContent>
        </Card>
    );
}

export function FormActions({ children, className }: { children: React.ReactNode; className?: string }) {
    return (
        <div
            className={cn(
                'sticky bottom-0 z-10 -mx-1 mt-8 flex flex-col-reverse gap-3 border-t bg-background/95 px-1 py-4 backdrop-blur sm:flex-row sm:justify-end',
                className,
            )}
        >
            {children}
        </div>
    );
}
