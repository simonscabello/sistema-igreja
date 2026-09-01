import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { EditButton, RowActions, ViewButton } from '@/components/ui/Button';
import { Can } from '@/components/layout/Can';
import { ActionsTh, DesktopOnly, MobileCard, MobileCardHeader, MobileList, Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { EmptyState } from '@/components/ui/EmptyState';
import { PageCard, Pagination } from '@/components/ui/PageCard';
import { formatCurrency, formatDateBr, route } from '@/utils';
import { AttachmentIcon, CampaignLink, TransactionFilters } from '../components/TransactionFields';
import { TransactionTypeBadge } from '../components/StatusBadge';
import { Campaign, CategoryWithSubcategories, PaginatedTransactions } from '../types';
import { Wallet } from 'lucide-react';

interface IndexProps {
    transactions: PaginatedTransactions;
    categories: CategoryWithSubcategories[];
    campaigns: Campaign[];
    filters?: {
        search?: string;
        type?: string;
        subcategory?: string;
        campaign?: string;
    };
}

function hasAttachment(transaction: PaginatedTransactions['data'][number]): boolean {
    return (transaction.files?.length ?? 0) > 0;
}

function Index({ transactions, categories, campaigns, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const [type, setType] = useState(filters.type ?? '');
    const [subcategory, setSubcategory] = useState(filters.subcategory ?? '');
    const [campaign, setCampaign] = useState(filters.campaign ?? '');
    const empty = transactions.data.length === 0;
    const hasFilters = Boolean(search || type || subcategory || campaign);

    const handleSearch = () => {
        router.get(
            route('financial.transactions.index'),
            {
                search: search || undefined,
                type: type || undefined,
                subcategory: subcategory || undefined,
                campaign: campaign || undefined,
            },
            { preserveState: true, replace: true },
        );
    };

    return (
        <AppPage>
            <PageCard
                title="Transações"
                description="Lançamentos de entrada e saída. Cada um pertence a uma subcategoria."
                actions={route('financial.transactions.create')}
                actionsLabel="Nova transação"
            >
                <TransactionFilters
                    categories={categories}
                    campaigns={campaigns}
                    search={search}
                    type={type}
                    subcategory={subcategory}
                    campaign={campaign}
                    onSearchChange={setSearch}
                    onTypeChange={setType}
                    onSubcategoryChange={setSubcategory}
                    onCampaignChange={setCampaign}
                    onSubmit={handleSearch}
                />

                {empty ? (
                    <EmptyState
                        title={hasFilters ? 'Nenhuma transação encontrada' : 'Nenhuma transação lançada'}
                        description={hasFilters ? 'Ajuste os filtros ou limpe a busca.' : 'Registre a primeira entrada ou saída.'}
                        actionLabel={hasFilters ? undefined : 'Nova transação'}
                        actionHref={hasFilters ? undefined : route('financial.transactions.create')}
                        icon={<Wallet className="h-8 w-8" />}
                    />
                ) : (
                    <>
                        <DesktopOnly>
                            <TableShell>
                                <Table>
                                    <THead>
                                        <Th>Data</Th>
                                        <Th>Subcategoria</Th>
                                        <Th>Campanha</Th>
                                        <Th>Tipo</Th>
                                        <Th>Valor</Th>
                                        <ActionsTh />
                                    </THead>
                                    <TBody>
                                        {transactions.data.map((transaction) => (
                                            <Tr key={transaction.id}>
                                                <Td className="tabular">
                                                    <span className="inline-flex items-center gap-2">
                                                        {formatDateBr(transaction.action_date)}
                                                        <AttachmentIcon hasAttachment={hasAttachment(transaction)} />
                                                    </span>
                                                </Td>
                                                <Td>{transaction.subcategory?.name ?? '—'}</Td>
                                                <Td>
                                                    {transaction.campaign ? (
                                                        <CampaignLink id={transaction.campaign.id} name={transaction.campaign.name} />
                                                    ) : (
                                                        <span className="text-muted-foreground">—</span>
                                                    )}
                                                </Td>
                                                <Td>
                                                    <TransactionTypeBadge type={transaction.type} />
                                                </Td>
                                                <Td
                                                    className={`tabular font-medium ${
                                                        transaction.type === 'entrada' ? 'text-entrada' : 'text-saida'
                                                    }`}
                                                >
                                                    {formatCurrency(transaction.amount)}
                                                </Td>
                                                <Td align="right">
                                                    <RowActions>
                                                        <ViewButton href={route('financial.transactions.show', transaction.id)} />
                                                        <Can permission="editar_transacoes">
                                                            <EditButton href={route('financial.transactions.edit', transaction.id)} />
                                                        </Can>
                                                    </RowActions>
                                                </Td>
                                            </Tr>
                                        ))}
                                    </TBody>
                                </Table>
                            </TableShell>
                        </DesktopOnly>

                        <MobileList>
                            {transactions.data.map((transaction) => (
                                <MobileCard key={transaction.id}>
                                    <MobileCardHeader
                                        actions={
                                            <RowActions>
                                                <ViewButton href={route('financial.transactions.show', transaction.id)} />
                                                <Can permission="editar_transacoes">
                                                    <EditButton href={route('financial.transactions.edit', transaction.id)} />
                                                </Can>
                                            </RowActions>
                                        }
                                    >
                                        <p className="inline-flex items-center gap-2 font-semibold">
                                            {formatDateBr(transaction.action_date)}
                                            <AttachmentIcon hasAttachment={hasAttachment(transaction)} />
                                        </p>
                                        <p className="mt-1 text-sm text-muted-foreground">{transaction.subcategory?.name ?? '—'}</p>
                                        <TransactionTypeBadge type={transaction.type} />
                                    </MobileCardHeader>
                                    <p
                                        className={`mt-2 tabular font-semibold ${
                                            transaction.type === 'entrada' ? 'text-entrada' : 'text-saida'
                                        }`}
                                    >
                                        {formatCurrency(transaction.amount)}
                                    </p>
                                    {transaction.campaign && (
                                        <p className="mt-1 text-sm text-muted-foreground">{transaction.campaign.name}</p>
                                    )}
                                </MobileCard>
                            ))}
                        </MobileList>

                        <Pagination paginator={transactions} />
                    </>
                )}
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
