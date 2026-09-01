import { FormEvent, useState } from 'react';
import { useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { CancelButton, SaveButton } from '@/components/ui/Button';
import { CurrencyInput, formatCurrencyForSubmit } from '@/components/ui/CurrencyInput';
import { DateInput, FileInput, Select, Textarea } from '@/components/ui/Input';
import { FormActions, FormPanel } from '@/components/ui/FormSection';
import { PageCard } from '@/components/ui/PageCard';
import { formatCurrency, route } from '@/utils';
import { CategorySubcategoryFields, TransactionTypeRadio } from '../components/TransactionFields';
import { Campaign, CategoryWithSubcategories } from '../types';

interface CreateProps {
    categories: CategoryWithSubcategories[];
    campaigns: Campaign[];
}

function Create({ categories, campaigns }: CreateProps) {
    const [categoryId, setCategoryId] = useState('');

    const { data, setData, post, processing, errors, transform } = useForm({
        financial_subcategory_id: '',
        campaign_id: '',
        type: 'entrada' as 'entrada' | 'saida',
        amount: '',
        action_date: '',
        description: '',
        attachment: null as File | null,
    });

    transform((formData) => ({
        ...formData,
        amount: formatCurrencyForSubmit(formData.amount),
        campaign_id: formData.campaign_id || null,
    }));

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        post(route('financial.transactions.store'), { forceFormData: true });
    };

    const hasErrors = Object.keys(errors).length > 0;

    const campaignOptions = campaigns.map((campaign) => ({
        value: campaign.id,
        label: `${campaign.name} (${formatCurrency(campaign.goal_amount)})`,
    }));

    return (
        <AppPage>
            <PageCard
                title="Nova transação"
                description="Toda transação fica em uma subcategoria. Campanha e anexo são opcionais."
                breadcrumbs={[{ label: 'Transações', href: route('financial.transactions.index') }, { label: 'Nova' }]}
            >
                {hasErrors && (
                    <Alert type="error" dismissible>
                        Corrija os campos destacados antes de salvar.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
                    <FormPanel>
                        <div className="max-w-2xl space-y-6">
                            <CategorySubcategoryFields
                                categories={categories}
                                categoryId={categoryId}
                                subcategoryId={data.financial_subcategory_id}
                                onCategoryChange={setCategoryId}
                                onSubcategoryChange={(value) => setData('financial_subcategory_id', value)}
                                subcategoryError={errors.financial_subcategory_id}
                            />

                            <Select
                                id="campaign_id"
                                label="Campanha (Opcional)"
                                value={data.campaign_id}
                                onChange={(event) => setData('campaign_id', event.target.value)}
                                options={campaignOptions}
                                placeholder="Nenhuma campanha"
                                error={errors.campaign_id}
                            />

                            <TransactionTypeRadio value={data.type} onChange={(type) => setData('type', type)} error={errors.type} />

                            <CurrencyInput
                                id="amount"
                                label="Valor"
                                value={data.amount}
                                onChange={(value) => setData('amount', value)}
                                required
                                error={errors.amount}
                            />

                            <DateInput
                                id="action_date"
                                label="Data da Ação"
                                value={data.action_date}
                                onChange={(event) => setData('action_date', event.target.value)}
                                required
                                error={errors.action_date}
                            />

                            <Textarea
                                id="description"
                                label="Descrição"
                                value={data.description}
                                onChange={(event) => setData('description', event.target.value)}
                                error={errors.description}
                            />

                            <FileInput
                                id="attachment"
                                label="Anexo (Opcional)"
                                accept=".pdf,.jpg,.jpeg,.png"
                                helpText="Anexe comprovantes, notas fiscais ou outros documentos. Formatos: PDF, JPG, PNG. Máximo: 10MB."
                                onChange={(event) => setData('attachment', event.target.files?.[0] ?? null)}
                                error={errors.attachment}
                            />
                        </div>
                    </FormPanel>

                    <FormActions>
                        <CancelButton href={route('financial.transactions.index')} />
                        <SaveButton processing={processing} />
                    </FormActions>
                </form>
            </PageCard>
        </AppPage>
    );
}

Create.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Create;
