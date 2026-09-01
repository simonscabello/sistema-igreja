import { FormEvent } from 'react';
import { useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { CancelButton, SaveButton } from '@/components/ui/Button';
import { CurrencyInput, formatCurrencyForDisplay, formatCurrencyForSubmit } from '@/components/ui/CurrencyInput';
import { DateInput, Select, TextInput, Textarea } from '@/components/ui/Input';
import { FormActions, FormPanel } from '@/components/ui/FormSection';
import { PageCard } from '@/components/ui/PageCard';
import { formatDateBr, route } from '@/utils';
import { Campaign } from '../types';

const STATUS_OPTIONS = [
    { value: 'ativo', label: 'Ativo' },
    { value: 'encerrado', label: 'Encerrado' },
    { value: 'cancelada', label: 'Cancelada' },
];

interface EditProps {
    campaign: Campaign;
}

function Edit({ campaign }: EditProps) {
    const { data, setData, put, processing, errors, transform } = useForm({
        name: campaign.name,
        description: campaign.description ?? '',
        goal_amount: formatCurrencyForDisplay(campaign.goal_amount),
        start_date: formatDateBr(campaign.start_date),
        end_date: formatDateBr(campaign.end_date),
        status: campaign.status,
    });

    transform((formData) => ({
        ...formData,
        goal_amount: formatCurrencyForSubmit(formData.goal_amount),
        start_date: formData.start_date === '-' ? '' : formData.start_date,
        end_date: formData.end_date === '-' ? '' : formData.end_date,
    }));

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        put(route('financial.campaigns.update', campaign.id));
    };

    const hasErrors = Object.keys(errors).length > 0;

    return (
        <AppPage>
            <PageCard title="Editar Campanha">
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
                            <CurrencyInput
                                id="goal_amount"
                                label="Meta (R$)"
                                value={data.goal_amount}
                                onChange={(value) => setData('goal_amount', value)}
                                required
                                error={errors.goal_amount}
                            />
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <DateInput
                                    id="start_date"
                                    label="Data de Início"
                                    value={data.start_date === '-' ? '' : data.start_date}
                                    onChange={(event) => setData('start_date', event.target.value)}
                                    error={errors.start_date}
                                />
                                <DateInput
                                    id="end_date"
                                    label="Data de Término"
                                    value={data.end_date === '-' ? '' : data.end_date}
                                    onChange={(event) => setData('end_date', event.target.value)}
                                    error={errors.end_date}
                                />
                            </div>
                            <Select
                                id="status"
                                label="Status"
                                value={data.status}
                                onChange={(event) => setData('status', event.target.value)}
                                options={STATUS_OPTIONS}
                                required
                                error={errors.status}
                            />
                        </div>
                    </FormPanel>

                    <FormActions>
                        <CancelButton href={route('financial.campaigns.index')} />
                        <SaveButton processing={processing}>Atualizar Campanha</SaveButton>
                    </FormActions>
                </form>
            </PageCard>
        </AppPage>
    );
}

Edit.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Edit;
