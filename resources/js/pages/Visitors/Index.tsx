import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Badge } from '@/components/ui/Badge';
import { EditButton, RowActions, ViewButton } from '@/components/ui/Button';
import { ActionsTh, DesktopOnly, MobileCard, MobileCardHeader, MobileList, Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { EmptyState } from '@/components/ui/EmptyState';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { formatDateBr, route } from '@/utils';
import { Paginated } from '@/types';
import { UserPlus } from 'lucide-react';

interface Visitor {
    id: number;
    name: string;
    mobile: string;
    age_group: string | null;
    wants_contact: boolean;
    visit_date: string | null;
}

interface IndexProps {
    visitors: Paginated<Visitor>;
    filters?: {
        search?: string;
    };
}

function formatAgeGroup(value: string | null): string {
    if (!value) {
        return '-';
    }

    if (value === 'crianca_adolescente') {
        return 'Criança / Adolescente';
    }

    return value.charAt(0).toUpperCase() + value.slice(1);
}

function Index({ visitors, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const empty = visitors.data.length === 0;

    const handleSearch = () => {
        router.get(route('visitors.index'), { search: search || undefined }, { preserveState: true, replace: true });
    };

    return (
        <AppPage>
            <PageCard
                title="Visitantes"
                description="Quem passou na igreja e pode precisar de um contato."
                actions={route('visitors.create')}
                actionsLabel="Registrar visitante"
            >
                <div className="mb-4">
                    <SearchForm placeholder="Nome ou telefone" value={search} onChange={setSearch} onSubmit={handleSearch} />
                </div>

                {empty ? (
                    <EmptyState
                        title={search ? 'Nenhum visitante encontrado' : 'Nenhum visitante registrado'}
                        description={search ? 'Tente outro nome.' : 'Depois do culto, registre quem visitou.'}
                        actionLabel={search ? undefined : 'Registrar visitante'}
                        actionHref={search ? undefined : route('visitors.create')}
                        icon={<UserPlus className="h-8 w-8" />}
                    />
                ) : (
                    <>
                        <DesktopOnly>
                            <TableShell>
                                <Table>
                                    <THead>
                                        <Th>Nome</Th>
                                        <Th>Celular</Th>
                                        <Th>Faixa etária</Th>
                                        <Th>Visita</Th>
                                        <Th>Contato</Th>
                                        <ActionsTh />
                                    </THead>
                                    <TBody>
                                        {visitors.data.map((visitor) => (
                                            <Tr key={visitor.id}>
                                                <Td className="font-medium">{visitor.name}</Td>
                                                <Td className="tabular">{visitor.mobile}</Td>
                                                <Td>{visitor.age_group ? <Badge>{formatAgeGroup(visitor.age_group)}</Badge> : '—'}</Td>
                                                <Td className="tabular">{formatDateBr(visitor.visit_date)}</Td>
                                                <Td>
                                                    <Badge tone={visitor.wants_contact ? 'success' : 'neutral'}>
                                                        {visitor.wants_contact ? 'Quer contato' : 'Não'}
                                                    </Badge>
                                                </Td>
                                                <Td align="right">
                                                    <RowActions>
                                                        <ViewButton href={route('visitors.show', visitor.id)} />
                                                        <EditButton href={route('visitors.edit', visitor.id)} />
                                                    </RowActions>
                                                </Td>
                                            </Tr>
                                        ))}
                                    </TBody>
                                </Table>
                            </TableShell>
                        </DesktopOnly>

                        <MobileList>
                            {visitors.data.map((visitor) => (
                                <MobileCard key={visitor.id}>
                                    <MobileCardHeader
                                        actions={
                                            <RowActions>
                                                <ViewButton href={route('visitors.show', visitor.id)} />
                                                <EditButton href={route('visitors.edit', visitor.id)} />
                                            </RowActions>
                                        }
                                    >
                                        <p className="font-semibold">{visitor.name}</p>
                                        <p className="text-sm text-muted-foreground">{visitor.mobile}</p>
                                        <p className="mt-1 text-sm text-muted-foreground">{formatDateBr(visitor.visit_date)}</p>
                                        {visitor.wants_contact && (
                                            <Badge tone="success" className="mt-2">
                                                Contato
                                            </Badge>
                                        )}
                                    </MobileCardHeader>
                                </MobileCard>
                            ))}
                        </MobileList>

                        <Pagination paginator={visitors} />
                    </>
                )}
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
