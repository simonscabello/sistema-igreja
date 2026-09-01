import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { EditButton, RowActions, ViewButton } from '@/components/ui/Button';
import { ProgressBar } from '@/components/ui/Detail';
import { ActionsTh, DesktopOnly, MobileCard, MobileCardHeader, MobileList, Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { EmptyState } from '@/components/ui/EmptyState';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { Select } from '@/components/ui/Input';
import { formatCurrency, route } from '@/utils';
import { CampaignStatusBadge } from '../components/StatusBadge';
import { PaginatedCampaigns } from '../types';
import { Target } from 'lucide-react';

interface IndexProps {
    campaigns: PaginatedCampaigns;
    filters?: {
        search?: string;
        status?: string;
    };
}

function Index({ campaigns, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const [status, setStatus] = useState(filters.status ?? '');
    const empty = campaigns.data.length === 0;

    const handleSearch = () => {
        router.get(
            route('financial.campaigns.index'),
            {
                search: search || undefined,
                status: status || undefined,
            },
            { preserveState: true, replace: true },
        );
    };

    return (
        <AppPage>
            <PageCard
                title="Campanhas"
                description="Metas de arrecadação. Só entradas entram no progresso."
                actions={route('financial.campaigns.create')}
                actionsLabel="Nova campanha"
            >
                <div className="mb-4">
                    <SearchForm placeholder="Buscar campanhas..." value={search} onChange={setSearch} onSubmit={handleSearch}>
                        <div className="w-full sm:w-44">
                            <Select
                                id="status"
                                value={status}
                                onChange={(event) => setStatus(event.target.value)}
                                options={[
                                    { value: 'ativo', label: 'Ativo' },
                                    { value: 'encerrado', label: 'Encerrado' },
                                    { value: 'cancelada', label: 'Cancelada' },
                                ]}
                                placeholder="Todos os status"
                            />
                        </div>
                    </SearchForm>
                </div>

                {empty ? (
                    <EmptyState
                        title={search || status ? 'Nenhuma campanha encontrada' : 'Nenhuma campanha cadastrada'}
                        description={search || status ? 'Tente outro nome ou status.' : 'Crie uma meta de arrecadação.'}
                        actionLabel={search || status ? undefined : 'Nova campanha'}
                        actionHref={search || status ? undefined : route('financial.campaigns.create')}
                        icon={<Target className="h-8 w-8" />}
                    />
                ) : (
                    <>
                        <DesktopOnly>
                            <TableShell>
                                <Table>
                                    <THead>
                                        <Th>Nome</Th>
                                        <Th>Meta</Th>
                                        <Th>Progresso</Th>
                                        <Th>Status</Th>
                                        <ActionsTh />
                                    </THead>
                                    <TBody>
                                        {campaigns.data.map((campaign) => (
                                            <Tr key={campaign.id}>
                                                <Td>
                                                    <div className="max-w-xs truncate font-medium" title={campaign.name}>
                                                        {campaign.name}
                                                    </div>
                                                    {campaign.description && (
                                                        <div
                                                            className="max-w-xs truncate text-sm text-muted-foreground"
                                                            title={campaign.description}
                                                        >
                                                            {campaign.description}
                                                        </div>
                                                    )}
                                                </Td>
                                                <Td className="tabular">{formatCurrency(campaign.goal_amount)}</Td>
                                                <Td>
                                                    <div className="flex items-center gap-3">
                                                        <ProgressBar value={campaign.progress_percentage ?? 0} className="w-16" />
                                                        <span className="tabular text-sm">{formatCurrency(campaign.progress ?? 0)}</span>
                                                    </div>
                                                </Td>
                                                <Td>
                                                    <CampaignStatusBadge status={campaign.status} />
                                                </Td>
                                                <Td align="right">
                                                    <RowActions>
                                                        <ViewButton href={route('financial.campaigns.show', campaign.id)} />
                                                        <EditButton href={route('financial.campaigns.edit', campaign.id)} />
                                                    </RowActions>
                                                </Td>
                                            </Tr>
                                        ))}
                                    </TBody>
                                </Table>
                            </TableShell>
                        </DesktopOnly>

                        <MobileList>
                            {campaigns.data.map((campaign) => (
                                <MobileCard key={campaign.id}>
                                    <MobileCardHeader
                                        actions={
                                            <RowActions>
                                                <ViewButton href={route('financial.campaigns.show', campaign.id)} />
                                                <EditButton href={route('financial.campaigns.edit', campaign.id)} />
                                            </RowActions>
                                        }
                                    >
                                        <div className="flex flex-wrap items-center gap-2">
                                            <p className="font-semibold">{campaign.name}</p>
                                            <CampaignStatusBadge status={campaign.status} />
                                        </div>
                                        <p className="mt-1 text-sm text-muted-foreground">
                                            {formatCurrency(campaign.progress ?? 0)} de {formatCurrency(campaign.goal_amount)}
                                        </p>
                                    </MobileCardHeader>
                                    <ProgressBar value={campaign.progress_percentage ?? 0} className="mt-3" />
                                </MobileCard>
                            ))}
                        </MobileList>

                        <Pagination paginator={campaigns} />
                    </>
                )}
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
