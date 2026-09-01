import { FormEvent } from 'react';
import { Link, useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { PrimaryButton, SecondaryButton } from '@/components/ui/Button';
import { Checkbox, TextInput } from '@/components/ui/Input';
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

interface CreateProps {
    groupedPermissions: Record<string, PermissionGroup>;
}

function formatPermissionLabel(permission: Permission): string {
    return permission.display_name ?? permission.name.replace(/_/g, ' ');
}

function Create({ groupedPermissions }: CreateProps) {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        display_name: '',
        permissions: [] as string[],
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
        post(route('roles.store'));
    };

    const hasErrors = Object.keys(errors).length > 0;
    const groups = Object.entries(groupedPermissions);

    return (
        <AppPage>
            <PageCard title="Novo papel">
                {hasErrors && (
                    <Alert type="error" dismissible>
                        <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
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

                    <div className="border-t border-neutral-medium pt-6">
                        <h3 className="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-6">Permissões</h3>

                        {groups.length === 0 ? (
                            <p className="text-neutral-medium dark:text-gray-500">
                                Nenhuma permissão encontrada.{' '}
                                <Link href={route('permissions.create')} className="text-primary hover:underline">
                                    Criar permissão
                                </Link>
                            </p>
                        ) : (
                            <div className="space-y-8">
                                {groups.map(([groupName, group]) => (
                                    <div key={groupName}>
                                        <h4 className="mb-4 font-semibold text-gray-900 dark:text-white text-base">{groupName}</h4>
                                        <p className="mb-4 text-sm text-gray-600 dark:text-gray-400">{group.description}</p>
                                        <ul className="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            {group.permissions.map((permission, index) => (
                                                <li
                                                    key={permission.id}
                                                    className={`w-full ${index < group.permissions.length - 1 ? 'border-b border-gray-200 dark:border-gray-600' : ''}`}
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

                    <div className="border-t border-neutral-medium mt-6 pt-6">
                        <div className="flex gap-4">
                            <Link href={route('roles.index')}>
                                <SecondaryButton type="button">Cancelar</SecondaryButton>
                            </Link>
                            <PrimaryButton type="submit" disabled={processing}>
                                Salvar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </PageCard>
        </AppPage>
    );
}

Create.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Create;
