import { FormEvent } from 'react';
import { useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { CancelButton, SaveButton } from '@/components/ui/Button';
import { TextInput } from '@/components/ui/Input';
import { FormActions, FormPanel } from '@/components/ui/FormSection';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';

function Create() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        display_name: '',
    });

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        post(route('permissions.store'));
    };

    const hasErrors = Object.keys(errors).length > 0;

    return (
        <AppPage>
            <PageCard title="Nova Permissão">
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

                        <div className="text-sm text-muted-foreground">
                            <p className="mb-2">Exemplos de nomes de permissão:</p>
                            <ul className="list-disc pl-5">
                                <li>visualizar_membros</li>
                                <li>criar_transacoes</li>
                                <li>gerenciar_usuarios</li>
                            </ul>
                        </div>
                    </FormPanel>

                    <FormActions>
                        <CancelButton href={route('permissions.index')} />
                        <SaveButton processing={processing} />
                    </FormActions>
                </form>
            </PageCard>
        </AppPage>
    );
}

Create.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Create;
