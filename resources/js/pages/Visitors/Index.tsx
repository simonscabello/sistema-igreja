import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Badge } from '@/components/ui/Badge';
import { LinkButton } from '@/components/ui/Button';
import {
    DesktopOnly,
    MobileCard,
    MobileList,
    Table,
    TableShell,
    TBody,
    Td,
    Th,
    THead,
    Tr,
} from '@/components/ui/DataTable';
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
                                        <Th align="right"> </Th>
                                    </THead>
                                    <TBody>
                                        {visitors.data.map((visitor) => (
                                            <Tr key={visitor.id}>
                                                <Td className="font-medium">{visitor.name}</Td>
                                                <Td className="tabular">{visitor.mobile}</Td>
                                                <Td>
                                                    {visitor.age_group ? (
                                                        <Badge>{formatAgeGroup(visitor.age_group)}</Badge>
                                                    ) : (
                                                        '—'
                                                    )}
                                                </Td>
                                                <Td className="tabular">{formatDateBr(visitor.visit_date)}</Td>
                                                <Td>
                                                    <Badge tone={visitor.wants_contact ? 'success' : 'neutral'}>
                                                        {visitor.wants_contact ? 'Quer contato' : 'Não'}
                                                    </Badge>
                                                </Td>
                                                <Td align="right">
                                                    <div className="flex justify-end gap-3">
                                                        <LinkButton href={route('visitors.show', visitor.id)}>Ver</LinkButton>
                                                        <LinkButton href={route('visitors.edit', visitor.id)}>Editar</LinkButton>
                                                    </div>
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
                                    <div className="flex items-start justify-between gap-2">
                                        <div>
                                            <p className="font-semibold">{visitor.name}</p>
                                            <p className="text-sm text-ink-muted">{visitor.mobile}</p>
                                        </div>
                                        {visitor.wants_contact && <Badge tone="success">Contato</Badge>}
                                    </div>
                                    <p className="mt-2 text-sm text-ink-muted">{formatDateBr(visitor.visit_date)}</p>
                                    <div className="mt-3 flex justify-end gap-4 text-sm">
                                        <LinkButton href={route('visitors.show', visitor.id)}>Ver</LinkButton>
                                        <LinkButton href={route('visitors.edit', visitor.id)}>Editar</LinkButton>
                                    </div>
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
