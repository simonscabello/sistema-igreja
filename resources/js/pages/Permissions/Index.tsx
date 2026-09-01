import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { EditButton, RowActions, ViewButton } from '@/components/ui/Button';
import { ActionsTh, DesktopOnly, MobileCard, MobileCardHeader, MobileList, Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { EmptyState } from '@/components/ui/EmptyState';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { formatDateBr, route } from '@/utils';
import { Paginated } from '@/types';
import { KeyRound } from 'lucide-react';

interface Role {
    id: number;
    name: string;
}

interface Permission {
    id: number;
    name: string;
    display_name: string | null;
    created_at: string;
    roles: Role[];
}

interface IndexProps {
    permissions: Paginated<Permission>;
    filters?: {
        search?: string;
    };
}

function Index({ permissions, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const empty = permissions.data.length === 0;

    const handleSearch = () => {
        router.get(route('permissions.index'), { search: search || undefined }, { preserveState: true, replace: true });
    };

    return (
        <AppPage>
            <PageCard
                title="Permissões"
                description="Capacidades individuais. Prefira atribuir papéis, não permissões soltas."
                actions={route('permissions.create')}
                actionsLabel="Nova permissão"
            >
                <div className="mb-4">
                    <SearchForm placeholder="Buscar permissões..." value={search} onChange={setSearch} onSubmit={handleSearch} />
                </div>

                {empty ? (
                    <EmptyState
                        title={search ? 'Nenhuma permissão encontrada' : 'Nenhuma permissão cadastrada'}
                        description={search ? 'Tente outro nome.' : 'Crie uma permissão para controlar o acesso.'}
                        actionLabel={search ? undefined : 'Nova permissão'}
                        actionHref={search ? undefined : route('permissions.create')}
                        icon={<KeyRound className="h-8 w-8" />}
                    />
                ) : (
                    <>
                        <DesktopOnly>
                            <TableShell>
                                <Table>
                                    <THead>
                                        <Th>Nome</Th>
                                        <Th>Papéis</Th>
                                        <Th>Criado em</Th>
                                        <ActionsTh />
                                    </THead>
                                    <TBody>
                                        {permissions.data.map((permission) => (
                                            <Tr key={permission.id}>
                                                <Td>
                                                    <div className="font-medium">{permission.display_name ?? permission.name}</div>
                                                    <div className="text-sm text-muted-foreground">{permission.name}</div>
                                                </Td>
                                                <Td className="tabular">{permission.roles?.length ?? 0}</Td>
                                                <Td className="tabular">{formatDateBr(permission.created_at)}</Td>
                                                <Td align="right">
                                                    <RowActions>
                                                        <ViewButton href={route('permissions.show', permission.id)} />
                                                        <EditButton href={route('permissions.edit', permission.id)} />
                                                    </RowActions>
                                                </Td>
                                            </Tr>
                                        ))}
                                    </TBody>
                                </Table>
                            </TableShell>
                        </DesktopOnly>

                        <MobileList>
                            {permissions.data.map((permission) => (
                                <MobileCard key={permission.id}>
                                    <MobileCardHeader
                                        actions={
                                            <RowActions>
                                                <ViewButton href={route('permissions.show', permission.id)} />
                                                <EditButton href={route('permissions.edit', permission.id)} />
                                            </RowActions>
                                        }
                                    >
                                        <p className="font-semibold">{permission.display_name ?? permission.name}</p>
                                        <p className="text-sm text-muted-foreground">{permission.roles?.length ?? 0} papéis</p>
                                    </MobileCardHeader>
                                </MobileCard>
                            ))}
                        </MobileList>

                        <Pagination paginator={permissions} />
                    </>
                )}
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
