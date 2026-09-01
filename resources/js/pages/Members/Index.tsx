import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Avatar } from '@/components/ui/Avatar';
import { EditButton, RowActions, ViewButton } from '@/components/ui/Button';
import { ActionsTh, DesktopOnly, MobileCard, MobileCardHeader, MobileList, Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { EmptyState } from '@/components/ui/EmptyState';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { formatDateBr, route } from '@/utils';
import { Paginated } from '@/types';
import { Users } from 'lucide-react';

interface Member {
    id: number;
    full_name: string;
    email: string | null;
    mobile: string;
    birth_date: string | null;
    foto_url?: string | null;
}

interface IndexProps {
    members: Paginated<Member>;
    filters?: {
        search?: string;
    };
}

function Index({ members, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const empty = members.data.length === 0;

    const handleSearch = () => {
        router.get(route('members.index'), { search: search || undefined }, { preserveState: true, replace: true });
    };

    return (
        <AppPage>
            <PageCard
                title="Membros"
                description="Cadastro pastoral das pessoas da igreja."
                actions={route('members.create')}
                actionsLabel="Novo membro"
            >
                <div className="mb-4">
                    <SearchForm placeholder="Nome, e-mail ou telefone" value={search} onChange={setSearch} onSubmit={handleSearch} />
                </div>

                {empty ? (
                    <EmptyState
                        title={search ? 'Nenhum membro encontrado' : 'Nenhum membro cadastrado'}
                        description={search ? 'Tente outro nome ou limpe a busca.' : 'Comece pelo cadastro de quem já faz parte da igreja.'}
                        actionLabel={search ? undefined : 'Novo membro'}
                        actionHref={search ? undefined : route('members.create')}
                        icon={<Users className="h-8 w-8" />}
                    />
                ) : (
                    <>
                        <DesktopOnly>
                            <TableShell>
                                <Table>
                                    <THead>
                                        <Th>Nome</Th>
                                        <Th>Telefone</Th>
                                        <Th>Nascimento</Th>
                                        <ActionsTh />
                                    </THead>
                                    <TBody>
                                        {members.data.map((member) => (
                                            <Tr key={member.id}>
                                                <Td>
                                                    <div className="flex items-center gap-3">
                                                        <Avatar name={member.full_name} imageUrl={member.foto_url} />
                                                        <div className="min-w-0">
                                                            <div className="font-medium">{member.full_name}</div>
                                                            {member.email && (
                                                                <div className="truncate text-sm text-muted-foreground">{member.email}</div>
                                                            )}
                                                        </div>
                                                    </div>
                                                </Td>
                                                <Td className="tabular">{member.mobile}</Td>
                                                <Td className="tabular">{formatDateBr(member.birth_date)}</Td>
                                                <Td align="right">
                                                    <RowActions>
                                                        <ViewButton href={route('members.show', member.id)} />
                                                        <EditButton href={route('members.edit', member.id)} />
                                                    </RowActions>
                                                </Td>
                                            </Tr>
                                        ))}
                                    </TBody>
                                </Table>
                            </TableShell>
                        </DesktopOnly>

                        <MobileList>
                            {members.data.map((member) => (
                                <MobileCard key={member.id}>
                                    <MobileCardHeader
                                        actions={
                                            <RowActions>
                                                <ViewButton href={route('members.show', member.id)} />
                                                <EditButton href={route('members.edit', member.id)} />
                                            </RowActions>
                                        }
                                    >
                                        <div className="flex items-center gap-3">
                                            <Avatar name={member.full_name} imageUrl={member.foto_url} />
                                            <div className="min-w-0">
                                                <div className="truncate font-semibold">{member.full_name}</div>
                                                <div className="text-sm text-muted-foreground">{member.mobile}</div>
                                            </div>
                                        </div>
                                    </MobileCardHeader>
                                </MobileCard>
                            ))}
                        </MobileList>

                        <Pagination paginator={members} />
                    </>
                )}
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
