import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { BackButton, EditButton, ViewButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { Can } from '@/components/layout/Can';
import { DetailActions, DetailField, DetailGrid, DetailSection, ProgressBar } from '@/components/ui/Detail';
import { EmptyState } from '@/components/ui/EmptyState';
import { PageCard } from '@/components/ui/PageCard';
import { Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { formatCurrency, formatDateBr, route } from '@/utils';
import { CampaignStatusBadge, TransactionTypeBadge } from '../components/StatusBadge';
import { Campaign } from '../types';
import { Wallet } from 'lucide-react';

interface ShowProps {
    campaign: Campaign;
}

function Show({ campaign }: ShowProps) {
    const progress = campaign.progress ?? 0;
    const progressPercentage = campaign.progress_percentage ?? 0;
    const remaining = Number(campaign.goal_amount) - progress;
    const transactions = campaign.transactions ?? [];

    return (
        <AppPage>
            <PageCard
                title={campaign.name}
                breadcrumbs={[{ label: 'Campanhas', href: route('financial.campaigns.index') }, { label: campaign.name }]}
                action={
                    <Can permission="gerenciar_campanhas">
                        <EditButton href={route('financial.campaigns.edit', campaign.id)} size="md" />
                    </Can>
                }
            >
                <div className="space-y-6">
                    <DetailSection title="Campanha">
                        <DetailGrid columns={3}>
                            <DetailField label="Status">
                                <CampaignStatusBadge status={campaign.status} />
                            </DetailField>
                            <DetailField label="Meta">{formatCurrency(campaign.goal_amount)}</DetailField>
                            {(campaign.start_date || campaign.end_date) && (
                                <DetailField label="Período">
                                    {campaign.start_date && campaign.end_date
                                        ? `${formatDateBr(campaign.start_date)} – ${formatDateBr(campaign.end_date)}`
                                        : campaign.start_date
                                          ? `A partir de ${formatDateBr(campaign.start_date)}`
                                          : `Até ${formatDateBr(campaign.end_date)}`}
                                </DetailField>
                            )}
                        </DetailGrid>
                        {campaign.description && <p className="mt-4 text-sm text-muted-foreground">{campaign.description}</p>}
                    </DetailSection>

                    <DetailSection title="Progresso">
                        <div className="flex items-baseline justify-between gap-3">
                            <p className="tabular text-lg font-semibold">{formatCurrency(progress)}</p>
                            <p className="text-sm text-muted-foreground">Faltam {formatCurrency(Math.max(0, remaining))}</p>
                        </div>
                        <ProgressBar value={progressPercentage} className="mt-3" />
                        <p className="mt-2 text-sm text-muted-foreground">{progressPercentage.toFixed(1)}% da meta</p>
                    </DetailSection>

                    <DetailSection title="Transações">
                        {transactions.length === 0 ? (
                            <EmptyState
                                title="Nenhuma entrada nesta campanha"
                                description="Lance uma transação de entrada vinculada a esta meta."
                                icon={<Wallet className="h-8 w-8" />}
                            />
                        ) : (
                            <TableShell>
                                <Table>
                                    <THead>
                                        <Th>Data</Th>
                                        <Th>Subcategoria</Th>
                                        <Th>Tipo</Th>
                                        <Th>Valor</Th>
                                        <Th align="right">
                                            <span className="sr-only">Ações</span>
                                        </Th>
                                    </THead>
                                    <TBody>
                                        {transactions.map((transaction) => (
                                            <Tr key={transaction.id}>
                                                <Td className="tabular">{formatDateBr(transaction.action_date)}</Td>
                                                <Td>{transaction.subcategory?.name ?? '—'}</Td>
                                                <Td>
                                                    <TransactionTypeBadge type={transaction.type} />
                                                </Td>
                                                <Td className={`tabular ${transaction.type === 'entrada' ? 'text-entrada' : 'text-saida'}`}>
                                                    {formatCurrency(transaction.amount)}
                                                </Td>
                                                <Td align="right">
                                                    <ViewButton href={route('financial.transactions.show', transaction.id)} />
                                                </Td>
                                            </Tr>
                                        ))}
                                    </TBody>
                                </Table>
                            </TableShell>
                        )}
                    </DetailSection>

                    <DetailActions>
                        <BackButton href={route('financial.campaigns.index')}>Voltar à lista</BackButton>
                        <Can permission="gerenciar_campanhas">
                            <DeleteButton href={route('financial.campaigns.destroy', campaign.id)} />
                        </Can>
                    </DetailActions>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
