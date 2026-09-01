import Swal from 'sweetalert2';
import { router } from '@inertiajs/react';
import { DangerButton } from './Button';

interface DeleteButtonProps {
    href: string;
    children?: React.ReactNode;
    title?: string;
    text?: string;
    className?: string;
}

export function DeleteButton({
    href,
    children = 'Excluir',
    title = 'Excluir este registro?',
    text = 'Isso não pode ser desfeito.',
    className,
}: DeleteButtonProps) {
    const handleClick = async () => {
        const isDark = document.documentElement.classList.contains('dark');

        const result = await Swal.fire({
            title,
            text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B42318',
            cancelButtonColor: '#4D6561',
            confirmButtonText: 'Excluir',
            cancelButtonText: 'Manter',
            background: isDark ? '#162624' : '#fff',
            color: isDark ? '#E8F2F0' : '#1C3330',
        });

        if (result.isConfirmed) {
            router.delete(href);
        }
    };

    return (
        <DangerButton type="button" onClick={handleClick} className={className} size="sm">
            {children}
        </DangerButton>
    );
}
