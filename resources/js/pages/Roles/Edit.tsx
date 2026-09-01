import { FormEvent } from 'react';
import { useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { CancelButton, SaveButton } from '@/components/ui/Button';
import { Checkbox, TextInput } from '@/components/ui/Input';
import { FormActions, FormPanel } from '@/components/ui/FormSection';
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

interface Role {
    id: number;
    name: string;
    display_name: string | null;
    permissions: Permission[];
}

interface EditProps {
    role: Role;
    groupedPermissions: Record<string, PermissionGroup>;
}

function formatPermissionLabel(permission: Permission): string {
    return permission.display_name ?? permission.name.replace(/_/g, ' ');
}

function Edit({ role, groupedPermissions }: EditProps) {
    const { data, setData, put, processing, errors } = useForm({
        name: role.name,
        display_name: role.display_name ?? '',
        permissions: role.permissions.map((permission) => permission.name),
    });

    const togglePermission = (permissionName: string, checked: boolean) => {
        if (checked) {
            setData('permissions', [...data.permissions, permissionName]);
            return;
        }

        setData(
            'permissions',
            data.permissions.filter((name) => name !== permissionName),
        );
    };

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        put(route('roles.update', role.id));
    };

    const hasErrors = Object.keys(errors).length > 0;
    const groups = Object.entries(groupedPermissions);

    return (
        <AppPage>
            <PageCard title="Editar papel">
                {hasErrors && (
                    <Alert type="error" dismissible>
                        <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
                    <FormPanel>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <TextInput
                                id="name"
                                label="Nome"
                                value={data.name}
                                onChange={(event) => setData('name', event.target.value)}
                                placeholder="Digite o nome do role"
                                required
                                error={errors.name}
                            />
                            <TextInput
                                id="display_name"
                                label="Nome de Exibição"
                                value={data.display_name}
                                onChange={(event) => setData('display_name', event.target.value)}
                                placeholder="Nome amigável para exibição"
                                error={errors.display_name}
                            />
                        </div>

                        <div className="border-t pt-6">
                            <h3 className="mb-6 text-base font-semibold">Permissões</h3>

                            {groups.length === 0 ? (
                                <p className="text-muted-foreground">Nenhuma permissão encontrada.</p>
                            ) : (
                                <div className="space-y-8">
                                    {groups.map(([groupName, group]) => (
                                        <div key={groupName}>
                                            <h4 className="mb-4 font-semibold text-foreground text-base">{groupName}</h4>
                                            <p className="mb-4 text-sm text-muted-foreground">{group.description}</p>
                                            <ul className="w-full rounded-lg border bg-card text-sm font-medium text-foreground">
                                                {group.permissions.map((permission, index) => (
                                                    <li
                                                        key={permission.id}
                                                        className={`w-full ${index < group.permissions.length - 1 ? 'border-b' : ''}`}
                                                    >
                                                        <div className="flex items-center ps-3 py-1">
                                                            <Checkbox
                                                                id={`permission_${permission.id}`}
                                                                label={formatPermissionLabel(permission)}
                                                                checked={data.permissions.includes(permission.name)}
                                                                onChange={(checked) => togglePermission(permission.name, checked)}
                                                            />
                                                        </div>
                                                    </li>
                                                ))}
                                            </ul>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    </FormPanel>

                    <FormActions>
                        <CancelButton href={route('roles.index')} />
                        <SaveButton processing={processing}>Atualizar</SaveButton>
                    </FormActions>
                </form>
            </PageCard>
        </AppPage>
    );
}

Edit.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Edit;
