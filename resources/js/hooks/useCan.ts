import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';

export function useCan(permission: string): boolean {
    const { auth } = usePage<PageProps>().props;

    return auth.user?.permissions.includes(permission) ?? false;
}

export function useCanAny(permissions: string[]): boolean {
    const { auth } = usePage<PageProps>().props;
    const userPermissions = auth.user?.permissions ?? [];

    return permissions.some((permission) => userPermissions.includes(permission));
}

export function useAuthUser() {
    const { auth } = usePage<PageProps>().props;

    return auth.user;
}
