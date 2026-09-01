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

interface User {
    id: number;
    name: string;
    email: string;
    must_change_password: boolean;
    roles: Role[];
    created_at: string;
    updated_at: string;
}

interface ShowProps {
    user: User;
}

function Show({ user }: ShowProps) {
    return (
        <AppPage>
            <PageCard title="Detalhes do Usuário">
                <div className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-neutral-dark dark:text-gray-300">Nome</label>
                            <p className="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">
                                {user.name}
                            </p>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-neutral-dark dark:text-gray-300">Email</label>
                            <p className="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">
                                {user.email}
                            </p>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-neutral-dark dark:text-gray-300">Deve alterar senha</label>
                            <p className="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">
                                {user.must_change_password ? 'Sim' : 'Não'}
                            </p>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-neutral-dark dark:text-gray-300">Criado em</label>
                            <p className="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">
                                {formatDateBr(user.created_at)}
                            </p>
                        </div>
                    </div>

                    <div className="border-t border-neutral-medium pt-6">
                        <h3 className="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Roles</h3>
                        {user.roles.length === 0 ? (
                            <p className="text-neutral-medium dark:text-gray-500">Nenhuma role atribuída.</p>
                        ) : (
                            <div className="flex flex-wrap gap-2">
                                {user.roles.map((role) => (
                                    <span key={role.id} className="inline-block bg-primary text-white text-xs px-3 py-1 rounded-full">
                                        {role.display_name ?? role.name}
                                    </span>
                                ))}
                            </div>
                        )}
                    </div>

                    <div className="border-t border-neutral-medium pt-6">
                        <div className="flex gap-4">
                            <Link href={route('users.index')}>
                                <SecondaryButton type="button">Voltar</SecondaryButton>
                            </Link>
                            <LinkButton href={route('users.edit', user.id)}>Editar</LinkButton>
                            <DeleteButton href={route('users.destroy', user.id)} title="Excluir usuário?" />
                        </div>
                    </div>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
