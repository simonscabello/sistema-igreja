import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton } from '@/components/ui/Button';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { Select } from '@/components/ui/Input';
import { route } from '@/utils';
import { Paginated } from '@/types';

interface Department {
    id: number;
    title: string;
    description: string | null;
    is_active: boolean;
    members_count: number;
}

interface IndexProps {
    departments: Paginated<Department>;
    filters?: {
        search?: string;
        status?: string;
    };
}

const STATUS_OPTIONS = [
    { value: '', label: 'Todos os status' },
    { value: 'active', label: 'Ativos' },
    { value: 'inactive', label: 'Inativos' },
];

function StatusBadge({ isActive }: { isActive: boolean }) {
    if (isActive) {
        return (
            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                Ativo
            </span>
        );
    }

    return (
        <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
            Inativo
        </span>
    );
}

function Index({ departments, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const [status, setStatus] = useState(filters.status ?? '');

    const handleSearch = () => {
        router.get(
            route('departments.index'),
            { search: search || undefined, status: status || undefined },
            { preserveState: true, replace: true },
        );
    };

    return (
        <AppPage>
            <PageCard
                title="Departamentos"
                description="Ministérios, com líderes e membros em papéis distintos."
                actions={route('departments.create')}
                actionsLabel="Novo departamento"
            >
                <div className="flex flex-col sm:flex-row sm:justify-between sm:items-end mb-4 gap-3">
                    <SearchForm
                        action={route('departments.index')}
                        placeholder="Buscar departamentos..."
                        value={search}
                        onChange={setSearch}
                        onSubmit={handleSearch}
                    />
                    <div className="w-full sm:w-48">
                        <Select
                            id="status"
                            value={status}
                            onChange={(event) => setStatus(event.target.value)}
                            options={STATUS_OPTIONS}
                            placeholder=""
                        />
                    </div>
                    <button
                        type="button"
                        onClick={handleSearch}
                        className="inline-flex items-center justify-center px-4 py-3 text-sm bg-primary text-white rounded-md hover:bg-primary-dark"
                    >
                        Buscar
                    </button>
                </div>

                <div className="hidden lg:block overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table className="min-w-full divide-y divide-neutral-medium dark:divide-gray-700">
                        <thead className="bg-neutral-light dark:bg-gray-700">
                            <tr>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Título</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Descrição</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Membros</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th className="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                            {departments.data.length === 0 ? (
                                <tr>
                                    <td colSpan={5} className="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                        Nenhum departamento encontrado.
                                    </td>
                                </tr>
                            ) : (
                                departments.data.map((department) => (
                                    <tr key={department.id} className="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td className="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300 font-medium">{department.title}</td>
                                        <td className="px-6 py-4 text-neutral-dark dark:text-gray-300">
                                            {department.description ? (
                                                department.description.length > 50 ? `${department.description.slice(0, 50)}...` : department.description
                                            ) : (
                                                <span className="text-neutral-medium dark:text-gray-500">-</span>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {department.members_count} membros
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <StatusBadge isActive={department.is_active} />
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div className="flex justify-end gap-2">
                                                <LinkButton href={route('departments.show', department.id)} className="text-xs px-3 py-1">
                                                    Ver
                                                </LinkButton>
                                                <LinkButton href={route('departments.edit', department.id)} className="text-xs px-3 py-1">
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

                <div className="space-y-3 lg:hidden">
                    {departments.data.length === 0 ? (
                        <p className="py-8 text-center text-sm text-ink-muted">Nenhum departamento encontrado.</p>
                    ) : (
                        departments.data.map((department) => (
                            <div key={department.id} className="rounded-xl border border-line bg-surface p-4 dark:border-line-dark dark:bg-surface-dark">
                                <p className="font-semibold">{department.title}</p>
                                <p className="mt-1 text-sm text-ink-muted">{department.members_count} membros</p>
                                <div className="mt-3 flex gap-3">
                                    <LinkButton href={route('departments.show', department.id)}>Ver</LinkButton>
                                    <LinkButton href={route('departments.edit', department.id)}>Editar</LinkButton>
                                </div>
                            </div>
                        ))
                    )}
                </div>

                <Pagination paginator={departments} />
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
