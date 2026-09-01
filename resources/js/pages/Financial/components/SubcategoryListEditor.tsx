import { TextInput } from '@/components/ui/Input';
import { SubcategoryFormItem } from '../types';
import { ActiveToggle } from './ActiveToggle';

interface SubcategoryListEditorProps {
    items: SubcategoryFormItem[];
    onChange: (items: SubcategoryFormItem[]) => void;
    errors?: Record<string, string>;
}

export function SubcategoryListEditor({ items, onChange, errors = {} }: SubcategoryListEditorProps) {
    const updateItem = (index: number, patch: Partial<SubcategoryFormItem>) => {
        onChange(items.map((item, currentIndex) => (currentIndex === index ? { ...item, ...patch } : item)));
    };

    const addItem = () => {
        onChange([...items, { name: '', active: true }]);
    };

    const removeItem = (index: number) => {
        if (items.length <= 1) {
            return;
        }

        onChange(items.filter((_, currentIndex) => currentIndex !== index));
    };

    return (
        <div>
            <div className="flex justify-between items-center mb-4">
                <span className="block font-medium text-sm text-neutral-dark dark:text-gray-300">Subcategorias</span>
                <button
                    type="button"
                    onClick={addItem}
                    className="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700"
                >
                    + Adicionar
                </button>
            </div>

            <div className="space-y-3">
                {items.map((item, index) => (
                    <div key={index} className="flex items-center gap-3">
                        <div className="flex-1">
                            <TextInput
                                id={`subcategory-${index}`}
                                value={item.name}
                                onChange={(event) => updateItem(index, { name: event.target.value })}
                                placeholder="Nome da subcategoria"
                                error={errors[`subcategories.${index}.name`]}
                            />
                        </div>
                        <ActiveToggle
                            id={`subcategory-active-${index}`}
                            checked={item.active}
                            onChange={(active) => updateItem(index, { active })}
                        />
                        {items.length > 1 && (
                            <button
                                type="button"
                                onClick={() => removeItem(index)}
                                className="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700"
                            >
                                Remover
                            </button>
                        )}
                    </div>
                ))}
            </div>

            {errors.subcategories && <p className="mt-2 text-sm text-red-600 dark:text-red-400">{errors.subcategories}</p>}
        </div>
    );
}
