import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Badge } from '@/components/ui/Badge';
import { BackButton, EditButton, ViewButton } from '@/components/ui/Button';
import { DetailActions, DetailField, DetailGrid, DetailSection } from '@/components/ui/Detail';
import { EmptyState } from '@/components/ui/EmptyState';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';
import { Shield } from 'lucide-react';

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
            <PageCard
                title={role.display_name ?? role.name}
                breadcrumbs={[{ label: 'Papéis', href: route('roles.index') }, { label: role.display_name ?? role.name }]}
                action={<EditButton href={route('roles.edit', role.id)} size="md" />}
            >
                <div className="space-y-6">
                    <DetailSection title="Papel">
                        <DetailGrid>
                            <DetailField label="Nome" value={role.name} />
                            <DetailField label="Nome de exibição" value={role.display_name} />
                            <DetailField label="Usuários" value={String(role.users.length)} />
                        </DetailGrid>
                    </DetailSection>

                    <DetailSection title="Permissões">
                        {role.permissions.length === 0 ? (
                            <EmptyState
                                title="Nenhuma permissão associada"
                                description="Edite o papel para atribuir permissões."
                                icon={<Shield className="h-8 w-8" />}
                            />
                        ) : groups.length > 0 ? (
                            <div className="space-y-6">
                                {groups.map(([groupName, group]) => (
                                    <div key={groupName} className="rounded-lg border p-4">
                                        <h3 className="text-sm font-semibold">{groupName}</h3>
                                        <p className="mb-3 text-sm text-muted-foreground">{group.description}</p>
                                        <div className="flex flex-wrap gap-2">
                                            {group.permissions.map((permission) => (
                                                <Badge key={permission.id}>{formatPermissionLabel(permission)}</Badge>
                                            ))}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <div className="flex flex-wrap gap-2">
                                {role.permissions.map((permission) => (
                                    <Badge key={permission.id}>{formatPermissionLabel(permission)}</Badge>
                                ))}
                            </div>
                        )}
                    </DetailSection>

                    <DetailSection title="Usuários com este papel">
                        {role.users.length === 0 ? (
                            <p className="text-sm text-muted-foreground">Nenhum usuário possui este papel.</p>
                        ) : (
                            <ul className="space-y-2">
                                {role.users.map((user) => (
                                    <li
                                        key={user.id}
                                        className="flex items-center justify-between gap-3 rounded-lg border bg-muted/40 px-3 py-2"
                                    >
                                        <div className="min-w-0">
                                            <p className="truncate text-sm font-medium">{user.name}</p>
                                            <p className="truncate text-xs text-muted-foreground">{user.email}</p>
                                        </div>
                                        <ViewButton href={route('users.show', user.id)} />
                                    </li>
                                ))}
                            </ul>
                        )}
                    </DetailSection>

                    <DetailActions>
                        <BackButton href={route('roles.index')} />
                    </DetailActions>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
