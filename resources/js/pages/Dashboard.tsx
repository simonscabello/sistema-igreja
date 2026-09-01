import { Head, Link } from '@inertiajs/react';
import { ArrowRight, Cake, Church, Music, UserPlus, Users, Wallet } from 'lucide-react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Can } from '@/components/layout/Can';
import { Avatar } from '@/components/ui/Avatar';
import { Badge } from '@/components/ui/Badge';
import { EmptyState } from '@/components/ui/EmptyState';
import { Button } from '@/components/ui/Button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useAuthUser, useCan } from '@/hooks/useCan';
import { formatCurrency, formatNumber, route } from '@/utils';

interface Aniversariante {
    id?: number;
    nome: string;
    mobile?: string | null;
    data: string;
    tipo: string;
    foto_url: string;
    is_today: boolean;
}

interface UltimoVisitante {
    id: number;
    nome: string;
    mobile?: string | null;
    primeira_visita: string;
    wants_contact?: boolean;
}

interface ProximoCulto {
    id: number;
    date: string;
    period_label: string;
    songs_count: number;
    escala_definida: boolean;
    vocal: string | null;
    direcao: string | null;
}

interface DashboardProps {
    aniversariantesDoMes: Aniversariante[];
    ultimosVisitantes: UltimoVisitante[];
    visitantesQuerendoContato?: number;
    proximoCulto?: ProximoCulto | null;
    totalMembros: number;
    totalVisitantes: number;
    totalDepartamentos: number;
    saldoAtual?: number;
}

function greeting(name: string): string {
    const hour = new Date().getHours();
    const firstName = name.split(' ')[0] ?? name;

    if (hour < 12) {
        return `Bom dia, ${firstName}`;
    }

    if (hour < 18) {
        return `Boa tarde, ${firstName}`;
    }

    return `Boa noite, ${firstName}`;
}

function todayLabel(): string {
    const label = new Intl.DateTimeFormat('pt-BR', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
    }).format(new Date());

    return label.charAt(0).toUpperCase() + label.slice(1);
}

function QuickLink({ href, icon, children }: { href: string; icon: React.ReactNode; children: React.ReactNode }) {
    return (
        <Button href={href} variant="secondary" icon={false}>
            {icon}
            {children}
        </Button>
    );
}

