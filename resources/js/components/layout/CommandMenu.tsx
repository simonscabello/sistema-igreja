import { useEffect, useState } from 'react';
import { router, usePage } from '@inertiajs/react';
import {
    ArrowLeftRight,
    Building2,
    FileBarChart,
    Home,
    KeyRound,
    ListMusic,
    Music,
    Shield,
    Tag,
    Tags,
    Target,
    UserCog,
    UserPlus,
    Users,
    Wallet,
} from 'lucide-react';
import { CommandDialog, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { useCan } from '@/hooks/useCan';
import { PageProps } from '@/types';
import { route } from '@/utils';

interface CommandEntry {
    label: string;
    href: string;
    icon: React.ReactNode;
    keywords?: string;
    show: boolean;
}

export function CommandMenu() {
    const [open, setOpen] = useState(false);
    const { auth } = usePage<PageProps>().props;

    const can = {
        members: useCan('visualizar_membros'),
        visitors: useCan('visualizar_visitantes'),
        departments: useCan('visualizar_departamentos'),
        finance: useCan('visualizar_financeiro'),
        categories: useCan('gerenciar_categorias_financeiras'),
        songs: useCan('visualizar_musicas'),
        worship: useCan('visualizar_escalas_louvor'),
        users: useCan('gerenciar_usuarios'),
        roles: useCan('gerenciar_roles'),
        permissions: useCan('gerenciar_permissoes'),
    };

    useEffect(() => {
        const onKeyDown = (event: KeyboardEvent) => {
            if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                setOpen((current) => !current);
            }
        };

        const onOpen = () => setOpen(true);

        document.addEventListener('keydown', onKeyDown);
        window.addEventListener('open-command-menu', onOpen);

        return () => {
            document.removeEventListener('keydown', onKeyDown);
            window.removeEventListener('open-command-menu', onOpen);
        };
    }, []);

    if (!auth.user) {
        return null;
    }

    const go = (href: string) => {
        setOpen(false);
        router.visit(href);
    };

    const items: CommandEntry[] = [
        { label: 'Início', href: route('dashboard'), icon: <Home />, show: true },
        { label: 'Membros', href: route('members.index'), icon: <Users />, keywords: 'pessoas cadastro', show: can.members },
        { label: 'Visitantes', href: route('visitors.index'), icon: <UserPlus />, show: can.visitors },
        { label: 'Departamentos', href: route('departments.index'), icon: <Building2 />, show: can.departments },
        { label: 'Caixa', href: route('financial.dashboard.index'), icon: <Wallet />, keywords: 'financeiro dashboard', show: can.finance },
        { label: 'Transações', href: route('financial.transactions.index'), icon: <ArrowLeftRight />, show: can.finance },
        { label: 'Campanhas', href: route('financial.campaigns.index'), icon: <Target />, show: can.finance },
        { label: 'Relatórios', href: route('financial.reports.index'), icon: <FileBarChart />, show: can.finance },
        { label: 'Categorias', href: route('financial.categories.index'), icon: <Tags />, show: can.categories },
        { label: 'Subcategorias', href: route('financial.subcategories.index'), icon: <Tag />, show: can.categories },
        { label: 'Músicas', href: route('songs.index'), icon: <Music />, show: can.songs },
        { label: 'Funções', href: route('worship-functions.index'), icon: <Users />, show: can.worship },
        { label: 'Cultos', href: route('worship-sets.index'), icon: <ListMusic />, keywords: 'repertório louvor', show: can.worship },
        { label: 'Usuários', href: route('users.index'), icon: <UserCog />, show: can.users },
        { label: 'Papéis', href: route('roles.index'), icon: <Shield />, show: can.roles },
        { label: 'Permissões', href: route('permissions.index'), icon: <KeyRound />, show: can.permissions },
    ];

    return (
        <CommandDialog open={open} onOpenChange={setOpen}>
            <CommandInput placeholder="Ir para uma tela..." />
            <CommandList>
                <CommandEmpty>Nenhuma tela encontrada.</CommandEmpty>
                <CommandGroup heading="Navegação">
                    {items
                        .filter((item) => item.show)
                        .map((item) => (
                            <CommandItem key={item.href} value={`${item.label} ${item.keywords ?? ''}`} onSelect={() => go(item.href)}>
                                {item.icon}
                                {item.label}
                            </CommandItem>
                        ))}
                </CommandGroup>
            </CommandList>
        </CommandDialog>
    );
}
