import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { RowActionsMenu } from '@/components/ui/RowActionsMenu';
import { ActionsTh, DesktopOnly, MobileCard, MobileCardHeader, MobileList, Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { EmptyState } from '@/components/ui/EmptyState';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { useCan } from '@/hooks/useCan';
import { route } from '@/utils';
import { ActiveStatusBadge } from '../components/StatusBadge';
import { PaginatedSubcategories } from '../types';
import { Tag } from 'lucide-react';

interface IndexProps {
    subcategories: PaginatedSubcategories;
    filters?: {
        search?: string;
    };
}

function Index({ subcategories, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const empty = subcategories.data.length === 0;
    const canManage = useCan('gerenciar_categorias_financeiras');

    const handleSearch = () => {
        router.get(route('financial.subcategories.index'), { search: search || undefined }, { preserveState: true, replace: true });
    };

    return (
        <AppPage>
            <PageCard
                title="Subcategorias"
                description="Contas usadas nas transações. Não dá para apagar se já tiverem lançamentos."
                actions={route('financial.subcategories.create')}
                actionsLabel="Nova subcategoria"
            >
                <div className="mb-4">
                    <SearchForm placeholder="Buscar subcategorias..." value={search} onChange={setSearch} onSubmit={handleSearch} />
                </div>

                {empty ? (
                    <EmptyState
                        title={search ? 'Nenhuma subcategoria encontrada' : 'Nenhuma subcategoria cadastrada'}
                        description={search ? 'Tente outro nome.' : 'Crie as contas usadas nos lançamentos.'}
                        actionLabel={search ? undefined : 'Nova subcategoria'}
                        actionHref={search ? undefined : route('financial.subcategories.create')}
                        icon={<Tag className="h-8 w-8" />}
                    />
                ) : (
                    <>
                        <DesktopOnly>
                            <TableShell>
                                <Table>
                                    <THead>
                                        <Th>Nome</Th>
                                        <Th>Categoria</Th>
                                        <Th>Status</Th>
                                        <ActionsTh />
                                    </THead>
                                    <TBody>
                                        {subcategories.data.map((subcategory) => (
                                            <Tr key={subcategory.id}>
                                                <Td className="font-medium">{subcategory.name}</Td>
                                                <Td>{subcategory.financial_category?.name ?? '—'}</Td>
                                                <Td>
                                                    <ActiveStatusBadge active={subcategory.active} />
                                                </Td>
                                                <Td align="right">
                                                    <RowActionsMenu
                                                        editHref={route('financial.subcategories.edit', subcategory.id)}
                                                        deleteHref={
                                                            canManage ? route('financial.subcategories.destroy', subcategory.id) : undefined
                                                        }
                                                    />
                                                </Td>
                                            </Tr>
                                        ))}
                                    </TBody>
                                </Table>
                            </TableShell>
                        </DesktopOnly>

                        <MobileList>
                            {subcategories.data.map((subcategory) => (
                                <MobileCard key={subcategory.id}>
                                    <MobileCardHeader
                                        actions={
                                            <RowActionsMenu
                                                editHref={route('financial.subcategories.edit', subcategory.id)}
                                                deleteHref={
                                                    canManage ? route('financial.subcategories.destroy', subcategory.id) : undefined
                                                }
                                            />
                                        }
                                    >
                                        <div className="flex flex-wrap items-center gap-2">
                                            <p className="font-semibold">{subcategory.name}</p>
                                            <ActiveStatusBadge active={subcategory.active} />
                                        </div>
                                        <p className="mt-1 text-sm text-muted-foreground">
                                            {subcategory.financial_category?.name ?? '—'}
                                        </p>
                                    </MobileCardHeader>
                                </MobileCard>
                            ))}
                        </MobileList>

                        <Pagination paginator={subcategories} />
                    </>
                )}
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