function Dashboard({
    aniversariantesDoMes,
    ultimosVisitantes,
    visitantesQuerendoContato = 0,
    proximoCulto = null,
    totalMembros,
    totalVisitantes,
    saldoAtual = 0,
}: DashboardProps) {
    const user = useAuthUser();
    const canFinance = useCan('visualizar_financeiro');
    const canMembers = useCan('visualizar_membros');
    const canVisitors = useCan('visualizar_visitantes');
    const canWorship = useCan('visualizar_escalas_louvor');

    const aniversariantesHoje = aniversariantesDoMes.filter((item) => item.is_today);
    const monthName = new Intl.DateTimeFormat('pt-BR', { month: 'long' }).format(new Date());

    return (
        <AppPage>
            <Head title="Início" />
            <div className="mx-auto w-full max-w-7xl px-4 py-5 sm:px-6 sm:py-8 lg:px-8">
                <header className="mb-8">
                    <p className="text-sm text-muted-foreground">{todayLabel()}</p>
                    <h1 className="mt-1 text-2xl font-semibold tracking-tight sm:text-3xl">{user ? greeting(user.name) : 'Início'}</h1>
                    <p className="mt-1 text-sm text-muted-foreground">O que pede atenção nesta semana.</p>
                </header>

                <div className="mb-8 flex flex-wrap gap-2">
                    <Can permission="criar_visitantes">
                        <QuickLink href={route('visitors.create')} icon={<UserPlus className="h-4 w-4" />}>
                            Registrar visitante
                        </QuickLink>
                    </Can>
                    <Can permission="criar_transacoes">
                        <QuickLink href={route('financial.transactions.create')} icon={<Wallet className="h-4 w-4" />}>
                            Lançar transação
                        </QuickLink>
                    </Can>
                    <Can permission="criar_membros">
                        <QuickLink href={route('members.create')} icon={<Users className="h-4 w-4" />}>
                            Novo membro
                        </QuickLink>
                    </Can>
                    <Can permission="gerenciar_escalas_louvor">
                        <QuickLink href={route('worship-sets.create')} icon={<Music className="h-4 w-4" />}>
                            Montar culto
                        </QuickLink>
                    </Can>
                </div>

                <div className="grid gap-6 lg:grid-cols-[minmax(0,1.4fr)_minmax(18rem,0.9fr)]">
                    <div className="space-y-6">
                        {canMembers && (
                            <section>
                                <div className="mb-3 flex items-end justify-between gap-3">
                                    <h2 className="text-base font-semibold">Aniversariantes de {monthName}</h2>
                                    <span className="text-sm text-muted-foreground tabular">{aniversariantesDoMes.length}</span>
                                </div>

                                {aniversariantesDoMes.length === 0 ? (
                                    <EmptyState
                                        title="Nenhum aniversário neste mês"
                                        description="Quando houver membros com data de nascimento cadastrada, eles aparecem aqui."
                                        icon={<Cake className="h-8 w-8" />}
                                    />
                                ) : (
                                    <Card className="shadow-none">
                                        <CardContent className="p-0">
                                            <ul className="divide-y">
                                                {aniversariantesDoMes.map((aniversariante, index) => {
                                                    const content = (
                                                        <>
                                                            <Avatar
                                                                name={aniversariante.nome}
                                                                imageUrl={aniversariante.foto_url}
                                                                size="h-10 w-10"
                                                            />
                                                            <div className="min-w-0 flex-1">
                                                                <p className="truncate font-medium">{aniversariante.nome}</p>
                                                                <p className="text-sm text-muted-foreground">
                                                                    {aniversariante.mobile ?? aniversariante.tipo}
                                                                </p>
                                                            </div>
                                                            <div className="flex shrink-0 flex-col items-end gap-1">
                                                                <span className="tabular text-sm font-medium">{aniversariante.data}</span>
                                                                {aniversariante.is_today && <Badge tone="warning">Hoje</Badge>}
                                                            </div>
                                                        </>
                                                    );

                                                    if (aniversariante.id) {
                                                        return (
                                                            <li key={aniversariante.id}>
                                                                <Link
                                                                    href={route('members.show', aniversariante.id)}
                                                                    className="flex items-center gap-3 px-4 py-3 hover:bg-muted/50"
                                                                >
                                                                    {content}
                                                                </Link>
                                                            </li>
                                                        );
                                                    }

                                                    return (
                                                        <li
                                                            key={`${aniversariante.nome}-${index}`}
                                                            className="flex items-center gap-3 px-4 py-3"
                                                        >
                                                            {content}
                                                        </li>
                                                    );
                                                })}
                                            </ul>
                                        </CardContent>
                                    </Card>
                                )}
                            </section>
                        )}

                        {canVisitors && (
                            <section>
                                <div className="mb-3 flex items-end justify-between gap-3">
                                    <h2 className="text-base font-semibold">Visitantes recentes</h2>
                                    {visitantesQuerendoContato > 0 && (
                                        <Badge tone="success">{visitantesQuerendoContato} querem contato</Badge>
                                    )}
                                </div>

                                {ultimosVisitantes.length === 0 ? (
                                    <EmptyState
                                        title="Ainda não há visitantes este mês"
                                        description="Registre quem passou no culto para o acompanhamento pastoral."
                                        icon={<UserPlus className="h-8 w-8" />}
                                    />
                                ) : (
                                    <Card className="shadow-none">
                                        <CardContent className="p-0">
                                            <ul className="divide-y">
                                                {ultimosVisitantes.map((visitante) => (
                                                    <li key={visitante.id}>
                                                        <Link
                                                            href={route('visitors.show', visitante.id)}
                                                            className="flex items-center gap-3 px-4 py-3 hover:bg-muted/50"
                                                        >
                                                            <div className="min-w-0 flex-1">
                                                                <p className="truncate font-medium">{visitante.nome}</p>
                                                                <p className="text-sm text-muted-foreground">
                                                                    {visitante.primeira_visita}
                                                                    {visitante.mobile ? ` · ${visitante.mobile}` : ''}
                                                                </p>
                                                            </div>
                                                            {visitante.wants_contact && <Badge tone="success">Contato</Badge>}
                                                            <ArrowRight className="h-4 w-4 shrink-0 text-muted-foreground" />
                                                        </Link>
                                                    </li>
                                                ))}
                                            </ul>
                                        </CardContent>
                                    </Card>
                                )}
                            </section>
                        )}
                    </div>

                    <aside className="space-y-4">
                        {(canMembers || canVisitors || canFinance) && (
                            <Card className="shadow-none">
                                <CardHeader className="pb-3">
                                    <CardTitle className="text-sm font-medium text-muted-foreground">Números</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <dl className="space-y-3">
                                        {canMembers && (
                                            <div className="flex items-baseline justify-between gap-3">
                                                <dt className="text-sm">Membros</dt>
                                                <dd className="tabular text-lg font-semibold">{formatNumber(totalMembros)}</dd>
                                            </div>
                                        )}
                                        {canVisitors && (
                                            <div className="flex items-baseline justify-between gap-3">
                                                <dt className="text-sm">Visitantes</dt>
                                                <dd className="tabular text-lg font-semibold">{formatNumber(totalVisitantes)}</dd>
                                            </div>
                                        )}
                                        {canFinance && (
                                            <div className="flex items-baseline justify-between gap-3 border-t pt-3">
                                                <dt className="text-sm">Saldo em caixa</dt>
                                                <dd
                                                    className={`tabular text-lg font-semibold ${
                                                        saldoAtual >= 0 ? 'text-entrada' : 'text-saida'
                                                    }`}
                                                >
                                                    {formatCurrency(saldoAtual)}
                                                </dd>
                                            </div>
                                        )}
                                    </dl>
                                    {canFinance && (
                                        <Link
                                            href={route('financial.dashboard.index')}
                                            className="mt-4 inline-flex items-center gap-1 text-sm font-medium hover:underline"
                                        >
                                            Ver movimentação
                                            <ArrowRight className="h-4 w-4" />
                                        </Link>
                                    )}
                                </CardContent>
                            </Card>
                        )}

                        {canWorship && (
                            <Card className="shadow-none">
                                <CardHeader className="pb-3">
                                    <CardTitle className="flex items-center gap-2 text-sm">
                                        <Church className="h-4 w-4 text-muted-foreground" />
                                        Próximo culto
                                    </CardTitle>
                                </CardHeader>
                                <CardContent>
                                    {proximoCulto ? (
                                        <>
                                            <p className="text-lg font-semibold">
                                                {proximoCulto.date}
                                                <span className="ml-2 text-sm font-medium text-muted-foreground">
                                                    {proximoCulto.period_label}
                                                </span>
                                            </p>
                                            <p className="mt-1 text-sm text-muted-foreground">
                                                {proximoCulto.vocal
                                                    ? `Vocal: ${proximoCulto.vocal}`
                                                    : proximoCulto.escala_definida
                                                      ? 'Vocal ainda não definido'
                                                      : 'Escala ainda não definida'}
                                            </p>
                                            {proximoCulto.direcao && (
                                                <p className="text-sm text-muted-foreground">Direção: {proximoCulto.direcao}</p>
                                            )}
                                            <p className="text-sm text-muted-foreground">
                                                {proximoCulto.songs_count} {proximoCulto.songs_count === 1 ? 'música' : 'músicas'}
                                            </p>
                                            <Link
                                                href={route('worship-sets.show', proximoCulto.id)}
                                                className="mt-3 inline-flex items-center gap-1 text-sm font-medium hover:underline"
                                            >
                                                Abrir repertório
                                                <ArrowRight className="h-4 w-4" />
                                            </Link>
                                        </>
                                    ) : (
                                        <p className="text-sm text-muted-foreground">Monte o repertório do próximo domingo.</p>
                                    )}
                                </CardContent>
                            </Card>
                        )}

                        {aniversariantesHoje.length > 0 && canMembers && (
                            <p className="rounded-xl border border-warning/30 bg-warning/10 px-4 py-3 text-sm text-warning-foreground">
                                {aniversariantesHoje.length === 1
                                    ? `${aniversariantesHoje[0].nome} faz aniversário hoje.`
                                    : `${aniversariantesHoje.length} pessoas fazem aniversário hoje.`}
                            </p>
                        )}
                    </aside>
                </div>
            </div>
        </AppPage>
    );
}

Dashboard.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Dashboard;
