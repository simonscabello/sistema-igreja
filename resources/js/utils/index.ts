import { route as ziggyRoute } from 'ziggy-js';
import { PageProps } from './types';

export { cn } from '@/lib/utils';

export function route(name: string, params?: Record<string, unknown> | number, absolute?: boolean): string {
    const page = (window as unknown as { __inertia_page?: { props: PageProps } }).__inertia_page;
    const ziggy = page?.props?.ziggy ?? (window as unknown as { Ziggy?: PageProps['ziggy'] }).Ziggy;

    if (!ziggy) {
        throw new Error('Ziggy config not found');
    }

    return ziggyRoute(name, params, absolute, ziggy);
}

export function formatDateBr(value: string | null | undefined): string {
    if (!value) {
        return '-';
    }

    const isoMatch = value.match(/^(\d{4})-(\d{2})-(\d{2})/);
    if (isoMatch) {
        return `${isoMatch[3]}/${isoMatch[2]}/${isoMatch[1]}`;
    }

    return value;
}

export function formatCurrency(value: number | string | null | undefined): string {
    const amount = typeof value === 'string' ? parseFloat(value) : (value ?? 0);

    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(amount);
}

export function formatNumber(value: number | null | undefined): string {
    return new Intl.NumberFormat('pt-BR').format(value ?? 0);
}

export function getInitials(name: string): string {
    return name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part[0]?.toUpperCase() ?? '')
        .join('');
}
