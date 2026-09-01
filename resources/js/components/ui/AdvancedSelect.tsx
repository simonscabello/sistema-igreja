import { useMemo, useState } from 'react';
import { cn, getInitials } from '@/utils';

export interface AdvancedSelectOption {
    value: string | number;
    label: string;
    image?: string | null;
}

interface AdvancedSelectProps {
    id?: string;
    name?: string;
    options: AdvancedSelectOption[];
    value: Array<string | number>;
    onChange: (value: Array<string | number>) => void;
    excludedIds?: Array<string | number>;
    multiple?: boolean;
    searchable?: boolean;
    creatable?: boolean;
    placeholder?: string;
    label?: string;
    error?: string;
    imageField?: boolean;
}

export function AdvancedSelect({
    id,
    name,
    options,
    value,
    onChange,
    excludedIds = [],
    multiple = false,
    searchable = false,
    creatable = false,
    placeholder = 'Selecione...',
    label,
    error,
    imageField = false,
}: AdvancedSelectProps) {
    const [search, setSearch] = useState('');
    const [open, setOpen] = useState(false);
    const [localOptions, setLocalOptions] = useState<AdvancedSelectOption[]>(options);

    const availableOptions = useMemo(() => {
        return localOptions.filter((option) => !excludedIds.includes(option.value));
    }, [localOptions, excludedIds]);

    const filteredOptions = useMemo(() => {
        if (!searchable || !search.trim()) {
            return availableOptions;
        }

        const term = search.toLowerCase();

        return availableOptions.filter((option) => option.label.toLowerCase().includes(term));
    }, [availableOptions, search, searchable]);

    const selectedOptions = localOptions.filter((option) => value.includes(option.value));

    const toggleValue = (optionValue: string | number) => {
        if (multiple) {
            if (value.includes(optionValue)) {
                onChange(value.filter((item) => item !== optionValue));
                return;
            }

            onChange([...value, optionValue]);
            return;
        }

        onChange([optionValue]);
        setOpen(false);
    };

    const handleCreate = () => {
        if (!creatable || !search.trim()) {
            return;
        }

        const newValue = search.trim();
        const exists = localOptions.some((option) => option.label.toLowerCase() === newValue.toLowerCase());

        if (exists) {
            return;
        }

        const option: AdvancedSelectOption = { value: newValue, label: newValue };
        setLocalOptions((current) => [...current, option]);
        onChange(multiple ? [...value, newValue] : [newValue]);
        setSearch('');
    };

    return (
        <div className="relative">
            {label && <label className="block font-medium text-sm text-neutral-dark dark:text-gray-300 mb-1">{label}</label>}

            <button
                type="button"
                id={id}
                onClick={() => setOpen((current) => !current)}
                className="mt-1 w-full text-left border border-neutral-medium dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-neutral-dark dark:text-white"
            >
                {selectedOptions.length > 0 ? selectedOptions.map((option) => option.label).join(', ') : placeholder}
            </button>

            {open && (
                <div className="absolute z-20 mt-1 w-full rounded-md border border-neutral-medium dark:border-gray-600 bg-white dark:bg-gray-800 shadow-lg max-h-60 overflow-auto">
                    {searchable && (
                        <div className="p-2 border-b border-neutral-medium dark:border-gray-700">
                            <input
                                type="text"
                                value={search}
                                onChange={(event) => setSearch(event.target.value)}
                                onKeyDown={(event) => {
                                    if (event.key === 'Enter' && creatable) {
                                        event.preventDefault();
                                        handleCreate();
                                    }
                                }}
                                placeholder="Buscar..."
                                className="w-full rounded-md border-neutral-medium dark:border-gray-600 bg-white dark:bg-gray-700 px-2 py-1 text-sm"
                            />
                        </div>
                    )}
                    {filteredOptions.map((option) => (
                        <button
                            key={option.value}
                            type="button"
                            onClick={() => toggleValue(option.value)}
                            className={cn(
                                'w-full flex items-center gap-2 px-3 py-2 text-left text-sm hover:bg-neutral-light dark:hover:bg-gray-700',
                                value.includes(option.value) && 'bg-primary/10',
                            )}
                        >
                            {imageField && (
                                option.image ? (
                                    <img src={option.image} alt="" className="w-6 h-6 rounded-full object-cover" />
                                ) : (
                                    <span className="w-6 h-6 rounded-full bg-primary/20 text-xs flex items-center justify-center">
                                        {getInitials(option.label)}
                                    </span>
                                )
                            )}
                            <span>{option.label}</span>
                        </button>
                    ))}
                    {creatable && search.trim() && !filteredOptions.some((option) => option.label.toLowerCase() === search.toLowerCase()) && (
                        <button type="button" onClick={handleCreate} className="w-full px-3 py-2 text-left text-sm text-primary hover:bg-primary/10">
                            Adicionar "{search.trim()}"
                        </button>
                    )}
                </div>
            )}

            {multiple &&
                value.map((item) => (
                    <input key={item} type="hidden" name={`${name}[]`} value={item} />
                ))}
            {!multiple && value[0] !== undefined && <input type="hidden" name={name} value={value[0]} />}

            {error && <p className="mt-2 text-sm text-red-600 dark:text-red-400">{error}</p>}
        </div>
    );
}

interface OrderedListProps {
    items: AdvancedSelectOption[];
    keys?: Record<string | number, string>;
    onKeyChange?: (id: string | number, key: string) => void;
}

export function OrderedSongList({ items, keys = {}, onKeyChange }: OrderedListProps) {
    if (items.length === 0) {
        return null;
    }

    return (
        <div className="space-y-2 mt-4">
            <h4 className="text-sm font-medium text-neutral-dark dark:text-gray-300">Ordem das músicas</h4>
            {items.map((item, index) => (
                <div key={item.value} className="flex items-center gap-3 p-3 border border-neutral-medium dark:border-gray-600 rounded-md">
                    <span className="text-sm font-semibold text-primary w-6">{index + 1}</span>
                    <span className="flex-1 text-sm text-neutral-dark dark:text-gray-300">{item.label}</span>
                    <input
                        type="text"
                        name={`song_keys[${item.value}]`}
                        value={keys[item.value] ?? ''}
                        onChange={(event) => onKeyChange?.(item.value, event.target.value)}
                        placeholder="Tom"
                        className="w-24 rounded-md border-neutral-medium dark:border-gray-600 bg-white dark:bg-gray-700 px-2 py-1 text-sm"
                    />
                </div>
            ))}
        </div>
    );
}
