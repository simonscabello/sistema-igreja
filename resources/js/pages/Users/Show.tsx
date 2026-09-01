import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Badge } from '@/components/ui/Badge';
import { BackButton, EditButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { DetailActions, DetailField, DetailGrid, DetailSection } from '@/components/ui/Detail';
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
            <PageCard
                title={user.name}
                breadcrumbs={[{ label: 'Usuários', href: route('users.index') }, { label: user.name }]}
                action={
                    <>
                        <EditButton href={route('users.edit', user.id)} size="md" />
                        <DeleteButton href={route('users.destroy', user.id)} title="Excluir usuário?" />
                    </>
                }
            >
                <div className="space-y-6">
                    <DetailSection title="Dados de acesso">
                        <DetailGrid>
                            <DetailField label="Nome" value={user.name} />
                            <DetailField label="E-mail" value={user.email} />
                            <DetailField label="Deve alterar senha" value={user.must_change_password ? 'Sim' : 'Não'} />
                            <DetailField label="Criado em" value={formatDateBr(user.created_at)} />
                        </DetailGrid>
                    </DetailSection>

                    <DetailSection title="Papéis">
                        {user.roles.length === 0 ? (
                            <p className="text-sm text-muted-foreground">Nenhum papel atribuído.</p>
                        ) : (
                            <div className="flex flex-wrap gap-2">
                                {user.roles.map((role) => (
                                    <Badge key={role.id}>{role.display_name ?? role.name}</Badge>
                                ))}
                            </div>
                        )}
                    </DetailSection>

                    <DetailActions>
                        <BackButton href={route('users.index')} />
                    </DetailActions>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
