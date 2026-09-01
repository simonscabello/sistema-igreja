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

interface CreateProps {
    roles: Role[];
}

function Create({ roles }: CreateProps) {
    const { data, setData, processing, errors } = useForm({
        name: '',
        email: '',
        role_id: '',
    });

    const roleOptions = roles.map((role) => ({
        value: role.id,
        label: role.display_name ?? role.name,
    }));

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();

        const selectedRole = roles.find((role) => String(role.id) === data.role_id);

        router.post(route('users.store'), {
            name: data.name,
            email: data.email,
            roles: selectedRole ? [selectedRole.name] : [],
        });
    };

    const hasErrors = Object.keys(errors).length > 0;

    return (
        <AppPage>
            <PageCard title="Novo Usuário">
                {hasErrors && (
                    <Alert type="error" dismissible>
                        <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                    </Alert>
                )}

                <div className="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <h3 className="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">Informação</h3>
                    <p className="text-sm text-blue-700 dark:text-blue-300">
                        Uma senha temporária de 8 caracteres será gerada automaticamente baseada no nome do usuário.
                        O usuário deverá alterar esta senha no primeiro login.
                    </p>
                </div>

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
                            <p className="text-neutral-medium dark:text-gray-500">
                                Nenhum role encontrado.{' '}
                                <Link href={route('roles.create')} className="text-primary hover:underline">
                                    Criar role
                                </Link>
                            </p>
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
