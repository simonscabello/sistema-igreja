import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Avatar } from '@/components/ui/Avatar';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { BackButton, Button, EditButton } from '@/components/ui/Button';
import { DetailActions, DetailField, DetailGrid, DetailSection } from '@/components/ui/Detail';
import { PageCard } from '@/components/ui/PageCard';
import { Card, CardContent } from '@/components/ui/card';
import { FolderOpen } from 'lucide-react';
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

function Show({ member }: ShowProps) {
    const hasAddress = member.street || member.neighborhood || member.city || member.state || member.zip_code;

    return (
        <AppPage>
            <PageCard
                title={member.full_name}
                breadcrumbs={[{ label: 'Membros', href: route('members.index') }, { label: member.full_name }]}
                action={
                    <>
                        <EditButton href={route('members.edit', member.id)} size="md" />
                        <DeleteButton href={route('members.destroy', member.id)} />
                    </>
                }
            >
                <div className="grid gap-6 lg:grid-cols-[16rem_minmax(0,1fr)]">
                    <Card className="shadow-none">
                        <CardContent className="flex flex-col items-center pt-6">
                            <Avatar name={member.full_name} imageUrl={member.foto_url} size="h-32 w-32 text-2xl" />
                            {member.mobile && (
                                <a href={`tel:${member.mobile}`} className="mt-3 text-sm font-medium hover:underline">
                                    {member.mobile}
                                </a>
                            )}
                            {member.email && <p className="mt-1 text-sm text-muted-foreground">{member.email}</p>}
                        </CardContent>
                    </Card>

                    <div className="space-y-6">
                        <DetailSection title="Pessoais">
                            <DetailGrid>
                                <DetailField label="E-mail" value={member.email} />
                                <DetailField label="Telefone" value={member.phone} />
                                <DetailField label="Gênero" value={member.gender} />
                                <DetailField label="Estado civil" value={member.marital_status} />
                            </DetailGrid>
                        </DetailSection>

                        <DetailSection title="Datas">
                            <DetailGrid columns={4}>
                                <DetailField label="Nascimento" value={formatDateBr(member.birth_date)} />
                                <DetailField label="Batismo" value={member.baptism_date ? formatDateBr(member.baptism_date) : null} />
                                <DetailField label="Admissão" value={member.admission_date ? formatDateBr(member.admission_date) : null} />
                                <DetailField label="Casamento" value={member.wedding_date ? formatDateBr(member.wedding_date) : null} />
                            </DetailGrid>
                        </DetailSection>

                        {hasAddress && (
                            <DetailSection title="Endereço">
                                <DetailGrid>
                                    {member.street && (
                                        <DetailField
                                            label="Rua"
                                            value={`${member.street}${member.number ? `, ${member.number}` : ''}${member.complement ? ` — ${member.complement}` : ''}`}
                                        />
                                    )}
                                    <DetailField label="Bairro" value={member.neighborhood} />
                                    <DetailField label="Cidade" value={[member.city, member.state].filter(Boolean).join(' — ') || null} />
                                    <DetailField label="CEP" value={member.zip_code} />
                                </DetailGrid>
                            </DetailSection>
                        )}

                        <DetailActions>
                            <BackButton href={route('members.index')}>Voltar à lista</BackButton>
                            <Button href={route('members.files.index', member.id)} variant="secondary" icon={FolderOpen}>
                                Arquivos
                            </Button>
                        </DetailActions>
                    </div>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
