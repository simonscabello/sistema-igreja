import { useEffect, useMemo, useState } from 'react';
import { Check, ChevronsUpDown, Plus } from 'lucide-react';
import { cn, getInitials } from '@/utils';
import { InputError, InputLabel } from '@/components/ui/Input';
import { Button } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Badge } from '@/components/ui/Badge';

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
    labelIcon?: React.ReactNode;
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
    labelIcon,
    error,
    imageField = false,
}: AdvancedSelectProps) {
    const [open, setOpen] = useState(false);
    const [search, setSearch] = useState('');
    const [localOptions, setLocalOptions] = useState<AdvancedSelectOption[]>(options);

    useEffect(() => {
        setLocalOptions(options);
    }, [options]);

    const availableOptions = useMemo(() => {
        return localOptions.filter((option) => !excludedIds.includes(option.value));
    }, [localOptions, excludedIds]);

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
        setSearch('');
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

        if (!multiple) {
            setOpen(false);
        }
    };

    return (
        <div>
            {label && (
                <div className={cn('mb-1.5 flex items-center gap-2')}>
                    {labelIcon}
                    <InputLabel htmlFor={id}>{label}</InputLabel>
                </div>
            )}

            <Popover open={open} onOpenChange={setOpen}>
                <PopoverTrigger asChild>
                    <Button
                        id={id}
                        type="button"
                        variant="outline"
                        role="combobox"
                        aria-expanded={open}
                        className="h-auto min-h-touch w-full justify-between font-normal"
                    >
                        <span className={cn('min-w-0 flex-1 truncate text-left', selectedOptions.length === 0 && 'text-muted-foreground')}>
                            {selectedOptions.length > 0
                                ? multiple
                                    ? `${selectedOptions.length} selecionado(s)`
                                    : selectedOptions.map((option) => option.label).join(', ')
                                : placeholder}
                        </span>
                        <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                    </Button>
                </PopoverTrigger>
                <PopoverContent className="w-[var(--radix-popover-trigger-width)] p-0" align="start">
                    <Command shouldFilter={searchable}>
                        {searchable && <CommandInput placeholder="Buscar..." value={search} onValueChange={setSearch} />}
                        <CommandList>
                            <CommandEmpty>
                                {creatable && search.trim() ? (
                                    <button
                                        type="button"
                                        onClick={handleCreate}
                                        className="flex w-full items-center gap-2 px-2 py-1.5 text-left text-sm"
                                    >
                                        <Plus className="h-4 w-4" />
                                        Adicionar “{search.trim()}”
                                    </button>
                                ) : (
                                    'Nenhuma opção encontrada.'
                                )}
                            </CommandEmpty>
                            <CommandGroup>
                                {availableOptions.map((option) => (
                                    <CommandItem key={String(option.value)} value={option.label} onSelect={() => toggleValue(option.value)}>
                                        {imageField &&
                                            (option.image ? (
                                                <img src={option.image} alt="" className="h-6 w-6 rounded-full object-cover" />
                                            ) : (
                                                <span className="flex h-6 w-6 items-center justify-center rounded-full bg-muted text-xs">
                                                    {getInitials(option.label)}
                                                </span>
                                            ))}
                                        <span className="min-w-0 flex-1 truncate">{option.label}</span>
                                        <Check className={cn('h-4 w-4', value.includes(option.value) ? 'opacity-100' : 'opacity-0')} />
                                    </CommandItem>
                                ))}
                            </CommandGroup>
                        </CommandList>
                    </Command>
                </PopoverContent>
            </Popover>

            {multiple && selectedOptions.length > 0 && (
                <div className="mt-2 flex flex-wrap gap-1">
                    {selectedOptions.map((option) => (
                        <Badge key={String(option.value)} tone="neutral">
                            {option.label}
                        </Badge>
                    ))}
                </div>
            )}

            {multiple && value.map((item) => <input key={item} type="hidden" name={`${name}[]`} value={item} />)}
            {!multiple && value[0] !== undefined && <input type="hidden" name={name} value={value[0]} />}

            <InputError message={error} />
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
        <div className="mt-4 space-y-2">
            <h4 className="text-sm font-medium">Ordem das músicas</h4>
            <div className="overflow-hidden rounded-lg border">
                <div className="hidden border-b bg-muted/40 px-3 py-2 text-xs font-medium uppercase tracking-wide text-muted-foreground sm:grid sm:grid-cols-[2.5rem_1fr_5.5rem] sm:gap-3">
                    <span>#</span>
                    <span>Música</span>
                    <span className="text-right">Tom</span>
                </div>
                {items.map((item, index) => (
                    <div
                        key={item.value}
                        className="grid grid-cols-1 gap-2 border-t px-3 py-3 first:border-t-0 sm:grid-cols-[2.5rem_1fr_5.5rem] sm:items-center sm:gap-3"
                    >
                        <span className="flex h-7 w-7 items-center justify-center rounded-full bg-muted text-sm font-semibold">
                            {index + 1}
                        </span>
                        <span className="min-w-0 text-sm font-medium">{item.label}</span>
                        <div className="sm:justify-self-end">
                            <label htmlFor={`song-key-${item.value}`} className="sr-only">
                                Tom de {item.label}
                            </label>
                            <input
                                id={`song-key-${item.value}`}
                                type="text"
                                name={`song_keys[${item.value}]`}
                                value={keys[item.value] ?? ''}
                                onChange={(event) => onKeyChange?.(item.value, event.target.value)}
                                placeholder="Tom"
                                className="flex h-9 w-full rounded-md border border-input bg-background px-3 text-center text-sm shadow-sm sm:w-20"
                            />
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
