import { Link } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton, SecondaryButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';

interface Member {
    id: number;
    full_name: string;
    email: string | null;
    mobile: string | null;
}

interface Department {
    id: number;
    title: string;
    description: string | null;
    is_active: boolean;
    members_count: number;
    responsible_members: Member[];
    members: Member[];
}

interface ShowProps {
    department: Department;
}

function MemberCard({ member }: { member: Member }) {
    return (
        <div className="bg-neutral-light dark:bg-gray-700 p-4 rounded-lg">
            <h4 className="font-medium text-neutral-dark dark:text-gray-300">{member.full_name}</h4>
            {member.email && <p className="text-sm text-neutral-medium dark:text-gray-400">{member.email}</p>}
            {member.mobile && <p className="text-sm text-neutral-medium dark:text-gray-400">{member.mobile}</p>}
        </div>
    );
}

function Show({ department }: ShowProps) {
    return (
        <AppPage>
            <PageCard title="Detalhes do Departamento">
                <div className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 className="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Informações Gerais</h3>
                            <dl className="space-y-3">
                                <div>
                                    <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Título</dt>
                                    <dd className="text-sm text-neutral-dark dark:text-gray-300">{department.title}</dd>
                                </div>
                                {department.description && (
                                    <div>
                                        <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Descrição</dt>
                                        <dd className="text-sm text-neutral-dark dark:text-gray-300">{department.description}</dd>
                                    </div>
                                )}
                                <div>
                                    <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Status</dt>
                                    <dd className="text-sm text-neutral-dark dark:text-gray-300">
                                        {department.is_active ? (
                                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Ativo
                                            </span>
                                        ) : (
                                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Inativo
                                            </span>
                                        )}
                                    </dd>
                                </div>
                                <div>
                                    <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Total de Membros</dt>
                                    <dd className="text-sm text-neutral-dark dark:text-gray-300">
                                        <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {department.members_count} membros
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {department.responsible_members.length > 0 && (
                        <div className="border-t border-neutral-medium pt-6">
                            <h3 className="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Líderes</h3>
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                {department.responsible_members.map((member) => (
                                    <MemberCard key={member.id} member={member} />
                                ))}
                            </div>
                        </div>
                    )}

                    {department.members.length > 0 && (
                        <div className="border-t border-neutral-medium pt-6">
                            <h3 className="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Membros</h3>
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                {department.members.map((member) => (
                                    <MemberCard key={member.id} member={member} />
                                ))}
                            </div>
                        </div>
                    )}

                    <div className="border-t border-neutral-medium pt-6">
                        <div className="flex gap-4">
                            <Link href={route('departments.index')}>
                                <SecondaryButton type="button">Voltar</SecondaryButton>
                            </Link>
                            <LinkButton href={route('departments.edit', department.id)}>Editar</LinkButton>
                            <DeleteButton href={route('departments.destroy', department.id)} />
                        </div>
                    </div>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
