import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton } from '@/components/ui/Button';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { route } from '@/utils';
import { Paginated } from '@/types';

interface Role {
    id: number;
    name: string;
    display_name: string | null;
}

interface User {
    id: number;
    name: string;
    email: string;
    roles: Role[];
}

interface IndexProps {
    users: Paginated<User>;
    filters?: {
        search?: string;
    };
}

function Index({ users, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');

    const handleSearch = () => {
        router.get(
            route('users.index'),
            { search: search || undefined },
            { preserveState: true, replace: true },
        );
    };

    return (
        <AppPage>
            <PageCard
                title="Usuários"
                description="Quem acessa o sistema. Não são os membros da igreja."
                actions={route('users.create')}
                actionsLabel="Novo usuário"
            >
                <div className="mb-4">
                    <SearchForm
                        action={route('users.index')}
                        placeholder="Buscar usuários..."
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
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Roles</th>
                                <th className="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                            {users.data.length === 0 ? (
                                <tr>
                                    <td colSpan={4} className="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                        Nenhum usuário encontrado.
                                    </td>
                                </tr>
                            ) : (
                                users.data.map((user) => (
                                    <tr key={user.id} className="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-base font-semibold text-neutral-dark dark:text-gray-300">{user.name}</div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">{user.email}</td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            {user.roles.length > 0 ? (
                                                <div className="flex flex-wrap gap-1">
                                                    {user.roles.map((role) => (
                                                        <span key={role.id} className="inline-block bg-primary text-white text-xs px-2 py-1 rounded-full">
                                                            {role.display_name ?? role.name}
                                                        </span>
                                                    ))}
                                                </div>
                                            ) : (
                                                <span className="text-neutral-medium dark:text-gray-500">Nenhuma role</span>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div className="flex justify-end gap-2">
                                                <LinkButton href={route('users.show', user.id)} className="text-xs px-3 py-1">
                                                    Ver
                                                </LinkButton>
                                                <LinkButton href={route('users.edit', user.id)} className="text-xs px-3 py-1">
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

                <Pagination paginator={users} />
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
