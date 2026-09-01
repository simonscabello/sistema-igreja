import { FormEvent } from 'react';
import { useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { CancelButton, SaveButton } from '@/components/ui/Button';
import { Select, TextInput } from '@/components/ui/Input';
import { FormActions, FormPanel } from '@/components/ui/FormSection';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';
import { ActiveToggle } from '../components/ActiveToggle';
import { FinancialCategory, FinancialSubcategory } from '../types';

interface EditProps {
    financialSubcategory: FinancialSubcategory;
    categories: FinancialCategory[];
}

function Edit({ financialSubcategory, categories }: EditProps) {
    const { data, setData, put, processing, errors } = useForm({
        financial_category_id: String(financialSubcategory.financial_category_id),
        name: financialSubcategory.name,
        active: financialSubcategory.active,
    });

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        put(route('financial.subcategories.update', financialSubcategory.id));
    };

    const hasErrors = Object.keys(errors).length > 0;

    const categoryOptions = categories.map((category) => ({
        value: category.id,
        label: category.name,
    }));

    return (
        <AppPage>
            <PageCard title="Editar Subcategoria Financeira">
                {hasErrors && (
                    <Alert type="error" dismissible>
                        <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
                    <FormPanel>
                        <div className="max-w-2xl space-y-6">
                            <Select
                                id="financial_category_id"
                                label="Categoria"
                                value={data.financial_category_id}
                                onChange={(event) => setData('financial_category_id', event.target.value)}
                                options={categoryOptions}
                                required
                                error={errors.financial_category_id}
                            />
                            <TextInput
                                id="name"
                                label="Nome da Subcategoria"
                                value={data.name}
                                onChange={(event) => setData('name', event.target.value)}
                                required
                                error={errors.name}
                            />
                            <div className="flex flex-col items-start gap-1">
                                <span className="text-sm font-medium text-foreground mb-1">Ativo</span>
                                <ActiveToggle checked={data.active} onChange={(active) => setData('active', active)} label="" />
                            </div>
                        </div>
                    </FormPanel>

                    <FormActions>
                        <CancelButton href={route('financial.subcategories.index')} />
                        <SaveButton processing={processing}>Atualizar</SaveButton>
                    </FormActions>
                </form>
            </PageCard>
        </AppPage>
    );
}

Edit.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Edit;
