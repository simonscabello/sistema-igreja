import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton } from '@/components/ui/Button';
import { Can } from '@/components/layout/Can';
import { PageCard, Pagination } from '@/components/ui/PageCard';
import { formatCurrency, formatDateBr, route } from '@/utils';
import { AttachmentIcon, CampaignLink, TransactionFilters } from '../components/TransactionFields';
import { TransactionTypeBadge } from '../components/StatusBadge';
import { Campaign, CategoryWithSubcategories, PaginatedTransactions } from '../types';

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

                <div className="hidden lg:block overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table className="min-w-full divide-y divide-neutral-medium dark:divide-gray-700">
                        <thead className="bg-neutral-light dark:bg-gray-700">
                            <tr>
                                <th className="px-4 xl:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Data</th>
                                <th className="px-4 xl:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Subcategoria</th>
                                <th className="px-4 xl:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Campanha</th>
                                <th className="px-4 xl:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                <th className="px-4 xl:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Valor</th>
                                <th className="px-4 xl:px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                            {transactions.data.length === 0 ? (
                                <tr>
                                    <td colSpan={6} className="px-4 xl:px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                        Nenhuma transação encontrada.
                                    </td>
                                </tr>
                            ) : (
                                transactions.data.map((transaction) => (
                                    <tr key={transaction.id} className="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td className="px-4 xl:px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                            <div className="flex items-center space-x-2">
                                                <span>{formatDateBr(transaction.action_date)}</span>
                                                <AttachmentIcon hasAttachment={hasAttachment(transaction)} />
                                            </div>
                                        </td>
                                        <td className="px-4 xl:px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                            {transaction.subcategory?.name ?? '-'}
                                        </td>
                                        <td className="px-4 xl:px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                            {transaction.campaign ? (
                                                <CampaignLink id={transaction.campaign.id} name={transaction.campaign.name} />
                                            ) : (
                                                <span className="text-gray-400">-</span>
                                            )}
                                        </td>
                                        <td className="px-4 xl:px-6 py-4 whitespace-nowrap">
                                            <TransactionTypeBadge type={transaction.type} />
                                        </td>
                                        <td className="px-4 xl:px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                            {formatCurrency(transaction.amount)}
                                        </td>
                                        <td className="px-4 xl:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div className="flex justify-end gap-2">
                                                <LinkButton href={route('financial.transactions.show', transaction.id)} className="text-xs px-3 py-1">
                                                    Ver
                                                </LinkButton>
                                                <Can permission="editar_transacoes">
                                                    <LinkButton href={route('financial.transactions.edit', transaction.id)} className="text-xs px-3 py-1">
                                                        Editar
                                                    </LinkButton>
                                                </Can>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>

                <div className="lg:hidden space-y-3">
                    {transactions.data.length === 0 ? (
                        <div className="text-center py-8 text-neutral-medium dark:text-gray-500">Nenhuma transação encontrada.</div>
                    ) : (
                        transactions.data.map((transaction) => (
                            <div key={transaction.id} className="bg-white dark:bg-gray-700 border border-neutral-medium dark:border-gray-600 rounded-lg p-4 space-y-3">
                                <div className="flex items-center justify-between">
                                    <div className="text-base font-semibold text-neutral-dark dark:text-gray-300 flex items-center space-x-2">
                                        <span>{formatDateBr(transaction.action_date)}</span>
                                        <AttachmentIcon hasAttachment={hasAttachment(transaction)} />
                                    </div>
                                    <TransactionTypeBadge type={transaction.type} />
                                </div>
                                <div className="grid grid-cols-1 gap-2 text-sm">
                                    <div className="flex justify-between">
                                        <span className="text-gray-500 dark:text-gray-400">Valor:</span>
                                        <span className={`font-semibold ${transaction.type === 'entrada' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}`}>
                                            {formatCurrency(transaction.amount)}
                                        </span>
                                    </div>
                                    <div className="flex justify-between">
                                        <span className="text-gray-500 dark:text-gray-400">Subcategoria:</span>
                                        <span className="text-neutral-dark dark:text-gray-300">{transaction.subcategory?.name ?? '-'}</span>
                                    </div>
                                    {transaction.campaign && (
                                        <div className="flex justify-between">
                                            <span className="text-gray-500 dark:text-gray-400">Campanha:</span>
                                            <CampaignLink id={transaction.campaign.id} name={transaction.campaign.name} />
                                        </div>
                                    )}
                                </div>
                                <div className="flex justify-end gap-2 pt-2 border-t border-neutral-medium dark:border-gray-600">
                                    <LinkButton href={route('financial.transactions.show', transaction.id)} className="text-xs px-3 py-2 flex-1 text-center">
                                        Ver
                                    </LinkButton>
                                    <Can permission="editar_transacoes">
                                        <LinkButton href={route('financial.transactions.edit', transaction.id)} className="text-xs px-3 py-2 flex-1 text-center">
                                            Editar
                                        </LinkButton>
                                    </Can>
                                </div>
                            </div>
                        ))
                    )}
                </div>

                <Pagination paginator={transactions} />
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
