import { Head, Link } from '@inertiajs/react';
import { Paginated } from '@/types';
import { cn } from '@/utils';
import { Button } from './Button';
import { Search } from 'lucide-react';

interface BreadcrumbItem {
    label: string;
    href?: string;
}

interface PageCardProps {
    title?: string;
    description?: string;
    breadcrumbs?: BreadcrumbItem[];
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
    breadcrumbs?: BreadcrumbItem[];
    action?: React.ReactNode;
}) {
    return (
        <header className="mb-6 flex flex-col gap-4 sm:mb-8 sm:flex-row sm:items-end sm:justify-between">
            <div className="min-w-0">
                {breadcrumbs && breadcrumbs.length > 0 && (
                    <nav aria-label="Trilha" className="mb-2 flex flex-wrap items-center gap-1 text-sm text-ink-muted dark:text-ink-inverse/60">
                        {breadcrumbs.map((item, index) => (
                            <span key={`${item.label}-${index}`} className="flex items-center gap-1">
                                {index > 0 && <span aria-hidden>/</span>}
                                {item.href ? (
                                    <Link href={item.href} className="hover:text-primary">
                                        {item.label}
                                    </Link>
                                ) : (
                                    <span className="text-ink dark:text-ink-inverse">{item.label}</span>
                                )}
                            </span>
                        ))}
                    </nav>
                )}
                {title && (
                    <h1 className="text-2xl font-semibold tracking-tight text-ink dark:text-ink-inverse sm:text-3xl">
                        {title}
                    </h1>
                )}
                {description && (
                    <p className="mt-1 max-w-2xl text-sm text-ink-muted dark:text-ink-inverse/70">{description}</p>
                )}
            </div>
            {action && <div className="flex shrink-0 flex-wrap items-center gap-2">{action}</div>}
        </header>
    );
}

export function PageCard({
    title,
    description,
    breadcrumbs,
    actions,
    actionsLabel,
    action,
    children,
}: PageCardProps) {
    const defaultLabel = actions?.includes('worship-sets') ? 'Novo culto' : 'Adicionar';
    const resolvedAction =
        action ??
        (actions ? (
            <Button href={actions}>{actionsLabel ?? defaultLabel}</Button>
        ) : undefined);

    return (
        <div className="mx-auto w-full max-w-7xl px-4 py-5 sm:px-6 sm:py-8 lg:px-8">
            {title && <Head title={title} />}
            {(title || resolvedAction || breadcrumbs) && (
                <PageHeader
                    title={title}
                    description={description}
                    breadcrumbs={breadcrumbs}
                    action={resolvedAction}
                />
            )}
            {children}
        </div>
    );
}

interface PaginationProps<T> {
    paginator: Paginated<T>;
}

export function Pagination<T>({ paginator }: PaginationProps<T>) {
    if (paginator.last_page <= 1) {
        return null;
    }

    return (
        <nav className="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between" aria-label="Paginação">
            <p className="text-sm text-ink-muted dark:text-ink-inverse/70">
                {paginator.from}–{paginator.to} de {paginator.total}
            </p>
            <div className="flex flex-wrap gap-1">
                {paginator.links.map((link, index) => {
                    if (!link.url) {
                        return (
                            <span
                                key={index}
                                className="inline-flex min-h-9 min-w-9 items-center justify-center rounded-lg px-3 text-sm text-ink-muted/50"
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        );
                    }

                    return (
                        <Link
                            key={index}
                            href={link.url}
                            preserveScroll
                            aria-current={link.active ? 'page' : undefined}
                            className={cn(
                                'inline-flex min-h-9 min-w-9 items-center justify-center rounded-lg px-3 text-sm transition-colors',
                                link.active
                                    ? 'bg-primary text-white'
                                    : 'text-ink hover:bg-surface dark:text-ink-inverse dark:hover:bg-surface-dark',
                            )}
                            dangerouslySetInnerHTML={{ __html: link.label }}
                        />
                    );
                })}
            </div>
        </nav>
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
                <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted" />
                <input
                    type="search"
                    value={value ?? ''}
                    onChange={(event) => onChange(event.target.value)}
                    placeholder={placeholder}
                    className="block w-full min-h-touch rounded-lg border-line bg-surface pl-10 text-ink placeholder:text-ink-muted/70 focus:border-primary focus:ring-primary dark:border-line-dark dark:bg-surface-dark dark:text-ink-inverse"
                />
            </div>
            {children}
            <button
                type="submit"
                className="inline-flex min-h-touch items-center justify-center rounded-lg bg-primary px-4 text-sm font-medium text-white hover:bg-primary-dark"
            >
                Buscar
            </button>
        </form>
    );
}
