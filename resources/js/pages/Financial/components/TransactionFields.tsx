import { useMemo } from 'react';
import { Link } from '@inertiajs/react';
import { InputError, InputLabel, Select } from '@/components/ui/Input';
import { AdvancedSelect } from '@/components/ui/AdvancedSelect';
import { FilterButton } from '@/components/ui/Button';
import { Paperclip } from 'lucide-react';
import { route } from '@/utils';
import { CategoryWithSubcategories } from '../types';

interface TransactionTypeRadioProps {
    value: string;
    onChange: (value: 'entrada' | 'saida') => void;
    error?: string;
}

export function TransactionTypeRadio({ value, onChange, error }: TransactionTypeRadioProps) {
    return (
        <div>
            <InputLabel required>Tipo</InputLabel>
            <div className="mt-2 flex flex-wrap gap-2">
                {(['entrada', 'saida'] as const).map((type) => {
                    const selected = value === type;

                    return (
                        <label key={type} className="relative cursor-pointer">
                            <input
                                type="radio"
                                name="type"
                                value={type}
                                className="sr-only"
                                checked={selected}
                                onChange={() => onChange(type)}
                            />
                            <span
                                className={`inline-flex min-h-touch min-w-[7.5rem] items-center justify-center rounded-lg border px-4 text-sm font-medium transition-colors ${
                                    type === 'entrada'
                                        ? selected
                                            ? 'border-entrada bg-entrada text-white'
                                            : 'border-entrada/40 text-entrada hover:bg-entrada/10'
                                        : selected
                                          ? 'border-saida bg-saida text-white'
                                          : 'border-saida/40 text-saida hover:bg-saida/10'
                                }`}
                            >
                                {type === 'entrada' ? 'Entrada' : 'Saída'}
                            </span>
                        </label>
                    );
                })}
            </div>
            <InputError message={error} />
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
                className="min-h-touch rounded-lg border-input bg-background text-foreground placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-1 focus-visible:ring-ring sm:col-span-2"
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
            <FilterButton />
        </form>
    );
}

export function AttachmentIcon({ hasAttachment }: { hasAttachment: boolean }) {
    if (!hasAttachment) {
        return null;
    }

    return <Paperclip className="h-4 w-4 text-primary" aria-label="Possui anexo" />;
}

export function CampaignLink({ id, name }: { id: number; name: string }) {
    return (
        <Link href={route('financial.campaigns.show', id)} className="font-medium hover:underline">
            {name}
        </Link>
    );
}
