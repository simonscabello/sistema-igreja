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
import { PaginatedCategories } from '../types';
import { Tags } from 'lucide-react';

interface IndexProps {
    categories: PaginatedCategories;
    filters?: {
        search?: string;
    };
}

function Index({ categories, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const empty = categories.data.length === 0;
    const canManage = useCan('gerenciar_categorias_financeiras');

    const handleSearch = () => {
        router.get(route('financial.categories.index'), { search: search || undefined }, { preserveState: true, replace: true });
    };

    return (
        <AppPage>
            <PageCard
                title="Categorias"
                description="Grupos das contas. Transações entram nas subcategorias."
                actions={route('financial.categories.create')}
                actionsLabel="Nova categoria"
            >
                <div className="mb-4">
                    <SearchForm placeholder="Buscar categorias..." value={search} onChange={setSearch} onSubmit={handleSearch} />
                </div>

                {empty ? (
                    <EmptyState
                        title={search ? 'Nenhuma categoria encontrada' : 'Nenhuma categoria cadastrada'}
                        description={search ? 'Tente outro nome.' : 'Crie os grupos das contas da tesouraria.'}
                        actionLabel={search ? undefined : 'Nova categoria'}
                        actionHref={search ? undefined : route('financial.categories.create')}
                        icon={<Tags className="h-8 w-8" />}
                    />
                ) : (
                    <>
                        <DesktopOnly>
                            <TableShell>
                                <Table>
                                    <THead>
                                        <Th>Nome</Th>
                                        <Th>Descrição</Th>
                                        <Th>Status</Th>
                                        <ActionsTh />
                                    </THead>
                                    <TBody>
                                        {categories.data.map((category) => (
                                            <Tr key={category.id}>
                                                <Td className="font-medium">{category.name}</Td>
                                                <Td>{category.description ?? '—'}</Td>
                                                <Td>
                                                    <ActiveStatusBadge active={category.active} />
                                                </Td>
                                                <Td align="right">
                                                    <RowActionsMenu
                                                        editHref={route('financial.categories.edit', category.id)}
                                                        deleteHref={
                                                            canManage ? route('financial.categories.destroy', category.id) : undefined
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
                            {categories.data.map((category) => (
                                <MobileCard key={category.id}>
                                    <MobileCardHeader
                                        actions={
                                            <RowActionsMenu
                                                editHref={route('financial.categories.edit', category.id)}
                                                deleteHref={canManage ? route('financial.categories.destroy', category.id) : undefined}
                                            />
                                        }
                                    >
                                        <div className="flex flex-wrap items-center gap-2">
                                            <p className="font-semibold">{category.name}</p>
                                            <ActiveStatusBadge active={category.active} />
                                        </div>
                                        {category.description && (
                                            <p className="mt-1 text-sm text-muted-foreground">{category.description}</p>
                                        )}
                                    </MobileCardHeader>
                                </MobileCard>
                            ))}
                        </MobileList>

                        <Pagination paginator={categories} />
                    </>
                )}
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
