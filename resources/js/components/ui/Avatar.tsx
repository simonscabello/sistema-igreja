import { getInitials, cn } from '@/utils';

interface AvatarProps {
    name: string;
    imageUrl?: string | null;
    size?: string;
    className?: string;
}

export function Avatar({ name, imageUrl, size = 'w-10 h-10', className }: AvatarProps) {
    if (imageUrl) {
        return (
            <img
                src={imageUrl}
                alt={`Foto de ${name}`}
                className={cn('h-full w-full rounded-full object-cover flex-shrink-0', size, className)}
            />
        );
    }

    return (
        <div
            className={cn(
                'rounded-full bg-primary/10 text-primary dark:text-primary-light flex items-center justify-center font-semibold flex-shrink-0',
                size,
                className,
            )}
        >
            {getInitials(name)}
        </div>
    );
}
