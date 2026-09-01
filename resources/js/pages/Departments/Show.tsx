import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Badge } from '@/components/ui/Badge';
import { BackButton, EditButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { DetailActions, DetailField, DetailGrid, DetailSection } from '@/components/ui/Detail';
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
        <div className="rounded-lg border bg-muted/40 p-4">
            <h4 className="font-medium">{member.full_name}</h4>
            {member.email && <p className="text-sm text-muted-foreground">{member.email}</p>}
            {member.mobile && <p className="text-sm text-muted-foreground">{member.mobile}</p>}
        </div>
    );
}

function Show({ department }: ShowProps) {
    return (
        <AppPage>
            <PageCard
                title={department.title}
                breadcrumbs={[{ label: 'Departamentos', href: route('departments.index') }, { label: department.title }]}
                action={
                    <>
                        <EditButton href={route('departments.edit', department.id)} size="md" />
                        <DeleteButton href={route('departments.destroy', department.id)} />
                    </>
                }
            >
                <div className="space-y-6">
                    <DetailSection title="Informações">
                        <DetailGrid>
                            <DetailField label="Título" value={department.title} />
                            <DetailField label="Descrição" value={department.description} />
                            <DetailField label="Status">
                                <Badge tone={department.is_active ? 'success' : 'neutral'}>
                                    {department.is_active ? 'Ativo' : 'Inativo'}
                                </Badge>
                            </DetailField>
                            <DetailField label="Total de membros">
                                <Badge tone="info">
                                    {department.members_count} {department.members_count === 1 ? 'membro' : 'membros'}
                                </Badge>
                            </DetailField>
                        </DetailGrid>
                    </DetailSection>

                    {department.responsible_members.length > 0 && (
                        <DetailSection title="Líderes">
                            <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                                {department.responsible_members.map((member) => (
                                    <MemberCard key={member.id} member={member} />
                                ))}
                            </div>
                        </DetailSection>
                    )}

                    {department.members.length > 0 && (
                        <DetailSection title="Membros">
                            <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                                {department.members.map((member) => (
                                    <MemberCard key={member.id} member={member} />
                                ))}
                            </div>
                        </DetailSection>
                    )}

                    <DetailActions>
                        <BackButton href={route('departments.index')} />
                    </DetailActions>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
