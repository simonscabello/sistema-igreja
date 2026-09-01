import { cn } from '@/utils';

export function TableShell({ children, className }: { children: React.ReactNode; className?: string }) {
    return (
        <div
            className={cn(
                'overflow-hidden rounded-xl border border-line bg-surface dark:border-line-dark dark:bg-surface-dark',
                className,
            )}
        >
            <div className="overflow-x-auto">{children}</div>
        </div>
    );
}

export function Table({ children, className }: { children: React.ReactNode; className?: string }) {
    return (
        <table className={cn('min-w-full divide-y divide-line dark:divide-line-dark', className)}>
            {children}
        </table>
    );
}

export function THead({ children }: { children: React.ReactNode }) {
    return (
        <thead className="bg-canvas dark:bg-white/5">
            <tr>{children}</tr>
        </thead>
    );
}

export function Th({
    children,
    align = 'left',
    className,
}: {
    children: React.ReactNode;
    align?: 'left' | 'right';
    className?: string;
}) {
    return (
        <th
            className={cn(
                'px-4 py-3 text-xs font-medium uppercase tracking-wide text-ink-muted dark:text-ink-inverse/60 lg:px-5',
                align === 'right' ? 'text-right' : 'text-left',
                className,
            )}
        >
            {children}
        </th>
    );
}

export function TBody({ children }: { children: React.ReactNode }) {
    return <tbody className="divide-y divide-line dark:divide-line-dark">{children}</tbody>;
}

export function Tr({
    children,
    className,
    onClick,
}: {
    children: React.ReactNode;
    className?: string;
    onClick?: () => void;
}) {
    return (
        <tr
            onClick={onClick}
            className={cn(
                'transition-colors duration-150 hover:bg-canvas dark:hover:bg-white/5',
                onClick && 'cursor-pointer',
                className,
            )}
        >
            {children}
        </tr>
    );
}

export function Td({
    children,
    align = 'left',
    className,
}: {
    children: React.ReactNode;
    align?: 'left' | 'right';
    className?: string;
}) {
    return (
        <td
            className={cn(
                'px-4 py-3.5 text-sm text-ink dark:text-ink-inverse lg:px-5',
                align === 'right' ? 'text-right' : 'text-left',
                className,
            )}
        >
            {children}
        </td>
    );
}

export function MobileList({ children, className }: { children: React.ReactNode; className?: string }) {
    return <div className={cn('space-y-3 md:hidden', className)}>{children}</div>;
}

export function MobileCard({ children, className }: { children: React.ReactNode; className?: string }) {
    return (
        <div
            className={cn(
                'rounded-xl border border-line bg-surface p-4 dark:border-line-dark dark:bg-surface-dark',
                className,
            )}
        >
            {children}
        </div>
    );
}

export function DesktopOnly({ children }: { children: React.ReactNode }) {
    return <div className="hidden md:block">{children}</div>;
}
