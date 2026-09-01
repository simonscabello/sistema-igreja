import { cn } from '@/lib/utils';
import { Table as UiTable, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Card } from '@/components/ui/card';

export function TableShell({ children, className }: { children: React.ReactNode; className?: string }) {
    return <Card className={cn('overflow-hidden shadow-none', className)}>{children}</Card>;
}

export function Table({ children, className }: { children: React.ReactNode; className?: string }) {
    return <UiTable className={cn('min-w-full', className)}>{children}</UiTable>;
}

export function THead({ children }: { children: React.ReactNode }) {
    return (
        <TableHeader className="bg-muted/40">
            <TableRow className="hover:bg-transparent">{children}</TableRow>
        </TableHeader>
    );
}

export function Th({ children, align = 'left', className }: { children: React.ReactNode; align?: 'left' | 'right'; className?: string }) {
    return <TableHead className={cn(align === 'right' ? 'text-right' : 'text-left', className)}>{children}</TableHead>;
}

export function ActionsTh() {
    return (
        <Th align="right" className="w-12">
            <span className="sr-only">Ações</span>
        </Th>
    );
}

export function TBody({ children }: { children: React.ReactNode }) {
    return <TableBody>{children}</TableBody>;
}

export function Tr({ children, className, onClick }: { children: React.ReactNode; className?: string; onClick?: () => void }) {
    return (
        <TableRow onClick={onClick} className={cn(onClick && 'cursor-pointer', className)}>
            {children}
        </TableRow>
    );
}

export function Td({ children, align = 'left', className }: { children: React.ReactNode; align?: 'left' | 'right'; className?: string }) {
    return <TableCell className={cn(align === 'right' ? 'text-right whitespace-nowrap' : 'text-left', className)}>{children}</TableCell>;
}

export function MobileList({ children, className }: { children: React.ReactNode; className?: string }) {
    return <div className={cn('space-y-3 md:hidden', className)}>{children}</div>;
}

export function MobileCard({ children, className }: { children: React.ReactNode; className?: string }) {
    return <Card className={cn('p-4 shadow-none', className)}>{children}</Card>;
}

export function MobileCardHeader({ children, actions }: { children: React.ReactNode; actions?: React.ReactNode }) {
    return (
        <div className="flex items-start justify-between gap-3">
            <div className="min-w-0 flex-1">{children}</div>
            {actions ? <div className="-mr-2 -mt-1 shrink-0">{actions}</div> : null}
        </div>
    );
}

export function DesktopOnly({ children }: { children: React.ReactNode }) {
    return <div className="hidden md:block">{children}</div>;
}
