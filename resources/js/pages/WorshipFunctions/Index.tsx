import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Badge } from '@/components/ui/Badge';
import { RowActionsMenu } from '@/components/ui/RowActionsMenu';
import { ActionsTh, DesktopOnly, MobileCard, MobileCardHeader, MobileList, Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { EmptyState } from '@/components/ui/EmptyState';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { Select } from '@/components/ui/Input';
import { route } from '@/utils';
import { WorshipFunctionIcon } from '@/utils/worshipFunctionIcon';
import { Paginated } from '@/types';
import { Users } from 'lucide-react';

interface WorshipFunction {
    id: number;
    name: string;
    slug: string;
    sort_order: number;
    is_active: boolean;
    members_count: number;
}

interface IndexProps {
    worshipFunctions: Paginated<WorshipFunction>;
    filters?: {
        search?: string;
        status?: string;
    };
}

const STATUS_OPTIONS = [
    { value: '', label: 'Todos os status' },
    { value: 'active', label: 'Ativos' },
    { value: 'inactive', label: 'Inativos' },
];

function Index({ worshipFunctions, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const [status, setStatus] = useState(filters.status ?? '');
    const empty = worshipFunctions.data.length === 0;

    const handleSearch = () => {
        router.get(
            route('worship-functions.index'),
            { search: search || undefined, status: status || undefined },
            { preserveState: true, replace: true },
        );
    };

    return (
        <AppPage>
            <PageCard
                title="Funções"
                description="Funções da equipe de louvor e quem pode exercê-las."
                actions={route('worship-functions.create')}
                actionsLabel="Nova função"
            >
                <div className="mb-4">
                    <SearchForm placeholder="Buscar funções..." value={search} onChange={setSearch} onSubmit={handleSearch}>
                        <div className="w-full sm:w-48">
                            <Select
                                id="status"
                                value={status}
                                onChange={(event) => setStatus(event.target.value)}
                                options={STATUS_OPTIONS}
                                placeholder=""
                            />
                        </div>
                    </SearchForm>
                </div>

                {empty ? (
                    <EmptyState
                        title={search || status ? 'Nenhuma função encontrada' : 'Nenhuma função cadastrada'}
                        description={search || status ? 'Tente outro nome ou status.' : 'Cadastre as funções da equipe de louvor.'}
                        actionLabel={search || status ? undefined : 'Nova função'}
                        actionHref={search || status ? undefined : route('worship-functions.create')}
                        icon={<Users className="h-8 w-8" />}
                    />
                ) : (
                    <>
                        <DesktopOnly>
                            <TableShell>
                                <Table>
                                    <THead>
                                        <Th>Nome</Th>
                                        <Th>Ordem</Th>
                                        <Th>Membros</Th>
                                        <Th>Status</Th>
                                        <ActionsTh />
                                    </THead>
                                    <TBody>
                                        {worshipFunctions.data.map((worshipFunction) => (
                                            <Tr key={worshipFunction.id}>
                                                <Td className="font-medium">
                                                    <span className="inline-flex items-center gap-2">
                                                        <span className="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-muted text-foreground">
                                                            <WorshipFunctionIcon slug={worshipFunction.slug} />
                                                        </span>
                                                        {worshipFunction.name}
                                                    </span>
                                                </Td>
                                                <Td className="tabular">{worshipFunction.sort_order}</Td>
                                                <Td className="tabular">{worshipFunction.members_count}</Td>
                                                <Td>
                                                    <Badge tone={worshipFunction.is_active ? 'success' : 'neutral'}>
                                                        {worshipFunction.is_active ? 'Ativo' : 'Inativo'}
                                                    </Badge>
                                                </Td>
                                                <Td align="right">
                                                    <RowActionsMenu
                                                        editHref={route('worship-functions.edit', worshipFunction.id)}
                                                        deleteHref={route('worship-functions.destroy', worshipFunction.id)}
                                                    />
                                                </Td>
                                            </Tr>
                                        ))}
                                    </TBody>
                                </Table>
                            </TableShell>
                        </DesktopOnly>

                        <MobileList>
                            {worshipFunctions.data.map((worshipFunction) => (
                                <MobileCard key={worshipFunction.id}>
                                    <MobileCardHeader
                                        actions={
                                            <RowActionsMenu
                                                editHref={route('worship-functions.edit', worshipFunction.id)}
                                                deleteHref={route('worship-functions.destroy', worshipFunction.id)}
                                            />
                                        }
                                    >
                                        <div className="flex min-w-0 items-start gap-2">
                                            <span className="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-muted text-foreground">
                                                <WorshipFunctionIcon slug={worshipFunction.slug} />
                                            </span>
                                            <div>
                                                <p className="font-semibold">{worshipFunction.name}</p>
                                                <p className="text-sm text-muted-foreground">{worshipFunction.members_count} membros</p>
                                                <Badge tone={worshipFunction.is_active ? 'success' : 'neutral'} className="mt-2">
                                                    {worshipFunction.is_active ? 'Ativo' : 'Inativo'}
                                                </Badge>
                                            </div>
                                        </div>
                                    </MobileCardHeader>
                                </MobileCard>
                            ))}
                        </MobileList>

                        <Pagination paginator={worshipFunctions} />
                    </>
                )}
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
