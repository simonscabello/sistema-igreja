import { FormEvent } from 'react';
import { useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { CancelButton, SaveButton } from '@/components/ui/Button';
import { TextInput, Textarea } from '@/components/ui/Input';
import { FormActions, FormPanel } from '@/components/ui/FormSection';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';
import { ActiveToggle } from '../components/ActiveToggle';
import { SubcategoryListEditor } from '../components/SubcategoryListEditor';

function Create() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        description: '',
        active: true,
        subcategories: [{ name: '', active: true }],
    });

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        post(route('financial.categories.store'));
    };

    const hasErrors = Object.keys(errors).length > 0;

    return (
        <AppPage>
            <PageCard title="Nova Categoria Financeira">
                {hasErrors && (
                    <Alert type="error" dismissible>
                        <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
                    <FormPanel>
                        <div className="max-w-2xl space-y-6">
                            <TextInput
                                id="name"
                                label="Nome"
                                value={data.name}
                                onChange={(event) => setData('name', event.target.value)}
                                required
                                error={errors.name}
                            />
                            <Textarea
                                id="description"
                                label="Descrição"
                                value={data.description}
                                onChange={(event) => setData('description', event.target.value)}
                                error={errors.description}
                            />
                            <ActiveToggle checked={data.active} onChange={(active) => setData('active', active)} />

                            <SubcategoryListEditor
                                items={data.subcategories}
                                onChange={(subcategories) => setData('subcategories', subcategories)}
                                errors={errors as Record<string, string>}
                            />
                        </div>
                    </FormPanel>

                    <FormActions>
                        <CancelButton href={route('financial.categories.index')} />
                        <SaveButton processing={processing} />
                    </FormActions>
                </form>
            </PageCard>
        </AppPage>
    );
}

Create.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Create;
