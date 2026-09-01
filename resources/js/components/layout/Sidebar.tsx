import { Link, usePage } from '@inertiajs/react';
import {
    ArrowLeftRight,
    Building2,
    FileBarChart,
    Home,
    KeyRound,
    ListMusic,
    LogOut,
    Menu,
    Moon,
    Music,
    Search,
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
} from 'lucide-react';
import { Can } from '@/components/layout/Can';
import { CommandMenu } from '@/components/layout/CommandMenu';
import { Badge } from '@/components/ui/Badge';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';
import { Sheet, SheetContent, SheetHeader, SheetTitle } from '@/components/ui/sheet';
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
                'flex min-h-10 items-center gap-2.5 rounded-md px-2.5 text-sm font-medium transition-colors',
                active
                    ? 'bg-sidebar-accent text-sidebar-accent-foreground'
                    : 'text-sidebar-foreground/70 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground',
            )}
        >
            {icon && <span className="shrink-0">{icon}</span>}
            <span className="truncate">{children}</span>
        </Link>
    );
}

function NavSection({ label, children }: { label: string; children: React.ReactNode }) {
    return (
        <div className="pt-4">
            <p className="px-2.5 pb-1.5 text-[11px] font-semibold uppercase tracking-wider text-sidebar-foreground/45">{label}</p>
            <div className="space-y-0.5">{children}</div>
        </div>
    );
}

function SidebarNav({ onNavigate }: { onNavigate?: () => void }) {
    const { url } = usePage<PageProps>();
    const iconClass = 'h-4 w-4';
    const pathActive = (path: string) => url === path || url.startsWith(`${path}/`) || url.startsWith(`${path}?`);

    return (
        <nav className="space-y-1 px-3 py-3" aria-label="Principal">
            <SidebarLink
                href={route('dashboard')}
                active={url === '/dashboard' || url.startsWith('/dashboard?')}
                onNavigate={onNavigate}
                icon={<Home className={iconClass} />}
            >
                Início
            </SidebarLink>

            <Can permissions={['visualizar_membros', 'visualizar_visitantes', 'visualizar_departamentos']}>
                <NavSection label="Pessoas">
                    <Can permission="visualizar_membros">
                        <SidebarLink
                            href={route('members.index')}
                            active={pathActive('/members')}
                            onNavigate={onNavigate}
                            icon={<Users className={iconClass} />}
                        >
                            Membros
                        </SidebarLink>
                    </Can>
                    <Can permission="visualizar_visitantes">
                        <SidebarLink
                            href={route('visitors.index')}
                            active={pathActive('/visitors')}
                            onNavigate={onNavigate}
                            icon={<UserPlus className={iconClass} />}
                        >
                            Visitantes
                        </SidebarLink>
                    </Can>
                    <Can permission="visualizar_departamentos">
                        <SidebarLink
                            href={route('departments.index')}
                            active={pathActive('/departments')}
                            onNavigate={onNavigate}
                            icon={<Building2 className={iconClass} />}
                        >
                            Departamentos
                        </SidebarLink>
                    </Can>
                </NavSection>
            </Can>

            <Can permissions={['visualizar_financeiro', 'gerenciar_categorias_financeiras']}>
                <NavSection label="Finanças">
                    <Can permission="visualizar_financeiro">
                        <SidebarLink
                            href={route('financial.dashboard.index')}
                            active={pathActive('/financial/dashboard')}
                            onNavigate={onNavigate}
                            icon={<Wallet className={iconClass} />}
                        >
                            Caixa
                        </SidebarLink>
                        <SidebarLink
                            href={route('financial.transactions.index')}
                            active={pathActive('/financial/transactions')}
                            onNavigate={onNavigate}
                            icon={<ArrowLeftRight className={iconClass} />}
                        >
                            Transações
                        </SidebarLink>
                        <SidebarLink
                            href={route('financial.campaigns.index')}
                            active={pathActive('/financial/campaigns')}
                            onNavigate={onNavigate}
                            icon={<Target className={iconClass} />}
                        >
                            Campanhas
                        </SidebarLink>
                        <SidebarLink
                            href={route('financial.reports.index')}
                            active={url.includes('/financial/reports')}
                            onNavigate={onNavigate}
                            icon={<FileBarChart className={iconClass} />}
                        >
                            Relatórios
                        </SidebarLink>
                    </Can>
                    <Can permission="gerenciar_categorias_financeiras">
                        <SidebarLink
                            href={route('financial.categories.index')}
                            active={pathActive('/financial/categories')}
                            onNavigate={onNavigate}
                            icon={<Tags className={iconClass} />}
                        >
                            Categorias
                        </SidebarLink>
                        <SidebarLink
                            href={route('financial.subcategories.index')}
                            active={pathActive('/financial/subcategories')}
                            onNavigate={onNavigate}
                            icon={<Tag className={iconClass} />}
                        >
                            Subcategorias
                        </SidebarLink>
                    </Can>
                </NavSection>
            </Can>

            <Can permissions={['visualizar_musicas', 'visualizar_escalas_louvor']}>
                <NavSection label="Louvor">
                    <Can permission="visualizar_musicas">
                        <SidebarLink
                            href={route('songs.index')}
                            active={pathActive('/worship/songs')}
                            onNavigate={onNavigate}
                            icon={<Music className={iconClass} />}
                        >
                            Músicas
                        </SidebarLink>
                    </Can>
                    <Can permission="visualizar_escalas_louvor">
                        <SidebarLink
                            href={route('worship-functions.index')}
                            active={pathActive('/worship/functions')}
                            onNavigate={onNavigate}
                            icon={<Users className={iconClass} />}
                        >
                            Funções
                        </SidebarLink>
                        <SidebarLink
                            href={route('worship-sets.index')}
                            active={pathActive('/worship/sets')}
                            onNavigate={onNavigate}
                            icon={<ListMusic className={iconClass} />}
                        >
                            Cultos
                        </SidebarLink>
                    </Can>
                </NavSection>
            </Can>

            <Can permissions={['gerenciar_roles', 'gerenciar_permissoes', 'gerenciar_usuarios']}>
                <NavSection label="Sistema">
                    <Can permission="gerenciar_usuarios">
                        <SidebarLink
                            href={route('users.index')}
                            active={pathActive('/users')}
                            onNavigate={onNavigate}
                            icon={<UserCog className={iconClass} />}
                        >
                            Usuários
                        </SidebarLink>
                    </Can>
                    <Can permission="gerenciar_roles">
                        <SidebarLink
                            href={route('roles.index')}
                            active={pathActive('/roles')}
                            onNavigate={onNavigate}
                            icon={<Shield className={iconClass} />}
                        >
                            Papéis
                        </SidebarLink>
                    </Can>
                    <Can permission="gerenciar_permissoes">
                        <SidebarLink
                            href={route('permissions.index')}
                            active={pathActive('/permissions')}
                            onNavigate={onNavigate}
                            icon={<KeyRound className={iconClass} />}
                        >
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
            <img src="/sib-icon-white.png" alt="" className="hidden h-8 w-8 dark:block" />
            <img src="/sib-icon-dark.png" alt="" className="h-8 w-8 dark:hidden" />
            <span className="truncate text-base font-semibold text-sidebar-foreground">{appName}</span>
        </Link>
    );
}

