import { Link } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Avatar } from '@/components/ui/Avatar';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { Button, LinkButton, SecondaryButton } from '@/components/ui/Button';
import { PageCard } from '@/components/ui/PageCard';
import { formatDateBr, route } from '@/utils';

interface Member {
    id: number;
    full_name: string;
    email: string | null;
    phone: string | null;
    mobile: string | null;
    gender: string | null;
    marital_status: string | null;
    birth_date: string | null;
    baptism_date: string | null;
    admission_date: string | null;
    wedding_date: string | null;
    zip_code: string | null;
    street: string | null;
    neighborhood: string | null;
    city: string | null;
    state: string | null;
    number: string | null;
    complement: string | null;
    foto_url?: string | null;
}

interface ShowProps {
    member: Member;
}

function DetailField({ label, value }: { label: string; value?: string | null }) {
    if (!value) {
        return null;
    }

    return (
        <div>
            <dt className="text-sm text-ink-muted dark:text-ink-inverse/60">{label}</dt>
            <dd className="mt-0.5 text-sm text-ink dark:text-ink-inverse">{value}</dd>
        </div>
    );
}

function Show({ member }: ShowProps) {
    const hasAddress = member.street || member.neighborhood || member.city || member.state || member.zip_code;

    return (
        <AppPage>
            <PageCard
                title={member.full_name}
                breadcrumbs={[{ label: 'Membros', href: route('members.index') }, { label: member.full_name }]}
                action={
                    <>
                        <Button href={route('members.edit', member.id)}>Editar</Button>
                        <DeleteButton href={route('members.destroy', member.id)} />
                    </>
                }
            >
                <div className="flex flex-col gap-8 lg:flex-row">
                    <div className="flex shrink-0 flex-col items-center lg:w-52">
                        <Avatar name={member.full_name} imageUrl={member.foto_url} size="w-32 h-32" />
                        {member.mobile && (
                            <a href={`tel:${member.mobile}`} className="mt-3 text-sm font-medium text-primary">
                                {member.mobile}
                            </a>
                        )}
                    </div>

                    <div className="min-w-0 flex-1 space-y-8">
                        <section>
                            <h2 className="mb-3 text-sm font-semibold text-ink dark:text-ink-inverse">Pessoais</h2>
                            <dl className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <DetailField label="E-mail" value={member.email} />
                                <DetailField label="Telefone" value={member.phone} />
                                <DetailField label="Gênero" value={member.gender} />
                                <DetailField label="Estado civil" value={member.marital_status} />
                            </dl>
                        </section>

                        <section>
                            <h2 className="mb-3 text-sm font-semibold text-ink dark:text-ink-inverse">Datas</h2>
                            <dl className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                <DetailField label="Nascimento" value={formatDateBr(member.birth_date)} />
                                <DetailField label="Batismo" value={member.baptism_date ? formatDateBr(member.baptism_date) : null} />
                                <DetailField label="Admissão" value={member.admission_date ? formatDateBr(member.admission_date) : null} />
                                <DetailField label="Casamento" value={member.wedding_date ? formatDateBr(member.wedding_date) : null} />
                            </dl>
                        </section>

                        {hasAddress && (
                            <section>
                                <h2 className="mb-3 text-sm font-semibold text-ink dark:text-ink-inverse">Endereço</h2>
                                <dl className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    {member.street && (
                                        <DetailField
                                            label="Rua"
                                            value={`${member.street}${member.number ? `, ${member.number}` : ''}${member.complement ? ` — ${member.complement}` : ''}`}
                                        />
                                    )}
                                    <DetailField label="Bairro" value={member.neighborhood} />
                                    <DetailField
                                        label="Cidade"
                                        value={[member.city, member.state].filter(Boolean).join(' — ') || null}
                                    />
                                    <DetailField label="CEP" value={member.zip_code} />
                                </dl>
                            </section>
                        )}

                        <div className="flex flex-wrap gap-3 border-t border-line pt-6 dark:border-line-dark">
                            <Link href={route('members.index')}>
                                <SecondaryButton type="button">Voltar à lista</SecondaryButton>
                            </Link>
                            <LinkButton href={route('members.files.index', member.id)}>Arquivos</LinkButton>
                        </div>
                    </div>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
