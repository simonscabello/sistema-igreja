import { Link } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton, SecondaryButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { PageCard } from '@/components/ui/PageCard';
import { formatDateBr, route } from '@/utils';

interface Visitor {
    id: number;
    name: string;
    mobile: string | null;
    age_group: string | null;
    gender: string | null;
    visit_date: string | null;
    wants_contact: boolean;
    full_address: string | null;
    notes: string | null;
}

interface ShowProps {
    visitor: Visitor;
}

function formatAgeGroup(value: string | null): string {
    if (!value) {
        return '';
    }

    if (value === 'crianca_adolescente') {
        return 'Criança / Adolescente';
    }

    return value.charAt(0).toUpperCase() + value.slice(1);
}

function Show({ visitor }: ShowProps) {
    return (
        <AppPage>
            <PageCard title="Detalhes do Visitante">
                <div className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 className="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Informações Pessoais</h3>
                            <dl className="space-y-3">
                                <div>
                                    <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Nome</dt>
                                    <dd className="text-sm text-neutral-dark dark:text-gray-300">{visitor.name}</dd>
                                </div>
                                {visitor.mobile && (
                                    <div>
                                        <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Celular</dt>
                                        <dd className="text-sm text-neutral-dark dark:text-gray-300">{visitor.mobile}</dd>
                                    </div>
                                )}
                                {visitor.age_group && (
                                    <div>
                                        <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Faixa Etária</dt>
                                        <dd className="text-sm text-neutral-dark dark:text-gray-300">{formatAgeGroup(visitor.age_group)}</dd>
                                    </div>
                                )}
                                {visitor.gender && (
                                    <div>
                                        <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Gênero</dt>
                                        <dd className="text-sm text-neutral-dark dark:text-gray-300">
                                            {visitor.gender.charAt(0).toUpperCase() + visitor.gender.slice(1)}
                                        </dd>
                                    </div>
                                )}
                            </dl>
                        </div>

                        <div>
                            <h3 className="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Informações da Visita</h3>
                            <dl className="space-y-3">
                                {visitor.visit_date && (
                                    <div>
                                        <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Data da Visita</dt>
                                        <dd className="text-sm text-neutral-dark dark:text-gray-300">{formatDateBr(visitor.visit_date)}</dd>
                                    </div>
                                )}
                                <div>
                                    <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Deseja ser contactado?</dt>
                                    <dd className="text-sm text-neutral-dark dark:text-gray-300">
                                        {visitor.wants_contact ? (
                                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Sim
                                            </span>
                                        ) : (
                                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                Não
                                            </span>
                                        )}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {visitor.full_address && (
                        <div className="border-t border-neutral-medium pt-6">
                            <h3 className="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Endereço</h3>
                            <p className="text-sm text-neutral-dark dark:text-gray-300 whitespace-pre-line">{visitor.full_address}</p>
                        </div>
                    )}

                    {visitor.notes && (
                        <div className="border-t border-neutral-medium pt-6">
                            <h3 className="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Observações</h3>
                            <p className="text-sm text-neutral-dark dark:text-gray-300 whitespace-pre-line">{visitor.notes}</p>
                        </div>
                    )}

                    <div className="border-t border-neutral-medium pt-6">
                        <div className="flex gap-4">
                            <Link href={route('visitors.index')}>
                                <SecondaryButton type="button">Voltar</SecondaryButton>
                            </Link>
                            <LinkButton href={route('visitors.edit', visitor.id)}>Editar</LinkButton>
                            <DeleteButton href={route('visitors.destroy', visitor.id)} />
                        </div>
                    </div>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
