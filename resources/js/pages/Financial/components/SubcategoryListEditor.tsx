import { Button } from '@/components/ui/Button';
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
            <div className="mb-4 flex items-center justify-between gap-3">
                <span className="text-sm font-medium text-foreground">Subcategorias</span>
                <Button type="button" variant="secondary" size="sm" onClick={addItem}>
                    Adicionar
                </Button>
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
                            <Button type="button" variant="danger" size="sm" onClick={() => removeItem(index)}>
                                Remover
                            </Button>
                        )}
                    </div>
                ))}
            </div>

            {errors.subcategories && (
                <p className="mt-2 text-sm text-saida dark:text-red-400" role="alert">
                    {errors.subcategories}
                </p>
            )}
        </div>
    );
}
