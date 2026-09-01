import { getInitials, cn } from '@/utils';
import { Avatar as UiAvatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';

interface AvatarProps {
    name: string;
    imageUrl?: string | null;
    size?: string;
    className?: string;
}

export function Avatar({ name, imageUrl, size = 'h-10 w-10', className }: AvatarProps) {
    return (
        <UiAvatar className={cn(size, className)}>
            {imageUrl && <AvatarImage src={imageUrl} alt={`Foto de ${name}`} />}
            <AvatarFallback className="text-sm font-semibold">{getInitials(name)}</AvatarFallback>
        </UiAvatar>
    );
}
