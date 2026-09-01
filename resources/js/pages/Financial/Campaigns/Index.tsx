import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton } from '@/components/ui/Button';
import { PageCard, Pagination } from '@/components/ui/PageCard';
import { Select } from '@/components/ui/Input';
import { formatCurrency, route } from '@/utils';
import { CampaignStatusBadge } from '../components/StatusBadge';
import { PaginatedCampaigns } from '../types';

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
                <form
                    onSubmit={(event) => {
                        event.preventDefault();
                        handleSearch();
                    }}
                    className="flex flex-col sm:flex-row gap-2 mb-4"
                >
                    <input
                        type="search"
                        value={search}
                        onChange={(event) => setSearch(event.target.value)}
                        placeholder="Buscar campanhas..."
                        className="flex-1 border-neutral-medium dark:border-gray-600 rounded-md shadow-sm focus:border-primary focus:ring-primary bg-white dark:bg-gray-700 text-neutral-dark dark:text-white"
                    />
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
                    <button type="submit" className="inline-flex items-center justify-center px-4 py-3 text-sm bg-primary text-white rounded-md hover:bg-primary-dark">
                        Buscar
                    </button>
                </form>

                <div className="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table className="min-w-full divide-y divide-neutral-medium dark:divide-gray-700">
                        <thead className="bg-neutral-light dark:bg-gray-700">
                            <tr>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Nome</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Meta</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Progresso</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th className="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                            {campaigns.data.length === 0 ? (
                                <tr>
                                    <td colSpan={5} className="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                        Nenhuma campanha encontrada.
                                    </td>
                                </tr>
                            ) : (
                                campaigns.data.map((campaign) => (
                                    <tr key={campaign.id} className="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td className="px-6 py-4">
                                            <div className="max-w-xs truncate text-sm font-medium text-neutral-dark dark:text-gray-100" title={campaign.name}>
                                                {campaign.name}
                                            </div>
                                            {campaign.description && (
                                                <div className="max-w-xs truncate text-sm text-neutral-medium dark:text-gray-400" title={campaign.description}>
                                                    {campaign.description}
                                                </div>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 text-sm text-neutral-dark whitespace-nowrap dark:text-gray-100">
                                            {formatCurrency(campaign.goal_amount)}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="flex items-center">
                                                <div className="mr-3 h-2 w-16 rounded-full bg-gray-200 dark:bg-gray-700">
                                                    <div
                                                        className="h-2 rounded-full bg-blue-600"
                                                        style={{ width: `${campaign.progress_percentage ?? 0}%` }}
                                                    />
                                                </div>
                                                <span className="text-sm text-neutral-dark dark:text-gray-100">
                                                    {formatCurrency(campaign.progress ?? 0)}
                                                </span>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <CampaignStatusBadge status={campaign.status} />
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div className="flex justify-end gap-2">
                                                <LinkButton href={route('financial.campaigns.show', campaign.id)} className="text-xs px-3 py-1">
                                                    Ver
                                                </LinkButton>
                                                <LinkButton href={route('financial.campaigns.edit', campaign.id)} className="text-xs px-3 py-1">
                                                    Editar
                                                </LinkButton>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>

                <Pagination paginator={campaigns} />
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
