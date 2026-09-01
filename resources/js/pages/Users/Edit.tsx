import { FormEvent } from 'react';
import { Link, router, useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { PrimaryButton, SecondaryButton } from '@/components/ui/Button';
import { Select, TextInput } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';

interface Role {
    id: number;
    name: string;
    display_name: string | null;
}

interface User {
    id: number;
    name: string;
    email: string;
    roles: Role[];
}

interface EditProps {
    user: User;
    roles: Role[];
}

function Edit({ user, roles }: EditProps) {
    const currentRoleId = user.roles[0]?.id ? String(user.roles[0].id) : '';

    const { data, setData, processing, errors } = useForm({
        name: user.name,
        email: user.email,
        role_id: currentRoleId,
    });

    const roleOptions = roles.map((role) => ({
        value: role.id,
        label: role.display_name ?? role.name,
    }));

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();

        const selectedRole = roles.find((role) => String(role.id) === data.role_id);

        router.put(route('users.update', user.id), {
            name: data.name,
            email: data.email,
            roles: selectedRole ? [selectedRole.name] : [],
        });
    };

    const hasErrors = Object.keys(errors).length > 0;

    return (
        <AppPage>
            <PageCard title="Editar Usuário">
                {hasErrors && (
                    <Alert type="error" dismissible>
                        <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <TextInput
                            id="name"
                            label="Nome"
                            value={data.name}
                            onChange={(event) => setData('name', event.target.value)}
                            placeholder="Digite o nome completo"
                            required
                            error={errors.name}
                        />
                        <TextInput
                            id="email"
                            label="Email"
                            type="email"
                            value={data.email}
                            onChange={(event) => setData('email', event.target.value)}
                            placeholder="Digite o email"
                            required
                            error={errors.email}
                        />
                    </div>

                    <div className="border-t border-neutral-medium pt-6">
                        <h3 className="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Role</h3>
                        {roles.length === 0 ? (
                            <p className="text-neutral-medium dark:text-gray-500">Nenhum role encontrado.</p>
                        ) : (
                            <Select
                                id="role_id"
                                label="Role"
                                value={data.role_id}
                                onChange={(event) => setData('role_id', event.target.value)}
                                options={roleOptions}
                                placeholder="Selecione um role..."
                                error={errors.roles}
                            />
                        )}
                    </div>

                    <div className="border-t border-neutral-medium mt-6 pt-6">
                        <div className="flex gap-4">
                            <Link href={route('users.index')}>
                                <SecondaryButton type="button">Cancelar</SecondaryButton>
                            </Link>
                            <PrimaryButton type="submit" disabled={processing}>
                                Atualizar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </PageCard>
        </AppPage>
    );
}

Edit.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Edit;
