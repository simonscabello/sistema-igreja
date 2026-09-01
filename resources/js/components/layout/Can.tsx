import { usePage } from '@inertiajs/react';
import { useFlashMessages } from '@/hooks';
import { Alert } from '@/components/ui/Alert';
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
    const { flash, visible } = useFlashMessages();

    if (!visible) {
        return null;
    }

    const hasMessage = flash.success || flash.error || flash.warning || flash.status;

    if (!hasMessage) {
        return null;
    }

    return (
        <div className="pointer-events-none fixed inset-x-0 top-16 z-[60] flex justify-center px-4 sm:justify-end sm:px-6">
            <div className="pointer-events-auto w-full max-w-md">
                {flash.success && (
                    <Alert type="success" dismissible>
                        {flash.success}
                    </Alert>
                )}
                {flash.error && (
                    <Alert type="error" dismissible>
                        {flash.error}
                    </Alert>
                )}
                {flash.warning && (
                    <Alert type="warning" dismissible>
                        {flash.warning}
                    </Alert>
                )}
                {flash.status && flash.status !== 'profile-updated' && (
                    <Alert type="info" dismissible>
                        {flash.status}
                    </Alert>
                )}
            </div>
        </div>
    );
}
