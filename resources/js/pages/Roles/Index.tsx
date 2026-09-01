import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { RowActionsMenu } from '@/components/ui/RowActionsMenu';
import { ActionsTh, DesktopOnly, MobileCard, MobileCardHeader, MobileList, Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { EmptyState } from '@/components/ui/EmptyState';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { route } from '@/utils';
import { Paginated } from '@/types';
import { Shield } from 'lucide-react';

interface Permission {
    id: number;
    name: string;
}

interface Role {
    id: number;
    name: string;
    display_name: string | null;
    permissions: Permission[];
    users: Array<{ id: number }>;
}

interface IndexProps {
    roles: Paginated<Role>;
    filters?: {
        search?: string;
    };
}

function Index({ roles, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const empty = roles.data.length === 0;

    const handleSearch = () => {
        router.get(route('roles.index'), { search: search || undefined }, { preserveState: true, replace: true });
    };

    return (
        <AppPage>
            <PageCard
                title="Papéis"
                description="Conjuntos de permissões atribuídos aos usuários."
                actions={route('roles.create')}
                actionsLabel="Novo papel"
            >
                <div className="mb-4">
                    <SearchForm placeholder="Buscar papéis..." value={search} onChange={setSearch} onSubmit={handleSearch} />
                </div>

                {empty ? (
                    <EmptyState
                        title={search ? 'Nenhum papel encontrado' : 'Nenhum papel cadastrado'}
                        description={search ? 'Tente outro nome.' : 'Crie um papel para agrupar permissões.'}
                        actionLabel={search ? undefined : 'Novo papel'}
                        actionHref={search ? undefined : route('roles.create')}
                        icon={<Shield className="h-8 w-8" />}
                    />
                ) : (
                    <>
                        <DesktopOnly>
                            <TableShell>
                                <Table>
                                    <THead>
                                        <Th>Nome</Th>
                                        <Th>Permissões</Th>
                                        <Th>Usuários</Th>
                                        <ActionsTh />
                                    </THead>
                                    <TBody>
                                        {roles.data.map((role) => (
                                            <Tr key={role.id}>
                                                <Td>
                                                    <div className="font-medium">{role.display_name ?? role.name}</div>
                                                    <div className="text-sm text-muted-foreground">{role.name}</div>
                                                </Td>
                                                <Td className="tabular">{role.permissions?.length ?? 0}</Td>
                                                <Td className="tabular">{role.users?.length ?? 0}</Td>
                                                <Td align="right">
                                                    <RowActionsMenu
                                                        viewHref={route('roles.show', role.id)}
                                                        editHref={route('roles.edit', role.id)}
                                                        deleteHref={route('roles.destroy', role.id)}
                                                        deleteTitle="Excluir papel?"
                                                    />
                                                </Td>
                                            </Tr>
                                        ))}
                                    </TBody>
                                </Table>
                            </TableShell>
                        </DesktopOnly>

                        <MobileList>
                            {roles.data.map((role) => (
                                <MobileCard key={role.id}>
                                    <MobileCardHeader
                                        actions={
                                            <RowActionsMenu
                                                viewHref={route('roles.show', role.id)}
                                                editHref={route('roles.edit', role.id)}
                                                deleteHref={route('roles.destroy', role.id)}
                                                deleteTitle="Excluir papel?"
                                            />
                                        }
                                    >
                                        <p className="font-semibold">{role.display_name ?? role.name}</p>
                                        <p className="text-sm text-muted-foreground">
                                            {role.permissions?.length ?? 0} permissões · {role.users?.length ?? 0} usuários
                                        </p>
                                    </MobileCardHeader>
                                </MobileCard>
                            ))}
                        </MobileList>

                        <Pagination paginator={roles} />
                    </>
                )}
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
