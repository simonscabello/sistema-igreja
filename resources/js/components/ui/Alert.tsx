import { useEffect, useState } from 'react';
import { CircleAlert, CircleCheck, Info, TriangleAlert, X } from 'lucide-react';
import { cn } from '@/utils';

type AlertType = 'success' | 'warning' | 'error' | 'info';

const alertClasses: Record<AlertType, string> = {
    success:
        'bg-entrada-soft text-entrada border-entrada/20 dark:bg-entrada/15 dark:text-emerald-300 dark:border-entrada/30',
    warning:
        'bg-accent-subtle text-amber-950 border-accent/30 dark:bg-accent/15 dark:text-accent-subtle dark:border-accent/30',
    error: 'bg-saida-soft text-saida border-saida/20 dark:bg-saida/15 dark:text-red-300 dark:border-saida/30',
    info: 'bg-primary/10 text-primary-dark border-primary/20 dark:bg-primary/15 dark:text-primary-light dark:border-primary/30',
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
}

export function Alert({ type = 'success', dismissible = false, children, className }: AlertProps) {
    const [visible, setVisible] = useState(true);
    const Icon = icons[type];

    if (!visible) {
        return null;
    }

    return (
        <div
            className={cn('mb-4 flex items-start gap-3 rounded-xl border p-3.5 text-sm', alertClasses[type], className)}
            role="alert"
        >
            <Icon className="mt-0.5 h-4 w-4 shrink-0" aria-hidden />
            <div className="min-w-0 flex-1">{children}</div>
            {dismissible && (
                <button
                    type="button"
                    onClick={() => setVisible(false)}
                    className="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg hover:bg-black/5 dark:hover:bg-white/10"
                    aria-label="Fechar"
                >
                    <X className="h-4 w-4" />
                </button>
            )}
        </div>
    );
}

export { EmptyState } from './EmptyState';

export function Spinner({ className }: { className?: string }) {
    return (
        <div
            className={cn(
                'inline-block h-5 w-5 animate-spin rounded-full border-2 border-primary border-r-transparent',
                className,
            )}
            role="status"
            aria-label="Carregando"
        />
    );
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
    useEffect(() => {
        if (!show) {
            return;
        }

        const onKey = (event: KeyboardEvent) => {
            if (event.key === 'Escape') {
                onClose();
            }
        };

        document.addEventListener('keydown', onKey);

        return () => document.removeEventListener('keydown', onKey);
    }, [onClose, show]);

    if (!show) {
        return null;
    }

    return (
        <div className="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby={title ? 'modal-title' : undefined}>
            <div className="flex min-h-full items-center justify-center p-4">
                <button type="button" className="fixed inset-0 bg-canvas-dark/60" onClick={onClose} aria-label="Fechar" />
                <div
                    className={cn(
                        'relative w-full rounded-xl border border-line bg-surface p-6 shadow-float dark:border-line-dark dark:bg-surface-dark',
                        maxWidthClasses[maxWidth],
                    )}
                >
                    {title && (
                        <h3 id="modal-title" className="mb-4 text-lg font-semibold text-ink dark:text-ink-inverse">
                            {title}
                        </h3>
                    )}
                    {children}
                </div>
            </div>
        </div>
    );
}
