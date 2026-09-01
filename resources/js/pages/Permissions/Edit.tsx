import { FormEvent } from 'react';
import { useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { CancelButton, SaveButton } from '@/components/ui/Button';
import { TextInput } from '@/components/ui/Input';
import { FormActions, FormPanel } from '@/components/ui/FormSection';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';

interface Permission {
    id: number;
    name: string;
    display_name: string | null;
}

interface EditProps {
    permission: Permission;
}

function Edit({ permission }: EditProps) {
    const { data, setData, put, processing, errors } = useForm({
        name: permission.name,
        display_name: permission.display_name ?? '',
    });

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        put(route('permissions.update', permission.id));
    };

    const hasErrors = Object.keys(errors).length > 0;

    return (
        <AppPage>
            <PageCard title="Editar Permissão">
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
                                placeholder="Digite o nome da permissão"
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
                    </FormPanel>

                    <FormActions>
                        <CancelButton href={route('permissions.index')} />
                        <SaveButton processing={processing}>Atualizar</SaveButton>
                    </FormActions>
                </form>
            </PageCard>
        </AppPage>
    );
}

Edit.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Edit;
