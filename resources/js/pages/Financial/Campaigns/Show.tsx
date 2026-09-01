import { Link } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton, SecondaryButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { Can } from '@/components/layout/Can';
import { PageCard } from '@/components/ui/PageCard';
import { formatCurrency, formatDateBr, route } from '@/utils';
import { CampaignStatusBadge, TransactionTypeBadge } from '../components/StatusBadge';
import { Campaign } from '../types';

interface ShowProps {
    campaign: Campaign;
}

function Show({ campaign }: ShowProps) {
    const progress = campaign.progress ?? 0;
    const progressPercentage = campaign.progress_percentage ?? 0;
    const remaining = Number(campaign.goal_amount) - progress;

    return (
        <AppPage>
            <PageCard title="Detalhes da Campanha">
                <div className="flex flex-col lg:flex-row gap-6">
                    <div className="lg:w-1/3">
                        <div className="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informações Gerais</h3>
                            <div className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-500 dark:text-gray-400">Nome</label>
                                    <p className="mt-1 text-sm text-gray-900 dark:text-gray-100">{campaign.name}</p>
                                </div>
                                {campaign.description && (
                                    <div>
                                        <label className="block text-sm font-medium text-gray-500 dark:text-gray-400">Descrição</label>
                                        <p className="mt-1 text-sm text-gray-900 dark:text-gray-100">{campaign.description}</p>
                                    </div>
                                )}
                                <div>
                                    <label className="block text-sm font-medium text-gray-500 dark:text-gray-400">Meta</label>
                                    <p className="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{formatCurrency(campaign.goal_amount)}</p>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                                    <div className="mt-1">
                                        <CampaignStatusBadge status={campaign.status} />
                                    </div>
                                </div>
                                {(campaign.start_date || campaign.end_date) && (
                                    <div>
                                        <label className="block text-sm font-medium text-gray-500 dark:text-gray-400">Período</label>
                                        <p className="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            {campaign.start_date && campaign.end_date
                                                ? `${formatDateBr(campaign.start_date)} - ${formatDateBr(campaign.end_date)}`
                                                : campaign.start_date
                                                  ? `A partir de ${formatDateBr(campaign.start_date)}`
                                                  : `Até ${formatDateBr(campaign.end_date)}`}
                                        </p>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    <div className="lg:w-2/3">
                        <div className="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6">
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Progresso Financeiro</h3>
                            <div className="space-y-4">
                                <div className="flex justify-between items-center">
                                    <span className="text-sm font-medium text-gray-500 dark:text-gray-400">Arrecadado</span>
                                    <span className="text-lg font-semibold text-gray-900 dark:text-gray-100">{formatCurrency(progress)}</span>
                                </div>
                                <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                                    <div className="bg-blue-600 h-4 rounded-full transition-all duration-300" style={{ width: `${progressPercentage}%` }} />
                                </div>
                                <div className="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                                    <span>{progressPercentage.toFixed(1)}% da meta</span>
                                    <span>Faltam {formatCurrency(Math.max(0, remaining))}</span>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                            <div className="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-gray-100">Transações Relacionadas</h3>
                            </div>
                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead className="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subcategoria</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valor</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        {(campaign.transactions ?? []).length === 0 ? (
                                            <tr>
                                                <td colSpan={5} className="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                    Nenhuma transação encontrada para esta campanha.
                                                </td>
                                            </tr>
                                        ) : (
                                            campaign.transactions?.map((transaction) => (
                                                <tr key={transaction.id} className="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                        {formatDateBr(transaction.action_date)}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                        {transaction.subcategory?.name ?? '-'}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap">
                                                        <TransactionTypeBadge type={transaction.type} />
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                        {formatCurrency(transaction.amount)}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm">
                                                        <Link href={route('financial.transactions.show', transaction.id)} className="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                                            Ver
                                                        </Link>
                                                    </td>
                                                </tr>
                                            ))
                                        )}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                    <div className="flex gap-4">
                        <Link href={route('financial.campaigns.index')}>
                            <SecondaryButton type="button">Voltar</SecondaryButton>
                        </Link>
                        <Can permission="gerenciar_campanhas">
                            <LinkButton href={route('financial.campaigns.edit', campaign.id)}>Editar</LinkButton>
                            <DeleteButton href={route('financial.campaigns.destroy', campaign.id)} />
                        </Can>
                    </div>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
