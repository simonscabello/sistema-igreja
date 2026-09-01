import { FormEvent } from 'react';
import { Link, useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { PrimaryButton, SecondaryButton } from '@/components/ui/Button';
import { TextInput, Textarea } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';
import { ActiveToggle } from '../components/ActiveToggle';
import { FinancialCategory } from '../types';

interface EditProps {
    financialCategory: FinancialCategory;
}

function Edit({ financialCategory }: EditProps) {
    const { data, setData, put, processing, errors } = useForm({
        name: financialCategory.name,
        description: financialCategory.description ?? '',
        active: financialCategory.active,
    });

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        put(route('financial.categories.update', financialCategory.id));
    };

    const hasErrors = Object.keys(errors).length > 0;

    return (
        <AppPage>
            <PageCard title="Editar Categoria Financeira">
                {hasErrors && (
                    <Alert type="error" dismissible>
                        <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
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
                        <div className="flex flex-col items-start gap-1">
                            <span className="text-sm font-medium text-neutral-dark dark:text-gray-300 mb-1">Ativo</span>
                            <ActiveToggle checked={data.active} onChange={(active) => setData('active', active)} label="" />
                        </div>
                    </div>

                    <div className="border-t border-neutral-medium mt-6 pt-6">
                        <div className="flex gap-4">
                            <Link href={route('financial.categories.index')}>
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
