import { useMemo, useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Badge } from '@/components/ui/Badge';
import { Button } from '@/components/ui/Button';
import { DateInput, Select } from '@/components/ui/Input';
import { RowActionsMenu } from '@/components/ui/RowActionsMenu';
import { ActionsTh, DesktopOnly, MobileCard, MobileCardHeader, MobileList, Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { EmptyState } from '@/components/ui/EmptyState';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { formatDateBr, route } from '@/utils';
import { Paginated } from '@/types';
import { Church } from 'lucide-react';

interface Song {
    id: number;
    name: string;
}

interface Assignment {
    id: number;
    worship_function_id: number;
    member_id: number;
    order: number;
    worship_function?: {
        id: number;
        name: string;
    };
    member?: {
        id: number;
        full_name: string;
    };
}

interface WorshipSet {
    id: number;
    date: string;
    formatted_date: string;
    period: 'manha' | 'noite';
    period_label: string;
    songs: Song[];
    assignments: Assignment[];
}

function formatScheduleSummary(assignments: Assignment[]): string {
    if (assignments.length === 0) {
        return 'Escala não definida';
    }

    const grouped = new Map<string, string[]>();

    assignments.forEach((assignment) => {
        const functionName = assignment.worship_function?.name ?? 'Função';
        const memberName = assignment.member?.full_name;

        if (!memberName) {
            return;
        }

        const current = grouped.get(functionName) ?? [];
        current.push(memberName);
        grouped.set(functionName, current);
    });

    return Array.from(grouped.entries())
        .map(([functionName, members]) => `${functionName}: ${members.join(', ')}`)
        .join(' · ');
}

interface IndexProps {
    worshipSets: Paginated<WorshipSet>;
    filters?: {
        search?: string;
        period?: string;
        date_from?: string;
        date_to?: string;
    };
}

const PERIOD_OPTIONS = [
    { value: '', label: 'Período' },
    { value: 'manha', label: 'Manhã' },
    { value: 'noite', label: 'Noite' },
];

function Index({ worshipSets, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const [period, setPeriod] = useState(filters.period ?? '');
    const [dateFrom, setDateFrom] = useState(filters.date_from ?? '');
    const [dateTo, setDateTo] = useState(filters.date_to ?? '');
    const empty = worshipSets.data.length === 0;

    const hasFilters = useMemo(() => Boolean(search || period || dateFrom || dateTo), [search, period, dateFrom, dateTo]);

    const handleSearch = () => {
        router.get(
            route('worship-sets.index'),
            {
                search: search || undefined,
                period: period || undefined,
                date_from: dateFrom || undefined,
                date_to: dateTo || undefined,
            },
            { preserveState: true, replace: true },
        );
    };

    const handleClear = () => {
        setSearch('');
        setPeriod('');
        setDateFrom('');
        setDateTo('');
        router.get(route('worship-sets.index'), {}, { preserveState: true, replace: true });
    };

    return (
        <AppPage>
            <PageCard
                title="Cultos"
                description="Repertório e escala de cada culto: data, período, equipe e músicas."
                actions={route('worship-sets.create')}
                actionsLabel="Novo culto"
            >
                <div className="mb-4">
                    <SearchForm placeholder="Buscar por membro da escala..." value={search} onChange={setSearch} onSubmit={handleSearch}>
                        <div className="w-full lg:w-36">
                            <Select
                                id="period"
                                value={period}
                                onChange={(event) => setPeriod(event.target.value)}
                                options={PERIOD_OPTIONS}
                                placeholder=""
                            />
                        </div>
                        <DateInput
                            id="date_from"
                            output="iso"
                            value={dateFrom}
                            onChange={(event) => setDateFrom(event.target.value)}
                            aria-label="Data inicial"
                        />
                        <DateInput
                            id="date_to"
                            output="iso"
                            value={dateTo}
                            onChange={(event) => setDateTo(event.target.value)}
                            aria-label="Data final"
                        />
                        {hasFilters && (
                            <Button type="button" variant="secondary" onClick={handleClear}>
                                Limpar
                            </Button>
                        )}
                    </SearchForm>
                </div>

                {empty ? (
                    <EmptyState
                        title={hasFilters ? 'Nenhum culto encontrado' : 'Nenhum culto montado'}
                        description={hasFilters ? 'Ajuste os filtros ou limpe a busca.' : 'Monte o repertório do próximo domingo.'}
                        actionLabel={hasFilters ? undefined : 'Novo culto'}
                        actionHref={hasFilters ? undefined : route('worship-sets.create')}
                        icon={<Church className="h-8 w-8" />}
                    />
                ) : (
                    <>
                        <DesktopOnly>
                            <TableShell>
                                <Table>
                                    <THead>
                                        <Th>Data</Th>
                                        <Th>Período</Th>
                                        <Th>Escala</Th>
                                        <Th>Músicas</Th>
                                        <ActionsTh />
                                    </THead>
                                    <TBody>
                                        {worshipSets.data.map((worshipSet) => (
                                            <Tr key={worshipSet.id}>
                                                <Td className="font-medium tabular">
                                                    {worshipSet.formatted_date ?? formatDateBr(worshipSet.date)}
                                                </Td>
                                                <Td>
                                                    <Badge tone={worshipSet.period === 'manha' ? 'warning' : 'info'}>
                                                        {worshipSet.period_label ?? (worshipSet.period === 'manha' ? 'Manhã' : 'Noite')}
                                                    </Badge>
                                                </Td>
                                                <Td>
                                                    <p className="max-w-xs text-sm text-muted-foreground">
                                                        {formatScheduleSummary(worshipSet.assignments ?? [])}
                                                    </p>
                                                </Td>
                                                <Td>
                                                    <div className="flex flex-wrap gap-1">
                                                        {worshipSet.songs.slice(0, 3).map((song) => (
                                                            <Badge key={song.id}>{song.name}</Badge>
                                                        ))}
                                                        {worshipSet.songs.length > 3 && <Badge>+{worshipSet.songs.length - 3}</Badge>}
                                                    </div>
                                                </Td>
                                                <Td align="right">
                                                    <RowActionsMenu
                                                        viewHref={route('worship-sets.show', worshipSet.id)}
                                                        editHref={route('worship-sets.edit', worshipSet.id)}
                                                        cloneHref={route('worship-sets.clone', worshipSet.id)}
                                                    />
                                                </Td>
                                            </Tr>
                                        ))}
                                    </TBody>
                                </Table>
                            </TableShell>
                        </DesktopOnly>

                        <MobileList>
                            {worshipSets.data.map((worshipSet) => (
                                <MobileCard key={worshipSet.id}>
                                    <MobileCardHeader
                                        actions={
                                            <RowActionsMenu
                                                viewHref={route('worship-sets.show', worshipSet.id)}
                                                editHref={route('worship-sets.edit', worshipSet.id)}
                                                cloneHref={route('worship-sets.clone', worshipSet.id)}
                                            />
                                        }
                                    >
                                        <p className="font-semibold">{worshipSet.formatted_date ?? formatDateBr(worshipSet.date)}</p>
                                        <p className="text-sm text-muted-foreground">{worshipSet.period_label}</p>
                                    </MobileCardHeader>
                                    <p className="mt-2 text-sm text-muted-foreground">
                                        {formatScheduleSummary(worshipSet.assignments ?? [])}
                                    </p>
                                    <p className="mt-1 text-sm text-muted-foreground">
                                        {worshipSet.songs.length} {worshipSet.songs.length === 1 ? 'música' : 'músicas'}
                                    </p>
                                </MobileCard>
                            ))}
                        </MobileList>

                        <Pagination paginator={worshipSets} />
                    </>
                )}
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
