import { usePage } from '@inertiajs/react';
import { useEffect, useRef } from 'react';
import { toast } from 'sonner';
import { PageProps } from '@/types';

interface CanProps {
    permission?: string;
    permissions?: string[];
    children: React.ReactNode;
}

export function Can({ permission, permissions, children }: CanProps) {
    const { auth } = usePage<PageProps>().props;
    const userPermissions = auth.user?.permissions ?? [];

    if (permission && !userPermissions.includes(permission)) {
        return null;
    }

    if (permissions && !permissions.some((item) => userPermissions.includes(item))) {
        return null;
    }

    return <>{children}</>;
}

export function FlashMessages() {
    const { flash } = usePage<PageProps>().props;
    const lastKey = useRef<string>('');

    useEffect(() => {
        const key = [flash.success, flash.error, flash.warning, flash.status].filter(Boolean).join('|');

        if (!key || key === lastKey.current) {
            return;
        }

        lastKey.current = key;

        if (flash.success) {
            toast.success(flash.success);
        }

        if (flash.error) {
            toast.error(flash.error);
        }

        if (flash.warning) {
            toast.warning(flash.warning);
        }

        if (flash.status && flash.status !== 'profile-updated' && flash.status !== 'password-updated') {
            toast.message(flash.status);
        }
    }, [flash.success, flash.error, flash.warning, flash.status]);

    return null;
}
