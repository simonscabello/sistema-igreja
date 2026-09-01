import { FormEvent, useState } from 'react';
import { Link, useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { PrimaryButton, SecondaryButton } from '@/components/ui/Button';
import { CurrencyInput, formatCurrencyForDisplay, formatCurrencyForSubmit } from '@/components/ui/CurrencyInput';
import { DateInput, FileInput, Select, Textarea } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { formatCurrency, formatDateBr, route } from '@/utils';
import { CategorySubcategoryFields, TransactionTypeRadio } from '../components/TransactionFields';
import { Campaign, CategoryWithSubcategories, FinancialTransaction } from '../types';

interface EditProps {
    financialTransaction: FinancialTransaction;
    categories: CategoryWithSubcategories[];
    campaigns: Campaign[];
}

function Edit({ financialTransaction, categories, campaigns }: EditProps) {
    const initialCategoryId = String(financialTransaction.subcategory?.financial_category_id ?? '');

    const [categoryId, setCategoryId] = useState(initialCategoryId);

    const { data, setData, post, processing, errors, transform } = useForm({
        _method: 'PUT',
        financial_subcategory_id: String(financialTransaction.financial_subcategory_id),
        campaign_id: financialTransaction.campaign_id ? String(financialTransaction.campaign_id) : '',
        type: financialTransaction.type,
        amount: formatCurrencyForDisplay(financialTransaction.amount),
        action_date: formatDateBr(financialTransaction.action_date),
        description: financialTransaction.description ?? '',
        attachment: null as File | null,
    });

    transform((formData) => ({
        ...formData,
        amount: formatCurrencyForSubmit(formData.amount),
        campaign_id: formData.campaign_id || null,
    }));

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        post(route('financial.transactions.update', financialTransaction.id), { forceFormData: true });
    };

    const hasErrors = Object.keys(errors).length > 0;

    const campaignOptions = campaigns.map((campaign) => ({
        value: campaign.id,
        label: `${campaign.name} (${formatCurrency(campaign.goal_amount)})`,
    }));

    const existingAttachments = financialTransaction.files ?? [];

    return (
        <AppPage>
            <PageCard title="Editar Transação Financeira">
                {hasErrors && (
                    <Alert type="error" dismissible>
                        <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
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

                        <TransactionTypeRadio
                            value={data.type}
                            onChange={(type) => setData('type', type)}
                            error={errors.type}
                        />

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

                        {existingAttachments.length > 0 && (
                            <div className="space-y-2">
                                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">Anexo Atual</label>
                                <div className="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 space-y-2">
                                    {existingAttachments.map((attachment) => (
                                        <div key={attachment.id} className="flex items-center justify-between">
                                            <div className="flex items-center space-x-2">
                                                <span className="text-sm text-gray-600 dark:text-gray-300">{attachment.original_name}</span>
                                                <span className="text-xs text-gray-500">({(attachment.size / 1024).toFixed(1)} KB)</span>
                                            </div>
                                            {attachment.url && (
                                                <a href={attachment.url} target="_blank" rel="noreferrer" className="text-primary hover:text-primary-dark text-sm underline">
                                                    Visualizar
                                                </a>
                                            )}
                                        </div>
                                    ))}
                                    <p className="text-xs text-gray-500 mt-2">Selecione um novo arquivo para substituir o anexo atual.</p>
                                </div>
                            </div>
                        )}
                    </div>

                    <div className="border-t border-neutral-medium mt-6 pt-6">
                        <div className="flex gap-4">
                            <Link href={route('financial.transactions.index')}>
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