export function Sidebar({ open, onClose, appName }: { open: boolean; onClose: () => void; appName: string }) {
    return (
        <>
            <aside className="fixed inset-y-0 left-0 z-40 hidden w-60 flex-col border-r border-sidebar-border bg-sidebar lg:flex">
                <div className="flex h-14 items-center px-4">
                    <Brand appName={appName} />
                </div>
                <Separator />
                <ScrollArea className="flex-1">
                    <SidebarNav />
                </ScrollArea>
            </aside>

            <Sheet open={open} onOpenChange={(next) => !next && onClose()}>
                <SheetContent side="left" className="w-72 bg-sidebar p-0 text-sidebar-foreground">
                    <SheetHeader className="h-14 justify-center border-b border-sidebar-border px-4 text-left">
                        <SheetTitle className="sr-only">Menu</SheetTitle>
                        <Brand appName={appName} />
                    </SheetHeader>
                    <ScrollArea className="h-[calc(100dvh-3.5rem)]">
                        <SidebarNav onNavigate={onClose} />
                    </ScrollArea>
                </SheetContent>
            </Sheet>
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
        ['/worship/functions', 'Funções'],
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
    const label = pageLabel(url);

    return (
        <header className="sticky top-0 z-30 border-b bg-background/90 backdrop-blur">
            <div className="flex h-14 items-center gap-2 px-3 sm:px-5">
                <Button type="button" variant="ghost" size="icon" className="lg:hidden" onClick={onMenuToggle} aria-label="Abrir menu">
                    <Menu className="h-5 w-5" />
                </Button>

                <div className="flex min-w-0 items-center gap-2 lg:hidden">
                    <img src="/sib-icon-dark.png" alt="" className="h-7 w-7 dark:hidden" />
                    <img src="/sib-icon-white.png" alt="" className="hidden h-7 w-7 dark:block" />
                    <span className="truncate text-sm font-semibold">{label || appName}</span>
                </div>

                <p className="hidden truncate text-sm font-medium lg:block">{label}</p>

                <div className="ml-auto flex items-center gap-1">
                    {!isProduction && (
                        <Badge tone="warning" className="mr-1 hidden sm:inline-flex">
                            Ambiente de teste
                        </Badge>
                    )}
                    <Button
                        type="button"
                        variant="outline"
                        size="sm"
                        className="hidden h-9 gap-2 text-muted-foreground md:inline-flex"
                        onClick={() => window.dispatchEvent(new Event('open-command-menu'))}
                    >
                        <Search className="h-3.5 w-3.5" />
                        Buscar
                        <kbd className="pointer-events-none rounded border bg-muted px-1.5 font-mono text-[10px]">⌘K</kbd>
                    </Button>
                    <CommandMenu />
                    <Button
                        type="button"
                        variant="ghost"
                        size="icon"
                        onClick={onThemeToggle}
                        aria-label={theme === 'dark' ? 'Usar tema claro' : 'Usar tema escuro'}
                    >
                        {theme === 'dark' ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
                    </Button>
                    {userName && (
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant="ghost" className="max-w-52 gap-2">
                                    <User className="h-4 w-4 shrink-0" />
                                    <span className="hidden truncate sm:inline">{userName}</span>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" className="w-48">
                                <DropdownMenuLabel className="font-normal">
                                    <p className="truncate text-sm font-medium">{userName}</p>
                                </DropdownMenuLabel>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem asChild>
                                    <Link href={route('profile.edit')}>Perfil</Link>
                                </DropdownMenuItem>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem asChild className="text-destructive focus:text-destructive">
                                    <Link href={route('logout')} method="post" as="button" className="w-full">
                                        <LogOut className="h-4 w-4" />
                                        Sair
                                    </Link>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    )}
                </div>
            </div>
        </header>
    );
}
