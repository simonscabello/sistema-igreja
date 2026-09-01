import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { route } from '@/utils';
import { Paginated } from '@/types';

interface Permission {
    id: number;
    name: string;
}

interface Role {
    id: number;
    name: string;
    display_name: string | null;
    permissions: Permission[];
    users: Array<{ id: number }>;
}

interface IndexProps {
    roles: Paginated<Role>;
    filters?: {
        search?: string;
    };
}

function Index({ roles, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');

    const handleSearch = () => {
        router.get(
            route('roles.index'),
            { search: search || undefined },
            { preserveState: true, replace: true },
        );
    };

    return (
        <AppPage>
            <PageCard title="Papéis" description="Conjuntos de permissões atribuídos aos usuários." actions={route('roles.create')} actionsLabel="Novo papel">
                <div className="mb-4">
                    <SearchForm
                        action={route('roles.index')}
                        placeholder="Buscar roles..."
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
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Permissões</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Usuários</th>
                                <th className="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                            {roles.data.length === 0 ? (
                                <tr>
                                    <td colSpan={4} className="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                        Nenhum role encontrado.
                                    </td>
                                </tr>
                            ) : (
                                roles.data.map((role) => (
                                    <tr key={role.id} className="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-base font-semibold text-neutral-dark dark:text-gray-300">
                                                {role.display_name ?? role.name}
                                            </div>
                                            <div className="text-xs text-neutral-medium dark:text-gray-400">{role.name}</div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                            {role.permissions?.length ?? 0}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                            {role.users?.length ?? 0}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div className="flex justify-end gap-2">
                                                <LinkButton href={route('roles.show', role.id)} className="text-xs px-3 py-1">
                                                    Ver
                                                </LinkButton>
                                                <LinkButton href={route('roles.edit', role.id)} className="text-xs px-3 py-1">
                                                    Editar
                                                </LinkButton>
                                                <DeleteButton href={route('roles.destroy', role.id)} title="Excluir role?">
                                                    Excluir
                                                </DeleteButton>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>

                <Pagination paginator={roles} />
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
