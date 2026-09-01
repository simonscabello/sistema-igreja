import { useState } from 'react';
import { router } from '@inertiajs/react';
import { Trash2 } from 'lucide-react';
import { Button } from './Button';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { buttonVariants } from '@/components/ui/button';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { cn } from '@/lib/utils';

interface DeleteButtonProps {
    href: string;
    children?: React.ReactNode;
    title?: string;
    text?: string;
    className?: string;
    compact?: boolean;
    asMenuItem?: boolean;
}

export function DeleteButton({
    href,
    children = 'Excluir',
    title = 'Excluir este registro?',
    text = 'Isso não pode ser desfeito.',
    className,
    compact = false,
    asMenuItem = false,
}: DeleteButtonProps) {
    const [open, setOpen] = useState(false);

    return (
        <AlertDialog open={open} onOpenChange={setOpen}>
            <AlertDialogTrigger asChild>
                {asMenuItem ? (
                    <DropdownMenuItem
                        className="text-destructive focus:bg-destructive/10 focus:text-destructive"
                        onSelect={(event) => event.preventDefault()}
                    >
                        <Trash2 />
                        {children}
                    </DropdownMenuItem>
                ) : (
                    <Button
                        type="button"
                        className={className}
                        size={compact ? 'sm' : 'md'}
                        variant={compact ? 'danger-ghost' : 'danger'}
                        icon={Trash2}
                    >
                        {children}
                    </Button>
                )}
            </AlertDialogTrigger>
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>{title}</AlertDialogTitle>
                    <AlertDialogDescription>{text}</AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Manter</AlertDialogCancel>
                    <AlertDialogAction className={cn(buttonVariants({ variant: 'destructive' }))} onClick={() => router.delete(href)}>
                        Excluir
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    );
}
