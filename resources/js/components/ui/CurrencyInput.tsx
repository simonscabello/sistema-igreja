import { NumericFormat } from 'react-number-format';
import { InputError, InputLabel } from './Input';

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
        <div>
            {label && (
                <InputLabel htmlFor={id} required={required}>
                    {label}
                </InputLabel>
            )}
            <div className="relative mt-1.5">
                <span className="absolute inset-y-0 left-0 flex items-center pl-3 text-ink-muted dark:text-ink-inverse/70">
                    R$
                </span>
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
                    className="block w-full min-h-touch rounded-lg border-line bg-surface pl-10 text-ink tabular-nums shadow-none focus:border-primary focus:ring-primary dark:border-line-dark dark:bg-white/5 dark:text-ink-inverse"
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
