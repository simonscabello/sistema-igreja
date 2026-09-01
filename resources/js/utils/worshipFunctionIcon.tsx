import { Drum, Guitar, Keyboard, Mic2, Monitor, Music2, Radio, Users, Volume2, type LucideIcon } from 'lucide-react';
import { cn } from '@/utils';

const ICON_BY_SLUG: Record<string, LucideIcon> = {
    vocal: Mic2,
    violao: Guitar,
    guitarra: Music2,
    baixo: Radio,
    teclado: Keyboard,
    bateria: Drum,
    som: Volume2,
    multimidia: Monitor,
    'direcao-do-culto': Users,
};

export function getWorshipFunctionIcon(slug: string): LucideIcon {
    return ICON_BY_SLUG[slug] ?? Mic2;
}

interface WorshipFunctionIconProps {
    slug: string;
    className?: string;
}

export function WorshipFunctionIcon({ slug, className }: WorshipFunctionIconProps) {
    const Icon = getWorshipFunctionIcon(slug);

    return <Icon className={cn('h-4 w-4', className)} aria-hidden />;
}
