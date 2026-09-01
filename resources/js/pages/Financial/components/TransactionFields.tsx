import { useMemo } from 'react';
import { Link } from '@inertiajs/react';
import { Select } from '@/components/ui/Input';
import { AdvancedSelect } from '@/components/ui/AdvancedSelect';
import { route } from '@/utils';
import { CategoryWithSubcategories } from '../types';

interface TransactionTypeRadioProps {
    value: string;
    onChange: (value: 'entrada' | 'saida') => void;
    error?: string;
}

export function TransactionTypeRadio({ value, onChange, error }: TransactionTypeRadioProps) {
    return (
        <div className="space-y-2">
            <label className="block text-md font-bold text-gray-700 dark:text-gray-300">
                Tipo <span className="text-red-500 ml-1">*</span>
            </label>
            <div className="flex gap-4">
                {(['entrada', 'saida'] as const).map((type) => (
                    <label key={type} className="relative cursor-pointer">
                        <input
                            type="radio"
                            name="type"
                            value={type}
                            className="sr-only peer"
                            checked={value === type}
                            onChange={() => onChange(type)}
                        />
                        <div
                            className={`px-6 py-2 rounded-lg border transition-all duration-200 text-md font-medium flex flex-row items-center justify-center gap-2 min-w-[120px] ${
                                type === 'entrada'
                                    ? 'peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 text-green-600 border-green-400 hover:bg-green-50'
                                    : 'peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500 text-red-600 border-red-400 hover:bg-red-50'
                            }`}
                        >
                            <span>{type === 'entrada' ? 'Entrada' : 'Saída'}</span>
                        </div>
                    </label>
                ))}
            </div>
            {error && <p className="mt-2 text-sm text-red-600 dark:text-red-400">{error}</p>}
        </div>
    );
}

interface CategorySubcategoryFieldsProps {
    categories: CategoryWithSubcategories[];
    categoryId: string;
    subcategoryId: string;
    onCategoryChange: (categoryId: string) => void;
    onSubcategoryChange: (subcategoryId: string) => void;
    categoryError?: string;
    subcategoryError?: string;
}

export function CategorySubcategoryFields({
    categories,
    categoryId,
    subcategoryId,
    onCategoryChange,
    onSubcategoryChange,
    categoryError,
    subcategoryError,
}: CategorySubcategoryFieldsProps) {
    const subcategories = useMemo(() => {
        const category = categories.find((item) => String(item.id) === categoryId);

        return (category?.subcategories ?? []).filter((item) => item.active);
    }, [categories, categoryId]);

    const categoryOptions = categories.map((category) => ({
        value: category.id,
        label: category.name,
    }));

    const subcategoryOptions = subcategories.map((subcategory) => ({
        value: subcategory.id,
        label: subcategory.name,
    }));

    return (
        <>
            <Select
                id="financial_category_id"
                label="Categoria"
                value={categoryId}
                onChange={(event) => {
                    onCategoryChange(event.target.value);
                    onSubcategoryChange('');
                }}
                options={categoryOptions}
                placeholder="Selecione uma categoria"
                required
                error={categoryError}
            />
            <Select
                id="financial_subcategory_id"
                label="Subcategoria"
                value={subcategoryId}
                onChange={(event) => onSubcategoryChange(event.target.value)}
                options={subcategoryOptions}
                placeholder={categoryId ? 'Selecione uma subcategoria' : 'Selecione uma categoria primeiro'}
                required
                disabled={!categoryId}
                error={subcategoryError}
            />
        </>
    );
}

interface TransactionFiltersProps {
    categories: CategoryWithSubcategories[];
    campaigns: Array<{ id: number; name: string }>;
    search: string;
    type: string;
    subcategory: string;
    campaign: string;
    onSearchChange: (value: string) => void;
    onTypeChange: (value: string) => void;
    onSubcategoryChange: (value: string) => void;
    onCampaignChange: (value: string) => void;
    onSubmit: () => void;
}

export function TransactionFilters({
    categories,
    campaigns,
    search,
    type,
    subcategory,
    campaign,
    onSearchChange,
    onTypeChange,
    onSubcategoryChange,
    onCampaignChange,
    onSubmit,
}: TransactionFiltersProps) {
    const subcategoryOptions = useMemo(
        () =>
            categories.flatMap((category) =>
                category.subcategories.map((item) => ({
                    value: item.id,
                    label: `${category.name} / ${item.name}`,
                })),
            ),
        [categories],
    );

    const campaignOptions = campaigns.map((item) => ({
        value: item.id,
        label: item.name,
    }));

    return (
        <form
            onSubmit={(event) => {
                event.preventDefault();
                onSubmit();
            }}
            className="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-6"
        >
            <input
                type="search"
                value={search}
                onChange={(event) => onSearchChange(event.target.value)}
                placeholder="Descrição ou valor"
                className="min-h-touch rounded-lg border-line bg-surface text-ink placeholder:text-ink-muted/70 focus:border-primary focus:ring-primary dark:border-line-dark dark:bg-surface-dark dark:text-ink-inverse sm:col-span-2"
            />
            <Select
                id="type"
                value={type}
                onChange={(event) => onTypeChange(event.target.value)}
                options={[
                    { value: 'entrada', label: 'Entradas' },
                    { value: 'saida', label: 'Saídas' },
                ]}
                placeholder="Todos os tipos"
            />
            <AdvancedSelect
                id="subcategory"
                name="subcategory"
                searchable
                value={subcategory ? [subcategory] : []}
                onChange={(value) => onSubcategoryChange(String(value[0] ?? ''))}
                options={subcategoryOptions}
                placeholder="Todas as subcategorias"
            />
            <AdvancedSelect
                id="campaign"
                name="campaign"
                searchable
                value={campaign ? [campaign] : []}
                onChange={(value) => onCampaignChange(String(value[0] ?? ''))}
                options={campaignOptions}
                placeholder="Todas as campanhas"
            />
            <button
                type="submit"
                className="inline-flex min-h-touch items-center justify-center rounded-lg bg-primary px-4 text-sm font-medium text-white hover:bg-primary-dark"
            >
                Filtrar
            </button>
        </form>
    );
}

export function AttachmentIcon({ hasAttachment }: { hasAttachment: boolean }) {
    if (!hasAttachment) {
        return null;
    }

    return (
        <svg className="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Possui anexo">
            <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
            />
        </svg>
    );
}

export function CampaignLink({ id, name }: { id: number; name: string }) {
    return (
        <Link href={route('financial.campaigns.show', id)} className="text-primary hover:text-primary-dark">
            {name}
        </Link>
    );
}
