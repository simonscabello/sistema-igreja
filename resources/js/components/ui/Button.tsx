import { Link } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { cn } from '@/utils';

type ButtonVariant = 'primary' | 'secondary' | 'danger' | 'ghost' | 'link';
type ButtonSize = 'sm' | 'md';

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
    variant?: ButtonVariant;
    size?: ButtonSize;
    href?: string;
    method?: 'get' | 'post' | 'put' | 'patch' | 'delete';
    as?: 'button' | 'link';
    processing?: boolean;
}

const variantClasses: Record<ButtonVariant, string> = {
    primary:
        'bg-primary text-white hover:bg-primary-dark focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2',
    secondary:
        'bg-surface text-ink border border-line hover:bg-canvas dark:bg-surface-dark dark:text-ink-inverse dark:border-line-dark dark:hover:bg-white/5',
    danger: 'bg-saida text-white hover:bg-red-800 focus-visible:ring-2 focus-visible:ring-saida focus-visible:ring-offset-2',
    ghost: 'bg-transparent text-ink-muted hover:bg-canvas hover:text-ink dark:text-ink-inverse/70 dark:hover:bg-white/5 dark:hover:text-ink-inverse',
    link: 'bg-transparent text-primary hover:text-primary-dark px-0 py-0 min-h-0 h-auto',
};

const sizeClasses: Record<ButtonSize, string> = {
    sm: 'min-h-9 px-3 text-sm',
    md: 'min-h-touch px-4 text-sm',
};

export function Button({
    variant = 'primary',
    size = 'md',
    href,
    method = 'get',
    as,
    className,
    children,
    type = 'button',
    processing = false,
    disabled,
    ...props
}: ButtonProps) {
    const classes = cn(
        'inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-colors duration-150 disabled:cursor-not-allowed disabled:opacity-50',
        variantClasses[variant],
        variant !== 'link' && sizeClasses[size],
        className,
    );

    const content = (
        <>
            {processing && <LoaderCircle className="h-4 w-4 animate-spin" aria-hidden />}
            {children}
        </>
    );

    if (href || as === 'link') {
        return (
            <Link href={href ?? '#'} method={method} className={classes}>
                {content}
            </Link>
        );
    }

    return (
        <button type={type} className={classes} disabled={disabled || processing} {...props}>
            {content}
        </button>
    );
}

export function PrimaryButton(props: Omit<ButtonProps, 'variant'>) {
    return <Button variant="primary" {...props} />;
}

export function SecondaryButton(props: Omit<ButtonProps, 'variant'>) {
    return <Button variant="secondary" {...props} />;
}

export function DangerButton(props: Omit<ButtonProps, 'variant'>) {
    return <Button variant="danger" {...props} />;
}

export function LinkButton({
    href,
    className,
    children,
}: {
    href: string;
    className?: string;
    children: React.ReactNode;
}) {
    return (
        <Link href={href} className={cn(variantClasses.link, 'inline-flex items-center text-sm font-medium', className)}>
            {children}
        </Link>
    );
}
