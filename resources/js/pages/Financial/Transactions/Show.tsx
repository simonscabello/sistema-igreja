import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { BackButton, EditButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { Can } from '@/components/layout/Can';
import { DetailActions, DetailField, DetailGrid, DetailSection, ExternalLink } from '@/components/ui/Detail';
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
            <PageCard
                title="Transação"
                breadcrumbs={[
                    { label: 'Transações', href: route('financial.transactions.index') },
                    { label: formatDateBr(financialTransaction.action_date) },
                ]}
                action={
                    <Can permission="editar_transacoes">
                        <EditButton href={route('financial.transactions.edit', financialTransaction.id)} size="md" />
                    </Can>
                }
            >
                <div className="space-y-6">
                    <DetailSection title="Lançamento">
                        <DetailGrid columns={3}>
                            <DetailField label="Data">{formatDateBr(financialTransaction.action_date)}</DetailField>
                            <DetailField label="Tipo">
                                <TransactionTypeBadge type={financialTransaction.type} />
                            </DetailField>
                            <DetailField label="Valor">
                                <span
                                    className={`tabular text-lg font-semibold ${
                                        financialTransaction.type === 'entrada' ? 'text-entrada' : 'text-saida'
                                    }`}
                                >
                                    {formatCurrency(financialTransaction.amount)}
                                </span>
                            </DetailField>
                            <DetailField label="Categoria">{financialTransaction.subcategory?.financial_category?.name ?? '—'}</DetailField>
                            <DetailField label="Subcategoria">{financialTransaction.subcategory?.name ?? '—'}</DetailField>
                            <DetailField label="Campanha">
                                {financialTransaction.campaign ? (
                                    <CampaignLink id={financialTransaction.campaign.id} name={financialTransaction.campaign.name} />
                                ) : (
                                    '—'
                                )}
                            </DetailField>
                        </DetailGrid>
                    </DetailSection>

                    {financialTransaction.description && (
                        <DetailSection title="Descrição">
                            <p className="whitespace-pre-line text-sm">{financialTransaction.description}</p>
                        </DetailSection>
                    )}

                    {attachments.length > 0 && (
                        <DetailSection title="Anexos">
                            <ul className="space-y-2">
                                {attachments.map((attachment) => (
                                    <li
                                        key={attachment.id}
                                        className="flex flex-wrap items-center justify-between gap-3 rounded-lg border px-4 py-3"
                                    >
                                        <div>
                                            <p className="text-sm font-medium">{attachment.original_name}</p>
                                            <p className="text-sm text-muted-foreground">
                                                {(attachment.size / 1024).toFixed(1)} KB · {attachment.extension.toUpperCase()}
                                            </p>
                                        </div>
                                        {attachment.url && (
                                            <div className="flex gap-3">
                                                <ExternalLink href={attachment.url}>Abrir</ExternalLink>
                                                <a
                                                    href={attachment.url}
                                                    download={attachment.original_name}
                                                    className="text-sm font-medium hover:underline"
                                                >
                                                    Baixar
                                                </a>
                                            </div>
                                        )}
                                    </li>
                                ))}
                            </ul>
                        </DetailSection>
                    )}

                    <DetailActions>
                        <BackButton href={route('financial.transactions.index')}>Voltar à lista</BackButton>
                        <Can permission="excluir_transacoes">
                            <DeleteButton
                                href={route('financial.transactions.destroy', financialTransaction.id)}
                                title="Excluir transação?"
                            />
                        </Can>
                    </DetailActions>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
