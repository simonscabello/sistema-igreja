import { Link, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import {
    Building2,
    ChevronDown,
    FileBarChart,
    Home,
    KeyRound,
    ListMusic,
    LogOut,
    Menu,
    Moon,
    Music,
    Shield,
    Sun,
    Tag,
    Tags,
    Target,
    User,
    UserCog,
    UserPlus,
    Users,
    Wallet,
    ArrowLeftRight,
    X,
} from 'lucide-react';
import { Can } from '@/components/layout/Can';
import { PageProps } from '@/types';
import { cn, route } from '@/utils';

interface SidebarLinkProps {
    href: string;
    active: boolean;
    onNavigate?: () => void;
    icon?: React.ReactNode;
    children: React.ReactNode;
}

function SidebarLink({ href, active, onNavigate, icon, children }: SidebarLinkProps) {
    return (
        <Link
            href={href}
            onClick={onNavigate}
            aria-current={active ? 'page' : undefined}
            className={cn(
                'flex min-h-10 items-center gap-2.5 rounded-lg px-2.5 text-sm font-medium transition-colors duration-150',
                active
                    ? 'bg-primary/12 text-primary-dark dark:bg-primary/20 dark:text-primary-light'
                    : 'text-ink-muted hover:bg-white/60 hover:text-ink dark:text-ink-inverse/70 dark:hover:bg-white/5 dark:hover:text-ink-inverse',
            )}
        >
            {icon && <span className={cn('shrink-0', active ? 'text-primary' : 'text-ink-muted/80')}>{icon}</span>}
            <span className="truncate">{children}</span>
        </Link>
    );
}

function NavSection({ label, children }: { label: string; children: React.ReactNode }) {
    return (
        <div className="pt-4">
            <p className="px-2.5 pb-1.5 text-[11px] font-semibold uppercase tracking-wider text-ink-muted/80 dark:text-ink-inverse/45">
                {label}
            </p>
            <div className="space-y-0.5">{children}</div>
        </div>
    );
}

function SidebarNav({ onNavigate }: { onNavigate?: () => void }) {
    const { url } = usePage<PageProps>();
    const iconClass = 'h-4 w-4';

    const pathActive = (path: string) => url === path || url.startsWith(`${path}/`) || url.startsWith(`${path}?`);

    return (
        <nav className="flex-1 space-y-1 overflow-y-auto px-3 py-3" aria-label="Principal">
            <SidebarLink href={route('dashboard')} active={url === '/dashboard' || url.startsWith('/dashboard?')} onNavigate={onNavigate} icon={<Home className={iconClass} />}>
                Início
            </SidebarLink>

            <Can permissions={['visualizar_membros', 'visualizar_visitantes', 'visualizar_departamentos']}>
                <NavSection label="Pessoas">
                    <Can permission="visualizar_membros">
                        <SidebarLink href={route('members.index')} active={pathActive('/members')} onNavigate={onNavigate} icon={<Users className={iconClass} />}>
                            Membros
                        </SidebarLink>
                    </Can>
                    <Can permission="visualizar_visitantes">
                        <SidebarLink href={route('visitors.index')} active={pathActive('/visitors')} onNavigate={onNavigate} icon={<UserPlus className={iconClass} />}>
                            Visitantes
                        </SidebarLink>
                    </Can>
                    <Can permission="visualizar_departamentos">
                        <SidebarLink href={route('departments.index')} active={pathActive('/departments')} onNavigate={onNavigate} icon={<Building2 className={iconClass} />}>
                            Departamentos
                        </SidebarLink>
                    </Can>
                </NavSection>
            </Can>

            <Can permissions={['visualizar_financeiro', 'gerenciar_categorias_financeiras']}>
                <NavSection label="Finanças">
                    <Can permission="visualizar_financeiro">
                        <SidebarLink href={route('financial.dashboard.index')} active={pathActive('/financial/dashboard')} onNavigate={onNavigate} icon={<Wallet className={iconClass} />}>
                            Caixa
                        </SidebarLink>
                        <SidebarLink href={route('financial.transactions.index')} active={pathActive('/financial/transactions')} onNavigate={onNavigate} icon={<ArrowLeftRight className={iconClass} />}>
                            Transações
                        </SidebarLink>
                        <SidebarLink href={route('financial.campaigns.index')} active={pathActive('/financial/campaigns')} onNavigate={onNavigate} icon={<Target className={iconClass} />}>
                            Campanhas
                        </SidebarLink>
                        <SidebarLink href={route('financial.reports.index')} active={url.includes('/financial/reports')} onNavigate={onNavigate} icon={<FileBarChart className={iconClass} />}>
                            Relatórios
                        </SidebarLink>
                    </Can>
                    <Can permission="gerenciar_categorias_financeiras">
                        <SidebarLink href={route('financial.categories.index')} active={pathActive('/financial/categories')} onNavigate={onNavigate} icon={<Tags className={iconClass} />}>
                            Categorias
                        </SidebarLink>
                        <SidebarLink href={route('financial.subcategories.index')} active={pathActive('/financial/subcategories')} onNavigate={onNavigate} icon={<Tag className={iconClass} />}>
                            Subcategorias
                        </SidebarLink>
                    </Can>
                </NavSection>
            </Can>

            <Can permissions={['visualizar_musicas', 'visualizar_escalas_louvor']}>
                <NavSection label="Louvor">
                    <Can permission="visualizar_musicas">
                        <SidebarLink href={route('songs.index')} active={pathActive('/worship/songs')} onNavigate={onNavigate} icon={<Music className={iconClass} />}>
                            Músicas
                        </SidebarLink>
                    </Can>
                    <Can permission="visualizar_escalas_louvor">
                        <SidebarLink href={route('worship-sets.index')} active={pathActive('/worship/sets')} onNavigate={onNavigate} icon={<ListMusic className={iconClass} />}>
                            Cultos
                        </SidebarLink>
                    </Can>
                </NavSection>
            </Can>

            <Can permissions={['gerenciar_roles', 'gerenciar_permissoes', 'gerenciar_usuarios']}>
                <NavSection label="Sistema">
                    <Can permission="gerenciar_usuarios">
                        <SidebarLink href={route('users.index')} active={pathActive('/users')} onNavigate={onNavigate} icon={<UserCog className={iconClass} />}>
                            Usuários
                        </SidebarLink>
                    </Can>
                    <Can permission="gerenciar_roles">
                        <SidebarLink href={route('roles.index')} active={pathActive('/roles')} onNavigate={onNavigate} icon={<Shield className={iconClass} />}>
                            Papéis
                        </SidebarLink>
                    </Can>
                    <Can permission="gerenciar_permissoes">
                        <SidebarLink href={route('permissions.index')} active={pathActive('/permissions')} onNavigate={onNavigate} icon={<KeyRound className={iconClass} />}>
                            Permissões
                        </SidebarLink>
                    </Can>
                </NavSection>
            </Can>
        </nav>
    );
}

function Brand({ appName }: { appName: string }) {
    return (
        <Link href={route('dashboard')} className="flex min-w-0 items-center gap-2.5">
            <img src="/images/sib-icon-white.png" alt="" className="hidden h-8 w-8 dark:block" />
            <img src="/images/sib-icon-dark.png" alt="" className="h-8 w-8 dark:hidden" />
            <span className="truncate text-base font-semibold text-ink dark:text-ink-inverse">{appName}</span>
        </Link>
    );
}

export function Sidebar({ open, onClose, appName }: { open: boolean; onClose: () => void; appName: string }) {
    return (
        <>
            <aside className="fixed inset-y-0 left-0 z-40 hidden w-60 flex-col border-r border-line bg-surface dark:border-line-dark dark:bg-surface-dark lg:flex">
                <div className="flex h-14 items-center px-4">
                    <Brand appName={appName} />
                </div>
                <SidebarNav />
            </aside>

            {open && (
                <button
                    type="button"
                    className="fixed inset-0 z-40 bg-canvas-dark/50 lg:hidden"
                    onClick={onClose}
                    aria-label="Fechar menu"
                />
            )}

            <aside
                className={cn(
                    'fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] flex-col border-r border-line bg-surface transition-transform duration-200 dark:border-line-dark dark:bg-surface-dark lg:hidden',
                    open ? 'translate-x-0' : '-translate-x-full',
                )}
                aria-hidden={!open}
            >
                <div className="flex h-14 items-center justify-between px-4">
                    <Brand appName={appName} />
                    <button type="button" onClick={onClose} className="inline-flex h-10 w-10 items-center justify-center rounded-lg text-ink-muted" aria-label="Fechar menu">
                        <X className="h-5 w-5" />
                    </button>
                </div>
                <SidebarNav onNavigate={onClose} />
            </aside>
        </>
    );
}

function pageLabel(url: string): string {
    const rules: Array<[string, string]> = [
        ['/dashboard', 'Início'],
        ['/members', 'Membros'],
        ['/visitors', 'Visitantes'],
        ['/departments', 'Departamentos'],
        ['/financial/dashboard', 'Caixa'],
        ['/financial/transactions', 'Transações'],
        ['/financial/campaigns', 'Campanhas'],
        ['/financial/reports', 'Relatórios'],
        ['/financial/categories', 'Categorias'],
        ['/financial/subcategories', 'Subcategorias'],
        ['/worship/songs', 'Músicas'],
        ['/worship/sets', 'Cultos'],
        ['/users', 'Usuários'],
        ['/roles', 'Papéis'],
        ['/permissions', 'Permissões'],
        ['/profile', 'Perfil'],
    ];

    const match = rules.find(([path]) => url === path || url.startsWith(`${path}/`) || url.startsWith(`${path}?`));

    return match?.[1] ?? '';
}

export function Header({
    appName,
    userName,
    onMenuToggle,
    onThemeToggle,
    isProduction,
    theme,
}: {
    appName: string;
    userName?: string;
    onMenuToggle: () => void;
    onThemeToggle: () => void;
    isProduction: boolean;
    theme: 'light' | 'dark';
}) {
    const { url } = usePage<PageProps>();
    const [menuOpen, setMenuOpen] = useState(false);
    const label = pageLabel(url);

    useEffect(() => {
        if (!menuOpen) {
            return;
        }

        const close = () => setMenuOpen(false);
        document.addEventListener('click', close);

        return () => document.removeEventListener('click', close);
    }, [menuOpen]);

    return (
        <header className="sticky top-0 z-30 border-b border-line bg-surface/90 backdrop-blur dark:border-line-dark dark:bg-surface-dark/90">
            <div className="flex h-14 items-center gap-2 px-3 sm:px-5">
                <button
                    type="button"
                    onClick={onMenuToggle}
                    className="inline-flex h-10 w-10 items-center justify-center rounded-lg text-ink lg:hidden dark:text-ink-inverse"
                    aria-label="Abrir menu"
                >
                    <Menu className="h-5 w-5" />
                </button>

                <div className="flex min-w-0 items-center gap-2 lg:hidden">
                    <img src="/images/sib-icon-dark.png" alt="" className="h-7 w-7 dark:hidden" />
                    <img src="/images/sib-icon-white.png" alt="" className="hidden h-7 w-7 dark:block" />
                    <span className="truncate text-sm font-semibold text-ink dark:text-ink-inverse">
                        {label || appName}
                    </span>
                </div>

                <p className="hidden truncate text-sm font-medium text-ink dark:text-ink-inverse lg:block">{label}</p>

                <div className="ml-auto flex items-center gap-1">
                    {!isProduction && (
                        <span className="mr-1 hidden rounded-full bg-accent-subtle px-2.5 py-0.5 text-xs font-medium text-amber-900 sm:inline dark:bg-accent/20 dark:text-accent-subtle">
                            Ambiente de teste
                        </span>
                    )}
                    <button
                        type="button"
                        onClick={onThemeToggle}
                        className="inline-flex h-10 w-10 items-center justify-center rounded-lg text-ink-muted hover:bg-canvas hover:text-ink dark:hover:bg-white/5 dark:text-ink-inverse"
                        aria-label={theme === 'dark' ? 'Usar tema claro' : 'Usar tema escuro'}
                    >
                        {theme === 'dark' ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
                    </button>
                    {userName && (
                        <div className="relative">
                            <button
                                type="button"
                                onClick={(event) => {
                                    event.stopPropagation();
                                    setMenuOpen((current) => !current);
                                }}
                                className="inline-flex min-h-10 max-w-40 items-center gap-1 rounded-lg px-2 text-sm font-medium text-ink hover:bg-canvas dark:text-ink-inverse dark:hover:bg-white/5"
                                aria-expanded={menuOpen}
                                aria-haspopup="menu"
                            >
                                <User className="h-4 w-4 shrink-0" />
                                <span className="hidden truncate sm:inline">{userName}</span>
                                <ChevronDown className="h-3.5 w-3.5 opacity-60" />
                            </button>
                            {menuOpen && (
                                <div
                                    className="absolute right-0 mt-1 w-48 rounded-xl border border-line bg-surface py-1 shadow-float dark:border-line-dark dark:bg-surface-dark"
                                    role="menu"
                                    onClick={(event) => event.stopPropagation()}
                                >
                                    <Link
                                        href={route('profile.edit')}
                                        className="flex items-center gap-2 px-3 py-2.5 text-sm text-ink hover:bg-canvas dark:text-ink-inverse dark:hover:bg-white/5"
                                        role="menuitem"
                                    >
                                        Perfil
                                    </Link>
                                    <Link
                                        href={route('logout')}
                                        method="post"
                                        as="button"
                                        className="flex w-full items-center gap-2 px-3 py-2.5 text-left text-sm text-saida hover:bg-saida-soft dark:hover:bg-saida/15"
                                        role="menuitem"
                                    >
                                        <LogOut className="h-4 w-4" />
                                        Sair
                                    </Link>
                                </div>
                            )}
                        </div>
                    )}
                </div>
            </div>
        </header>
    );
}
