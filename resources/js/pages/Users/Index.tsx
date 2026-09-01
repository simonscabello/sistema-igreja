import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Badge } from '@/components/ui/Badge';
import { EditButton, RowActions, ViewButton } from '@/components/ui/Button';
import { ActionsTh, DesktopOnly, MobileCard, MobileCardHeader, MobileList, Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { EmptyState } from '@/components/ui/EmptyState';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { route } from '@/utils';
import { Paginated } from '@/types';
import { UserCog } from 'lucide-react';

interface Role {
    id: number;
    name: string;
    display_name: string | null;
}

interface User {
    id: number;
    name: string;
    email: string;
    roles: Role[];
}

interface IndexProps {
    users: Paginated<User>;
    filters?: {
        search?: string;
    };
}

function Index({ users, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const empty = users.data.length === 0;

    const handleSearch = () => {
        router.get(route('users.index'), { search: search || undefined }, { preserveState: true, replace: true });
    };

    return (
        <AppPage>
            <PageCard
                title="Usuários"
                description="Quem acessa o sistema. Não são os membros da igreja."
                actions={route('users.create')}
                actionsLabel="Novo usuário"
            >
                <div className="mb-4">
                    <SearchForm placeholder="Nome ou e-mail" value={search} onChange={setSearch} onSubmit={handleSearch} />
                </div>

                {empty ? (
                    <EmptyState
                        title={search ? 'Nenhum usuário encontrado' : 'Nenhum usuário cadastrado'}
                        description={search ? 'Tente outro nome ou e-mail.' : 'Crie a conta de quem precisa acessar o sistema.'}
                        actionLabel={search ? undefined : 'Novo usuário'}
                        actionHref={search ? undefined : route('users.create')}
                        icon={<UserCog className="h-8 w-8" />}
                    />
                ) : (
                    <>
                        <DesktopOnly>
                            <TableShell>
                                <Table>
                                    <THead>
                                        <Th>Nome</Th>
                                        <Th>E-mail</Th>
                                        <Th>Papéis</Th>
                                        <ActionsTh />
                                    </THead>
                                    <TBody>
                                        {users.data.map((user) => (
                                            <Tr key={user.id}>
                                                <Td className="font-medium">{user.name}</Td>
                                                <Td>{user.email}</Td>
                                                <Td>
                                                    {user.roles.length > 0 ? (
                                                        <div className="flex flex-wrap gap-1">
                                                            {user.roles.map((role) => (
                                                                <Badge key={role.id} tone="primary">
                                                                    {role.display_name ?? role.name}
                                                                </Badge>
                                                            ))}
                                                        </div>
                                                    ) : (
                                                        <span className="text-muted-foreground">Nenhum papel</span>
                                                    )}
                                                </Td>
                                                <Td align="right">
                                                    <RowActions>
                                                        <ViewButton href={route('users.show', user.id)} />
                                                        <EditButton href={route('users.edit', user.id)} />
                                                    </RowActions>
                                                </Td>
                                            </Tr>
                                        ))}
                                    </TBody>
                                </Table>
                            </TableShell>
                        </DesktopOnly>

                        <MobileList>
                            {users.data.map((user) => (
                                <MobileCard key={user.id}>
                                    <MobileCardHeader
                                        actions={
                                            <RowActions>
                                                <ViewButton href={route('users.show', user.id)} />
                                                <EditButton href={route('users.edit', user.id)} />
                                            </RowActions>
                                        }
                                    >
                                        <p className="font-semibold">{user.name}</p>
                                        <p className="text-sm text-muted-foreground">{user.email}</p>
                                    </MobileCardHeader>
                                </MobileCard>
                            ))}
                        </MobileList>

                        <Pagination paginator={users} />
                    </>
                )}
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
