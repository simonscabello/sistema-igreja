import { Link } from '@inertiajs/react';
import { Copy, Eye, MoreHorizontal, Pencil } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { cn } from '@/lib/utils';

interface RowActionsMenuProps {
    viewHref?: string;
    editHref?: string;
    cloneHref?: string;
    deleteHref?: string;
    deleteTitle?: string;
    deleteText?: string;
    className?: string;
}

export function RowActionsMenu({ viewHref, editHref, cloneHref, deleteHref, deleteTitle, deleteText, className }: RowActionsMenuProps) {
    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button variant="ghost" size="icon" className={cn('size-10 text-foreground', className)} aria-label="Ações">
                    <MoreHorizontal className="h-4 w-4" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
                {viewHref && (
                    <DropdownMenuItem asChild>
                        <Link href={viewHref}>
                            <Eye />
                            Ver
                        </Link>
                    </DropdownMenuItem>
                )}
                {editHref && (
                    <DropdownMenuItem asChild>
                        <Link href={editHref}>
                            <Pencil />
                            Editar
                        </Link>
                    </DropdownMenuItem>
                )}
                {cloneHref && (
                    <DropdownMenuItem asChild>
                        <Link href={cloneHref}>
                            <Copy />
                            Clonar
                        </Link>
                    </DropdownMenuItem>
                )}
                {deleteHref && (
                    <>
                        <DropdownMenuSeparator />
                        <DeleteButton href={deleteHref} title={deleteTitle} text={deleteText} asMenuItem />
                    </>
                )}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
