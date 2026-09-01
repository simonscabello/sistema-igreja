import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Badge } from '@/components/ui/Badge';
import { EditButton, RowActions, ViewButton } from '@/components/ui/Button';
import { ActionsTh, DesktopOnly, MobileCard, MobileCardHeader, MobileList, Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { EmptyState } from '@/components/ui/EmptyState';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { Select } from '@/components/ui/Input';
import { route } from '@/utils';
import { Paginated } from '@/types';
import { Building2 } from 'lucide-react';

interface Department {
    id: number;
    title: string;
    description: string | null;
    is_active: boolean;
    members_count: number;
}

interface IndexProps {
    departments: Paginated<Department>;
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

function Index({ departments, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const [status, setStatus] = useState(filters.status ?? '');
    const empty = departments.data.length === 0;

    const handleSearch = () => {
        router.get(
            route('departments.index'),
            { search: search || undefined, status: status || undefined },
            { preserveState: true, replace: true },
        );
    };

    return (
        <AppPage>
            <PageCard
                title="Departamentos"
                description="Ministérios, com líderes e membros em papéis distintos."
                actions={route('departments.create')}
                actionsLabel="Novo departamento"
            >
                <div className="mb-4">
                    <SearchForm placeholder="Buscar departamentos..." value={search} onChange={setSearch} onSubmit={handleSearch}>
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
                        title={search || status ? 'Nenhum departamento encontrado' : 'Nenhum departamento cadastrado'}
                        description={search || status ? 'Tente outro nome ou status.' : 'Cadastre os ministérios da igreja.'}
                        actionLabel={search || status ? undefined : 'Novo departamento'}
                        actionHref={search || status ? undefined : route('departments.create')}
                        icon={<Building2 className="h-8 w-8" />}
                    />
                ) : (
                    <>
                        <DesktopOnly>
                            <TableShell>
                                <Table>
                                    <THead>
                                        <Th>Título</Th>
                                        <Th>Descrição</Th>
                                        <Th>Membros</Th>
                                        <Th>Status</Th>
                                        <ActionsTh />
                                    </THead>
                                    <TBody>
                                        {departments.data.map((department) => (
                                            <Tr key={department.id}>
                                                <Td className="font-medium">{department.title}</Td>
                                                <Td className="max-w-xs truncate text-muted-foreground">{department.description ?? '—'}</Td>
                                                <Td className="tabular">{department.members_count}</Td>
                                                <Td>
                                                    <Badge tone={department.is_active ? 'success' : 'neutral'}>
                                                        {department.is_active ? 'Ativo' : 'Inativo'}
                                                    </Badge>
                                                </Td>
                                                <Td align="right">
                                                    <RowActions>
                                                        <ViewButton href={route('departments.show', department.id)} />
                                                        <EditButton href={route('departments.edit', department.id)} />
                                                    </RowActions>
                                                </Td>
                                            </Tr>
                                        ))}
                                    </TBody>
                                </Table>
                            </TableShell>
                        </DesktopOnly>

                        <MobileList>
                            {departments.data.map((department) => (
                                <MobileCard key={department.id}>
                                    <MobileCardHeader
                                        actions={
                                            <RowActions>
                                                <ViewButton href={route('departments.show', department.id)} />
                                                <EditButton href={route('departments.edit', department.id)} />
                                            </RowActions>
                                        }
                                    >
                                        <div className="flex flex-wrap items-center gap-2">
                                            <p className="font-semibold">{department.title}</p>
                                            <Badge tone={department.is_active ? 'success' : 'neutral'}>
                                                {department.is_active ? 'Ativo' : 'Inativo'}
                                            </Badge>
                                        </div>
                                        <p className="mt-1 text-sm text-muted-foreground">{department.members_count} membros</p>
                                    </MobileCardHeader>
                                </MobileCard>
                            ))}
                        </MobileList>

                        <Pagination paginator={departments} />
                    </>
                )}
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
