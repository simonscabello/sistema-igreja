import { Link } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton, SecondaryButton } from '@/components/ui/Button';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';

interface Permission {
    id: number;
    name: string;
    display_name?: string | null;
}

interface PermissionGroup {
    permissions: Permission[];
    description: string;
}

interface User {
    id: number;
    name: string;
    email: string;
}

interface Role {
    id: number;
    name: string;
    display_name: string | null;
    permissions: Permission[];
    users: User[];
}

interface ShowProps {
    role: Role;
    groupedPermissions: Record<string, PermissionGroup>;
}

function formatPermissionLabel(permission: Permission): string {
    return permission.display_name ?? permission.name.replace(/_/g, ' ');
}

function Show({ role, groupedPermissions }: ShowProps) {
    const groups = Object.entries(groupedPermissions);

    return (
        <AppPage>
            <PageCard title="Papel">
                <div className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-neutral-dark dark:text-gray-300">Nome</label>
                            <p className="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">
                                {role.name}
                            </p>
                        </div>
                        {role.display_name && (
                            <div>
                                <label className="block text-sm font-medium text-neutral-dark dark:text-gray-300">Nome de Exibição</label>
                                <p className="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">
                                    {role.display_name}
                                </p>
                            </div>
                        )}
                        <div>
                            <label className="block text-sm font-medium text-neutral-dark dark:text-gray-300">Número de Usuários</label>
                            <p className="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">
                                {role.users.length}
                            </p>
                        </div>
                    </div>

                    <div className="border-t border-neutral-medium pt-6">
                        <h3 className="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-6">Permissões Associadas</h3>

                        {role.permissions.length === 0 ? (
                            <p className="text-neutral-medium dark:text-gray-500">Nenhuma permissão associada a este role.</p>
                        ) : groups.length > 0 ? (
                            <div className="space-y-8">
                                {groups.map(([groupName, group]) => (
                                    <div key={groupName}>
                                        <h4 className="mb-4 font-semibold text-gray-900 dark:text-white text-base">{groupName}</h4>
                                        <p className="mb-4 text-sm text-gray-600 dark:text-gray-400">{group.description}</p>
                                        <div className="bg-white border border-gray-200 rounded-lg p-4 dark:bg-gray-700 dark:border-gray-600">
                                            <div className="flex flex-wrap gap-2">
                                                {group.permissions.map((permission) => (
                                                    <span
                                                        key={permission.id}
                                                        className="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary text-white"
                                                    >
                                                        {formatPermissionLabel(permission)}
                                                    </span>
                                                ))}
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                {role.permissions.map((permission) => (
                                    <div key={permission.id} className="bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">
                                        <span className="text-sm text-neutral-dark dark:text-gray-300">{formatPermissionLabel(permission)}</span>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>

                    <div className="border-t border-neutral-medium pt-6">
                        <h3 className="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Usuários com este Role</h3>
                        {role.users.length === 0 ? (
                            <p className="text-neutral-medium dark:text-gray-500">Nenhum usuário possui este role.</p>
                        ) : (
                            <div className="space-y-2">
                                {role.users.map((user) => (
                                    <div key={user.id} className="bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md flex justify-between items-center">
                                        <div>
                                            <span className="text-sm font-medium text-neutral-dark dark:text-gray-300">{user.name}</span>
                                            <span className="text-xs text-neutral-medium dark:text-gray-400 ml-2">{user.email}</span>
                                        </div>
                                        <Link href={route('users.show', user.id)} className="text-primary hover:underline text-sm">
                                            Ver
                                        </Link>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>

                    <div className="border-t border-neutral-medium pt-6">
                        <div className="flex gap-4">
                            <Link href={route('roles.index')}>
                                <SecondaryButton type="button">Voltar</SecondaryButton>
                            </Link>
                            <LinkButton href={route('roles.edit', role.id)}>Editar</LinkButton>
                        </div>
                    </div>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
