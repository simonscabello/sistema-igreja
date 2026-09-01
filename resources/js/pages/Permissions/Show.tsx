import { Link } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton, SecondaryButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { PageCard } from '@/components/ui/PageCard';
import { formatDateBr, route } from '@/utils';

interface Role {
    id: number;
    name: string;
    display_name: string | null;
}

interface Permission {
    id: number;
    name: string;
    display_name: string | null;
    created_at: string;
    updated_at: string;
    roles?: Role[];
}

interface ShowProps {
    permission: Permission;
}

function Show({ permission }: ShowProps) {
    return (
        <AppPage>
            <PageCard title="Detalhes da Permissão">
                <div className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-neutral-dark dark:text-gray-300">Nome</label>
                            <p className="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">
                                {permission.name}
                            </p>
                        </div>
                        {permission.display_name && (
                            <div>
                                <label className="block text-sm font-medium text-neutral-dark dark:text-gray-300">Nome de Exibição</label>
                                <p className="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">
                                    {permission.display_name}
                                </p>
                            </div>
                        )}
                        <div>
                            <label className="block text-sm font-medium text-neutral-dark dark:text-gray-300">Criado em</label>
                            <p className="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">
                                {formatDateBr(permission.created_at)}
                            </p>
                        </div>
                    </div>

                    {permission.roles && permission.roles.length > 0 && (
                        <div className="border-t border-neutral-medium pt-6">
                            <h3 className="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Roles Associados</h3>
                            <div className="flex flex-wrap gap-2">
                                {permission.roles.map((role) => (
                                    <span key={role.id} className="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary text-white">
                                        {role.display_name ?? role.name}
                                    </span>
                                ))}
                            </div>
                        </div>
                    )}

                    <div className="border-t border-neutral-medium pt-6">
                        <div className="flex gap-4">
                            <Link href={route('permissions.index')}>
                                <SecondaryButton type="button">Voltar</SecondaryButton>
                            </Link>
                            <LinkButton href={route('permissions.edit', permission.id)}>Editar</LinkButton>
                            <DeleteButton href={route('permissions.destroy', permission.id)} title="Excluir permissão?" />
                        </div>
                    </div>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
