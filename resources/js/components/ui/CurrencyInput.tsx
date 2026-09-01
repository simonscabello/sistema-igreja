import { NumericFormat } from 'react-number-format';
import { InputError, InputLabel, controlClass } from './Input';
import { cn } from '@/lib/utils';

interface CurrencyInputProps {
    id?: string;
    name?: string;
    label?: string;
    value?: string | number;
    onChange?: (value: string) => void;
    error?: string;
    required?: boolean;
    disabled?: boolean;
}

export function CurrencyInput({ id, name, label, value, onChange, error, required, disabled }: CurrencyInputProps) {
    return (
        <div className="space-y-1.5">
            {label && (
                <InputLabel htmlFor={id} required={required}>
                    {label}
                </InputLabel>
            )}
            <div className="relative">
                <span className="absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground">R$</span>
                <NumericFormat
                    id={id}
                    name={name}
                    value={value ?? ''}
                    thousandSeparator="."
                    decimalSeparator=","
                    decimalScale={2}
                    fixedDecimalScale
                    allowNegative={false}
                    disabled={disabled}
                    onValueChange={(values) => onChange?.(values.value)}
                    className={cn(controlClass, 'pl-10 tabular-nums')}
                />
            </div>
            <InputError message={error} />
        </div>
    );
}

export function formatCurrencyForSubmit(value: string): string {
    if (!value) {
        return '';
    }

    const numeric = parseFloat(value);

    return numeric.toFixed(2).replace('.', ',');
}

export function formatCurrencyForDisplay(value: string | number | null | undefined): string {
    if (value === null || value === undefined || value === '') {
        return '';
    }

    const numeric = typeof value === 'string' ? parseFloat(value.replace(',', '.')) : value;

    return numeric.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
