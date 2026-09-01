import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton } from '@/components/ui/Button';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { formatDateBr, route } from '@/utils';
import { Paginated } from '@/types';

interface Role {
    id: number;
    name: string;
}

interface Permission {
    id: number;
    name: string;
    display_name: string | null;
    created_at: string;
    roles: Role[];
}

interface IndexProps {
    permissions: Paginated<Permission>;
    filters?: {
        search?: string;
    };
}

function Index({ permissions, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');

    const handleSearch = () => {
        router.get(
            route('permissions.index'),
            { search: search || undefined },
            { preserveState: true, replace: true },
        );
    };

    return (
        <AppPage>
            <PageCard title="Permissões" description="Capacidades individuais. Prefira atribuir papéis, não permissões soltas." actions={route('permissions.create')} actionsLabel="Nova permissão">
                <div className="mb-4">
                    <SearchForm
                        action={route('permissions.index')}
                        placeholder="Buscar permissões..."
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
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Roles</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Criado em</th>
                                <th className="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                            {permissions.data.length === 0 ? (
                                <tr>
                                    <td colSpan={4} className="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                        Nenhuma permissão encontrada.
                                    </td>
                                </tr>
                            ) : (
                                permissions.data.map((permission) => (
                                    <tr key={permission.id} className="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-base font-semibold text-neutral-dark dark:text-gray-300">
                                                {permission.display_name ?? permission.name}
                                            </div>
                                            <div className="text-xs text-neutral-medium dark:text-gray-400">{permission.name}</div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                            {permission.roles?.length ?? 0}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                            {formatDateBr(permission.created_at)}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div className="flex justify-end gap-2">
                                                <LinkButton href={route('permissions.show', permission.id)} className="text-xs px-3 py-1">
                                                    Ver
                                                </LinkButton>
                                                <LinkButton href={route('permissions.edit', permission.id)} className="text-xs px-3 py-1">
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

                <Pagination paginator={permissions} />
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
