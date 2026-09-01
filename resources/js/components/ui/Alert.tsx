import { useState } from 'react';
import { CircleAlert, CircleCheck, Info, LoaderCircle, TriangleAlert, X } from 'lucide-react';
import { cn } from '@/lib/utils';
import { Alert as UiAlert, AlertDescription } from '@/components/ui/alert';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';

type AlertType = 'success' | 'warning' | 'error' | 'info';

const alertClasses: Record<AlertType, string> = {
    success: 'border-entrada/30 bg-entrada/10 text-entrada [&>svg]:text-entrada dark:text-emerald-300',
    warning: 'border-warning/30 bg-warning/10 text-warning-foreground [&>svg]:text-warning',
    error: 'border-destructive/30 bg-destructive/10 text-destructive [&>svg]:text-destructive',
    info: 'border-info/30 bg-info/10 text-info-foreground [&>svg]:text-info',
};

const icons: Record<AlertType, typeof CircleCheck> = {
    success: CircleCheck,
    warning: TriangleAlert,
    error: CircleAlert,
    info: Info,
};

interface AlertProps {
    type?: AlertType;
    dismissible?: boolean;
    children: React.ReactNode;
    className?: string;
    onDismiss?: () => void;
}

export function Alert({ type = 'success', dismissible = false, children, className, onDismiss }: AlertProps) {
    const [visible, setVisible] = useState(true);
    const Icon = icons[type];

    if (!visible) {
        return null;
    }

    return (
        <UiAlert className={cn('mb-4', alertClasses[type], className)}>
            <Icon className="h-4 w-4" />
            <AlertDescription className="flex items-start justify-between gap-3">
                <div className="min-w-0 flex-1">{children}</div>
                {dismissible && (
                    <button
                        type="button"
                        onClick={() => {
                            setVisible(false);
                            onDismiss?.();
                        }}
                        className="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md hover:bg-foreground/10"
                        aria-label="Fechar"
                    >
                        <X className="h-4 w-4" />
                    </button>
                )}
            </AlertDescription>
        </UiAlert>
    );
}

export function Spinner({ className }: { className?: string }) {
    return <LoaderCircle className={cn('h-5 w-5 animate-spin text-muted-foreground', className)} aria-label="Carregando" />;
}

interface ModalProps {
    show: boolean;
    onClose: () => void;
    title?: string;
    children: React.ReactNode;
    maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl';
}

const maxWidthClasses = {
    sm: 'max-w-sm',
    md: 'max-w-md',
    lg: 'max-w-lg',
    xl: 'max-w-xl',
    '2xl': 'max-w-2xl',
};

export function Modal({ show, onClose, title, children, maxWidth = 'md' }: ModalProps) {
    return (
        <Dialog open={show} onOpenChange={(open) => !open && onClose()}>
            <DialogContent className={maxWidthClasses[maxWidth]}>
                {title && (
                    <DialogHeader>
                        <DialogTitle>{title}</DialogTitle>
                    </DialogHeader>
                )}
                {children}
            </DialogContent>
        </Dialog>
    );
}
