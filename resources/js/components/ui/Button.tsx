import { Link } from '@inertiajs/react';
import {
    ArrowLeft,
    Check,
    Copy,
    Eye,
    Filter,
    KeyRound,
    LoaderCircle,
    LogIn,
    Mail,
    Pencil,
    Plus,
    RotateCcw,
    Save,
    Search,
    Send,
    Trash2,
    X,
    type LucideIcon,
} from 'lucide-react';
import { cn } from '@/lib/utils';
import { Button as UiButton, buttonVariants } from '@/components/ui/button';

type ButtonVariant = 'primary' | 'secondary' | 'neutral' | 'danger' | 'ghost' | 'danger-ghost' | 'link';
type ButtonSize = 'sm' | 'md';

export const actionFillClass = 'bg-primary text-primary-foreground hover:bg-primary/90';

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
    variant?: ButtonVariant;
    size?: ButtonSize;
    href?: string;
    method?: 'get' | 'post' | 'put' | 'patch' | 'delete';
    as?: 'button' | 'link';
    asChild?: boolean;
    processing?: boolean;
    icon?: LucideIcon | false;
}

const variantMap: Record<ButtonVariant, 'default' | 'outline' | 'secondary' | 'destructive' | 'ghost' | 'link'> = {
    primary: 'default',
    secondary: 'outline',
    neutral: 'secondary',
    danger: 'destructive',
    ghost: 'ghost',
    'danger-ghost': 'ghost',
    link: 'link',
};

function extractLabel(children: React.ReactNode): string {
    if (typeof children === 'string' || typeof children === 'number') {
        return String(children).trim();
    }

    if (Array.isArray(children)) {
        return children
            .map((child) => extractLabel(child))
            .join(' ')
            .replace(/\s+/g, ' ')
            .trim();
    }

    return '';
}

const inferredIcons: Array<[RegExp, LucideIcon]> = [
    [/^clonar/i, Copy],
    [/^excluir/i, Trash2],
    [/^cancelar/i, X],
    [/^voltar/i, ArrowLeft],
    [/^(salvar|atualizar|cadastrar)/i, Save],
    [/^(criar|nova |novo |adicionar)/i, Plus],
    [/^buscar/i, Search],
    [/^filtrar/i, Filter],
    [/^enviar/i, Send],
    [/^entrar/i, LogIn],
    [/^ver\b/i, Eye],
    [/^editar/i, Pencil],
    [/^confirmar/i, Check],
    [/^tentar/i, RotateCcw],
    [/^(alterar senha|redefinir)/i, KeyRound],
    [/^reenviar/i, Mail],
    [/^copiar/i, Copy],
];

function inferIcon(children: React.ReactNode): LucideIcon | undefined {
    const label = extractLabel(children);

    if (!label) {
        return undefined;
    }

    const match = inferredIcons.find(([pattern]) => pattern.test(label));

    return match?.[1];
}

export function Button({
    variant = 'primary',
    size = 'md',
    href,
    method = 'get',
    as,
    asChild = false,
    className,
    children,
    type = 'button',
    processing = false,
    disabled,
    icon,
    ...props
}: ButtonProps) {
    const mapped = variantMap[variant];
    const uiSize = size === 'sm' ? 'sm' : 'default';
    const extra = variant === 'danger-ghost' ? 'text-destructive hover:bg-destructive/10 hover:text-destructive' : undefined;

    if (asChild) {
        return (
            <UiButton variant={mapped} size={uiSize} className={cn(extra, className)} asChild disabled={disabled || processing}>
                {children}
            </UiButton>
        );
    }

    const Icon = icon === false ? undefined : (icon ?? inferIcon(children));

    const content = (
        <>
            {processing ? <LoaderCircle className="animate-spin" aria-hidden /> : Icon && <Icon aria-hidden />}
            {children}
        </>
    );

    if (href || as === 'link') {
        return (
            <UiButton variant={mapped} size={uiSize} className={cn(extra, className)} asChild disabled={disabled || processing}>
                <Link href={href ?? '#'} method={method}>
                    {content}
                </Link>
            </UiButton>
        );
    }

    return (
        <UiButton type={type} variant={mapped} size={uiSize} className={cn(extra, className)} disabled={disabled || processing} {...props}>
            {content}
        </UiButton>
    );
}

export function PrimaryButton(props: Omit<ButtonProps, 'variant'>) {
    return <Button variant="primary" {...props} />;
}

export function SecondaryButton(props: Omit<ButtonProps, 'variant'>) {
    return <Button variant="secondary" {...props} />;
}

export function NeutralButton(props: Omit<ButtonProps, 'variant'>) {
    return <Button variant="neutral" {...props} />;
}

export function DangerButton(props: Omit<ButtonProps, 'variant'>) {
    return <Button variant="danger" {...props} />;
}

export function LinkButton({
    href,
    className,
    children,
    size = 'sm',
}: {
    href: string;
    className?: string;
    children: React.ReactNode;
    size?: ButtonSize;
}) {
    return (
        <Button href={href} variant="ghost" size={size} className={className}>
            {children}
        </Button>
    );
}

export function CreateButton({ children = 'Adicionar', ...props }: Omit<ButtonProps, 'variant' | 'icon'>) {
    return (
        <Button variant="primary" icon={Plus} {...props}>
            {children}
        </Button>
    );
}

export function SaveButton({ children = 'Salvar', ...props }: Omit<ButtonProps, 'variant' | 'icon'>) {
    return (
        <Button variant="primary" type="submit" icon={Save} {...props}>
            {children}
        </Button>
    );
}

export function CancelButton({ children = 'Cancelar', ...props }: Omit<ButtonProps, 'variant' | 'icon'>) {
    return (
        <Button variant="secondary" icon={X} {...props}>
            {children}
        </Button>
    );
}

export function SearchButton({ children = 'Buscar', ...props }: Omit<ButtonProps, 'variant' | 'icon'>) {
    return (
        <Button variant="neutral" type="submit" icon={Search} {...props}>
            {children}
        </Button>
    );
}

export function FilterButton({ children = 'Filtrar', ...props }: Omit<ButtonProps, 'variant' | 'icon'>) {
    return (
        <Button variant="neutral" type="submit" icon={Filter} {...props}>
            {children}
        </Button>
    );
}

export function ViewButton({
    href,
    children = 'Ver',
    size = 'sm',
    className,
}: {
    href: string;
    children?: React.ReactNode;
    size?: ButtonSize;
    className?: string;
}) {
    return (
        <Button href={href} variant="ghost" size={size} icon={Eye} className={className}>
            {children}
        </Button>
    );
}

export function EditButton({
    href,
    children = 'Editar',
    size = 'sm',
    className,
}: {
    href: string;
    children?: React.ReactNode;
    size?: ButtonSize;
    className?: string;
}) {
    return (
        <Button href={href} variant="neutral" size={size} icon={Pencil} className={className}>
            {children}
        </Button>
    );
}

export function BackButton({ href, children = 'Voltar', className }: { href: string; children?: React.ReactNode; className?: string }) {
    return (
        <Button href={href} variant="secondary" icon={ArrowLeft} className={className}>
            {children}
        </Button>
    );
}

export function RowActions({ children, className }: { children: React.ReactNode; className?: string }) {
    return <div className={cn('inline-flex flex-wrap items-center justify-end gap-1 sm:flex-nowrap', className)}>{children}</div>;
}

export { buttonVariants };
