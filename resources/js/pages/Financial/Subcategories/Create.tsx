import { FormEvent } from 'react';
import { Link, useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { PrimaryButton, SecondaryButton } from '@/components/ui/Button';
import { Select, TextInput } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';
import { ActiveToggle } from '../components/ActiveToggle';
import { FinancialCategory } from '../types';

interface CreateProps {
    categories: FinancialCategory[];
}

function Create({ categories }: CreateProps) {
    const { data, setData, post, processing, errors, transform } = useForm({
        financial_category_id: '',
        name: '',
        active: true,
    });

    transform((formData) => ({
        financial_category_id: formData.financial_category_id,
        subcategories: [{ name: formData.name, active: formData.active }],
    }));

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        post(route('financial.subcategories.store'));
    };

    const hasErrors = Object.keys(errors).length > 0;

    const categoryOptions = categories.map((category) => ({
        value: category.id,
        label: category.name,
    }));

    return (
        <AppPage>
            <PageCard title="Nova Subcategoria Financeira">
                {hasErrors && (
                    <Alert type="error" dismissible>
                        <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
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
                            label="Nome"
                            value={data.name}
                            onChange={(event) => setData('name', event.target.value)}
                            required
                            error={errors.name ?? errors['subcategories.0.name']}
                        />
                        <ActiveToggle checked={data.active} onChange={(active) => setData('active', active)} />
                    </div>

                    <div className="border-t border-neutral-medium mt-6 pt-6">
                        <div className="flex gap-4">
                            <Link href={route('financial.subcategories.index')}>
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
