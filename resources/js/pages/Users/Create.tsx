import { FormEvent } from 'react';
import { Link, router, useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { CancelButton, SaveButton } from '@/components/ui/Button';
import { Select, TextInput } from '@/components/ui/Input';
import { FormActions, FormPanel } from '@/components/ui/FormSection';
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

                <Alert type="info" className="mb-6">
                    Uma senha temporária é gerada automaticamente. A pessoa troca no primeiro acesso.
                </Alert>

                <form onSubmit={handleSubmit} className="space-y-6">
                    <FormPanel>
                        <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <TextInput
                                id="name"
                                label="Nome"
                                value={data.name}
                                onChange={(event) => setData('name', event.target.value)}
                                placeholder="Nome completo"
                                required
                                error={errors.name}
                            />
                            <TextInput
                                id="email"
                                label="E-mail"
                                type="email"
                                value={data.email}
                                onChange={(event) => setData('email', event.target.value)}
                                placeholder="E-mail de acesso"
                                required
                                error={errors.email}
                            />
                        </div>

                        <div className="border-t pt-6 ">
                            <h2 className="mb-4 text-sm font-semibold text-foreground">Papel</h2>
                            {roles.length === 0 ? (
                                <p className="text-sm text-muted-foreground">
                                    Nenhum papel encontrado.{' '}
                                    <Link href={route('roles.create')} className="font-medium hover:underline">
                                        Criar papel
                                    </Link>
                                </p>
                            ) : (
                                <Select
                                    id="role_id"
                                    label="Papel"
                                    value={data.role_id}
                                    onChange={(event) => setData('role_id', event.target.value)}
                                    options={roleOptions}
                                    placeholder="Selecione um papel"
                                    error={errors.roles}
                                />
                            )}
                        </div>
                    </FormPanel>

                    <FormActions>
                        <CancelButton href={route('users.index')} />
                        <SaveButton processing={processing} />
                    </FormActions>
                </form>
            </PageCard>
        </AppPage>
    );
}

Create.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Create;
