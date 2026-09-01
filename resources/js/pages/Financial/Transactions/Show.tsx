import { Link } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton, SecondaryButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { Can } from '@/components/layout/Can';
import { PageCard } from '@/components/ui/PageCard';
import { formatCurrency, formatDateBr, route } from '@/utils';
import { CampaignLink } from '../components/TransactionFields';
import { TransactionTypeBadge } from '../components/StatusBadge';
import { FinancialTransaction } from '../types';

interface ShowProps {
    financialTransaction: FinancialTransaction;
}

function Show({ financialTransaction }: ShowProps) {
    const attachments = financialTransaction.files ?? [];

    return (
        <AppPage>
            <PageCard title="Detalhes da Transação Financeira">
                <div className="space-y-6">
                    <div className="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
                            <div>
                                <h3 className="text-lg font-medium text-gray-900 dark:text-gray-300 mb-6">Informações da Transação</h3>
                                <dl className="space-y-4">
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">Data da Transação</dt>
                                        <dd className="mt-1 text-sm text-gray-900 dark:text-gray-300">{formatDateBr(financialTransaction.action_date)}</dd>
                                    </div>
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</dt>
                                        <dd className="mt-1">
                                            <TransactionTypeBadge type={financialTransaction.type} />
                                        </dd>
                                    </div>
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">Valor</dt>
                                        <dd
                                            className={`mt-1 text-2xl font-bold ${
                                                financialTransaction.type === 'entrada'
                                                    ? 'text-green-600 dark:text-green-400'
                                                    : 'text-red-600 dark:text-red-400'
                                            }`}
                                        >
                                            {formatCurrency(financialTransaction.amount)}
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <div>
                                <h3 className="text-lg font-medium text-gray-900 dark:text-gray-300 mb-6">Categorização</h3>
                                <dl className="space-y-4">
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">Categoria</dt>
                                        <dd className="mt-1 text-sm text-gray-900 dark:text-gray-300">
                                            {financialTransaction.subcategory?.financial_category?.name ?? '-'}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">Subcategoria</dt>
                                        <dd className="mt-1 text-sm text-gray-900 dark:text-gray-300">{financialTransaction.subcategory?.name ?? '-'}</dd>
                                    </div>
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">Campanha</dt>
                                        <dd className="mt-1 text-sm text-gray-900 dark:text-gray-300">
                                            {financialTransaction.campaign ? (
                                                <CampaignLink id={financialTransaction.campaign.id} name={financialTransaction.campaign.name} />
                                            ) : (
                                                <span className="text-gray-400 italic">Nenhuma campanha</span>
                                            )}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    {financialTransaction.description && (
                        <div className="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                            <h3 className="text-lg font-medium text-gray-900 dark:text-gray-300 mb-4">Descrição</h3>
                            <div className="text-sm text-gray-900 dark:text-gray-300 whitespace-pre-line bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                                {financialTransaction.description}
                            </div>
                        </div>
                    )}

                    {attachments.length > 0 && (
                        <div className="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                            <h3 className="text-lg font-medium text-gray-900 dark:text-gray-300 mb-4">Anexos</h3>
                            <div className="space-y-3">
                                {attachments.map((attachment) => (
                                    <div key={attachment.id} className="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <p className="text-sm font-medium text-gray-900 dark:text-gray-300">{attachment.original_name}</p>
                                            <p className="text-xs text-gray-500 dark:text-gray-400">
                                                {(attachment.size / 1024).toFixed(1)} KB • {attachment.extension.toUpperCase()}
                                            </p>
                                        </div>
                                        {attachment.url && (
                                            <div className="flex items-center space-x-2">
                                                <a
                                                    href={attachment.url}
                                                    target="_blank"
                                                    rel="noreferrer"
                                                    className="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-gray-50 bg-primary hover:bg-primary-dark"
                                                >
                                                    Visualizar
                                                </a>
                                                <a
                                                    href={attachment.url}
                                                    download={attachment.original_name}
                                                    className="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                                                >
                                                    Download
                                                </a>
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {(financialTransaction.created_at || financialTransaction.updated_at) && (
                        <div className="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 className="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Informações do Sistema</h4>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-500 dark:text-gray-400">
                                {financialTransaction.created_at && (
                                    <div>
                                        <span className="font-medium">Criado em:</span>{' '}
                                        {formatDateBr(financialTransaction.created_at.split('T')[0])}
                                    </div>
                                )}
                                {financialTransaction.updated_at && financialTransaction.updated_at !== financialTransaction.created_at && (
                                    <div>
                                        <span className="font-medium">Última atualização:</span>{' '}
                                        {formatDateBr(financialTransaction.updated_at.split('T')[0])}
                                    </div>
                                )}
                            </div>
                        </div>
                    )}

                    <div className="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <div className="flex flex-wrap gap-3">
                            <Link href={route('financial.transactions.index')}>
                                <SecondaryButton type="button">Voltar</SecondaryButton>
                            </Link>
                            <Can permission="editar_transacoes">
                                <LinkButton href={route('financial.transactions.edit', financialTransaction.id)}>Editar</LinkButton>
                            </Can>
                            <Can permission="excluir_transacoes">
                                <DeleteButton
                                    href={route('financial.transactions.destroy', financialTransaction.id)}
                                    title="Excluir transação?"
                                    text="Tem certeza que deseja excluir esta transação? Esta ação não pode ser desfeita."
                                />
                            </Can>
                        </div>
                    </div>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
