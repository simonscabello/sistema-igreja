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
            <PageCard
                title={permission.display_name ?? permission.name}
                breadcrumbs={[
                    { label: 'Permissões', href: route('permissions.index') },
                    { label: permission.display_name ?? permission.name },
                ]}
                action={
                    <>
                        <EditButton href={route('permissions.edit', permission.id)} size="md" />
                        <DeleteButton href={route('permissions.destroy', permission.id)} title="Excluir permissão?" />
                    </>
                }
            >
                <div className="space-y-6">
                    <DetailSection title="Permissão">
                        <DetailGrid>
                            <DetailField label="Nome" value={permission.name} />
                            <DetailField label="Nome de exibição" value={permission.display_name} />
                            <DetailField label="Criado em" value={formatDateBr(permission.created_at)} />
                        </DetailGrid>
                    </DetailSection>

                    {permission.roles && permission.roles.length > 0 && (
                        <DetailSection title="Papéis associados">
                            <div className="flex flex-wrap gap-2">
                                {permission.roles.map((role) => (
                                    <Badge key={role.id}>{role.display_name ?? role.name}</Badge>
                                ))}
                            </div>
                        </DetailSection>
                    )}

                    <DetailActions>
                        <BackButton href={route('permissions.index')} />
                    </DetailActions>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
