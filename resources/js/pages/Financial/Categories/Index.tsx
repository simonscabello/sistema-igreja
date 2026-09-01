import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { Can } from '@/components/layout/Can';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { route } from '@/utils';
import { ActiveStatusBadge } from '../components/StatusBadge';
import { PaginatedCategories } from '../types';

interface IndexProps {
    categories: PaginatedCategories;
    filters?: {
        search?: string;
    };
}

function Index({ categories, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');

    const handleSearch = () => {
        router.get(
            route('financial.categories.index'),
            { search: search || undefined },
            { preserveState: true, replace: true },
        );
    };

    return (
        <AppPage>
            <PageCard
                title="Categorias"
                description="Grupos das contas. Transações entram nas subcategorias."
                actions={route('financial.categories.create')}
                actionsLabel="Nova categoria"
            >
                <div className="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-3">
                    <SearchForm
                        action={route('financial.categories.index')}
                        placeholder="Buscar categorias..."
                        value={search}
                        onChange={setSearch}
                        onSubmit={handleSearch}
                    />
                </div>

                <div className="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table className="min-w-full divide-y divide-neutral-medium dark:divide-gray-700">
                        <thead className="bg-neutral-light dark:bg-gray-700">
                            <tr>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Nome</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Descrição</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th className="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                            {categories.data.length === 0 ? (
                                <tr>
                                    <td colSpan={4} className="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                        Nenhuma categoria encontrada.
                                    </td>
                                </tr>
                            ) : (
                                categories.data.map((category) => (
                                    <tr key={category.id} className="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td className="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">{category.name}</td>
                                        <td className="px-6 py-4 text-neutral-dark dark:text-gray-300">{category.description ?? '-'}</td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <ActiveStatusBadge active={category.active} />
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div className="flex justify-end gap-2">
                                                <LinkButton href={route('financial.categories.edit', category.id)} className="text-xs px-3 py-1">
                                                    Editar
                                                </LinkButton>
                                                <Can permission="gerenciar_categorias_financeiras">
                                                    <DeleteButton href={route('financial.categories.destroy', category.id)} />
                                                </Can>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>

                <Pagination paginator={categories} />
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
