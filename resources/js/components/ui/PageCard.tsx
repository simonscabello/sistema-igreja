import { Head, Link } from '@inertiajs/react';
import { Search } from 'lucide-react';
import { Paginated } from '@/types';
import { cn } from '@/lib/utils';
import { CreateButton, SearchButton } from './Button';
import { buttonVariants } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import { Pagination as UiPagination, PaginationContent, PaginationItem } from '@/components/ui/pagination';

interface BreadcrumbItemData {
    label: string;
    href?: string;
}

interface PageCardProps {
    title?: string;
    description?: string;
    breadcrumbs?: BreadcrumbItemData[];
    actions?: string;
    actionsLabel?: string;
    action?: React.ReactNode;
    children: React.ReactNode;
}

export function PageHeader({
    title,
    description,
    breadcrumbs,
    action,
}: {
    title?: string;
    description?: string;
    breadcrumbs?: BreadcrumbItemData[];
    action?: React.ReactNode;
}) {
    return (
        <header className="mb-6 flex flex-col gap-4 sm:mb-8 sm:flex-row sm:items-end sm:justify-between">
            <div className="min-w-0 space-y-2">
                {breadcrumbs && breadcrumbs.length > 0 && (
                    <Breadcrumb>
                        <BreadcrumbList>
                            {breadcrumbs.map((item, index) => (
                                <BreadcrumbItem key={`${item.label}-${index}`}>
                                    {index > 0 && <BreadcrumbSeparator />}
                                    {item.href ? (
                                        <BreadcrumbLink asChild>
                                            <Link href={item.href}>{item.label}</Link>
                                        </BreadcrumbLink>
                                    ) : (
                                        <BreadcrumbPage>{item.label}</BreadcrumbPage>
                                    )}
                                </BreadcrumbItem>
                            ))}
                        </BreadcrumbList>
                    </Breadcrumb>
                )}
                {title && <h1 className="text-2xl font-semibold tracking-tight sm:text-3xl">{title}</h1>}
                {description && <p className="max-w-2xl text-sm text-muted-foreground">{description}</p>}
            </div>
            {action && <div className="flex shrink-0 flex-wrap items-center gap-2">{action}</div>}
        </header>
    );
}

export function PageCard({ title, description, breadcrumbs, actions, actionsLabel, action, children }: PageCardProps) {
    const defaultLabel = actions?.includes('worship-sets') ? 'Novo culto' : 'Adicionar';
    const resolvedAction = action ?? (actions ? <CreateButton href={actions}>{actionsLabel ?? defaultLabel}</CreateButton> : undefined);

    return (
        <div className="mx-auto w-full max-w-7xl px-4 py-5 sm:px-6 sm:py-8 lg:px-8">
            {title && <Head title={title} />}
            {(title || resolvedAction || breadcrumbs) && (
                <PageHeader title={title} description={description} breadcrumbs={breadcrumbs} action={resolvedAction} />
            )}
            {children}
        </div>
    );
}

interface PaginationProps<T> {
    paginator: Paginated<T>;
}

function paginationLabel(label: string): string {
    return label
        .replace(/&laquo;|&lsaquo;/g, '‹')
        .replace(/&raquo;|&rsaquo;/g, '›')
        .replace(/&amp;/g, '&')
        .replace(/<[^>]+>/g, '')
        .trim();
}

export function Pagination<T>({ paginator }: PaginationProps<T>) {
    if (paginator.last_page <= 1) {
        return null;
    }

    return (
        <div className="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p className="text-sm text-muted-foreground">
                {paginator.from}–{paginator.to} de {paginator.total}
            </p>
            <UiPagination className="mx-0 w-auto justify-start sm:justify-end">
                <PaginationContent className="flex-wrap">
                    {paginator.links.map((link, index) => {
                        const label = paginationLabel(link.label);

                        return (
                            <PaginationItem key={index}>
                                {link.url ? (
                                    <Link
                                        href={link.url}
                                        preserveScroll
                                        aria-current={link.active ? 'page' : undefined}
                                        aria-label={label}
                                        className={cn(
                                            buttonVariants({
                                                variant: link.active ? 'outline' : 'ghost',
                                                size: 'icon',
                                            }),
                                            'min-w-10',
                                        )}
                                    >
                                        {label}
                                    </Link>
                                ) : (
                                    <span className="inline-flex h-10 min-w-10 items-center justify-center px-3 text-sm text-muted-foreground/50">
                                        {label}
                                    </span>
                                )}
                            </PaginationItem>
                        );
                    })}
                </PaginationContent>
            </UiPagination>
        </div>
    );
}

interface SearchFormProps {
    placeholder?: string;
    value?: string;
    onChange: (value: string) => void;
    onSubmit: () => void;
    action?: string;
    children?: React.ReactNode;
}

export function SearchForm({ placeholder = 'Buscar...', value, onChange, onSubmit, children }: SearchFormProps) {
    return (
        <form
            onSubmit={(event) => {
                event.preventDefault();
                onSubmit();
            }}
            className="flex w-full flex-col gap-2 sm:flex-row sm:items-center"
        >
            <div className="relative min-w-0 flex-1">
                <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                <Input
                    type="search"
                    value={value ?? ''}
                    onChange={(event) => onChange(event.target.value)}
                    placeholder={placeholder}
                    className="pl-10"
                />
            </div>
            {children}
            <SearchButton />
        </form>
    );
}
