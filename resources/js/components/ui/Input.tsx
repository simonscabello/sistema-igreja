import { useState } from 'react';
import { Calendar, Eye, EyeOff } from 'lucide-react';
import { cn } from '@/lib/utils';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea as UiTextarea } from '@/components/ui/textarea';
import { Checkbox as UiCheckbox } from '@/components/ui/checkbox';
import { RadioGroup as UiRadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Select as UiSelect, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

interface InputLabelProps {
    htmlFor?: string;
    required?: boolean;
    className?: string;
    children: React.ReactNode;
}

export function InputLabel({ htmlFor, required, className, children }: InputLabelProps) {
    return (
        <Label htmlFor={htmlFor} className={cn('text-foreground', className)}>
            {children}
            {required && (
                <span className="ms-1 text-destructive" aria-hidden>
                    *
                </span>
            )}
        </Label>
    );
}

interface InputErrorProps {
    message?: string;
    className?: string;
}

export function InputError({ message, className }: InputErrorProps) {
    if (!message) {
        return null;
    }

    return (
        <p className={cn('mt-1.5 text-sm text-destructive', className)} role="alert">
            {message}
        </p>
    );
}

export const controlClass =
    'flex h-10 min-h-touch w-full rounded-md border border-input bg-background px-3 py-2 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm';

interface TextInputProps extends React.InputHTMLAttributes<HTMLInputElement> {
    label?: string;
    error?: string;
    hint?: string;
}

export function TextInput({ label, error, hint, id, required, className, ...props }: TextInputProps) {
    return (
        <div className="space-y-1.5">
            {label && (
                <InputLabel htmlFor={id} required={required}>
                    {label}
                </InputLabel>
            )}
            <Input id={id} required={required} className={className} {...props} />
            {hint && !error && <p className="text-sm text-muted-foreground">{hint}</p>}
            <InputError message={error} />
        </div>
    );
}

export function PasswordInput({ label, error, id, required, className, ...props }: TextInputProps) {
    const [visible, setVisible] = useState(false);

    return (
        <div className="space-y-1.5">
            {label && (
                <InputLabel htmlFor={id} required={required}>
                    {label}
                </InputLabel>
            )}
            <div className="relative">
                <Input id={id} required={required} {...props} type={visible ? 'text' : 'password'} className={cn('pr-11', className)} />
                <button
                    type="button"
                    onClick={() => setVisible((current) => !current)}
                    className="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-muted-foreground hover:text-foreground"
                    aria-label={visible ? 'Ocultar senha' : 'Mostrar senha'}
                >
                    {visible ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                </button>
            </div>
            <InputError message={error} />
        </div>
    );
}

interface TextareaProps extends React.TextareaHTMLAttributes<HTMLTextAreaElement> {
    label?: string;
    error?: string;
}

export function Textarea({ label, error, id, required, className, ...props }: TextareaProps) {
    return (
        <div className="space-y-1.5">
            {label && (
                <InputLabel htmlFor={id} required={required}>
                    {label}
                </InputLabel>
            )}
            <UiTextarea id={id} required={required} className={className} {...props} />
            <InputError message={error} />
        </div>
    );
}

export function DateInput({
    output = 'br',
    value,
    onChange,
    className,
    label,
    error,
    hint,
    id,
    required,
    name,
    ...props
}: Omit<TextInputProps, 'type'> & { output?: 'br' | 'iso' }) {
    if (output === 'iso') {
        return (
            <TextInput
                type="date"
                value={value}
                onChange={onChange}
                className={className}
                label={label}
                error={error}
                hint={hint}
                id={id}
                required={required}
                name={name}
                {...props}
            />
        );
    }

    const isoValue = brToIso(String(value ?? ''));

    return (
        <div className="space-y-1.5">
            {label && (
                <InputLabel htmlFor={id} required={required}>
                    {label}
                </InputLabel>
            )}
            <div className="relative">
                <Input
                    id={id}
                    name={name}
                    required={required}
                    type="text"
                    inputMode="numeric"
                    placeholder="dd/mm/aaaa"
                    value={value}
                    onChange={onChange}
                    className={cn('pr-11', className)}
                    {...props}
                />
                <span className="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-muted-foreground">
                    <Calendar className="pointer-events-none h-4 w-4" aria-hidden />
                    <input
                        type="date"
                        value={isoValue}
                        onChange={(event) => {
                            const next = event.target.value ? isoToBr(event.target.value) : '';
                            onChange?.({
                                target: { value: next, name: name ?? id ?? '' },
                            } as React.ChangeEvent<HTMLInputElement>);
                        }}
                        className="absolute inset-0 cursor-pointer opacity-0"
                        aria-label="Escolher data no calendário"
                        tabIndex={-1}
                    />
                </span>
            </div>
            {hint && !error && <p className="text-sm text-muted-foreground">{hint}</p>}
            <InputError message={error} />
        </div>
    );
}

function brToIso(value: string): string {
    const match = value.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);

    if (!match) {
        return '';
    }

    return `${match[3]}-${match[2]}-${match[1]}`;
}

function isoToBr(value: string): string {
    const match = value.match(/^(\d{4})-(\d{2})-(\d{2})$/);

    if (!match) {
        return '';
    }

    return `${match[3]}/${match[2]}/${match[1]}`;
}

interface CheckboxProps {
    id?: string;
    name?: string;
    label: string;
    checked?: boolean;
    onChange?: (checked: boolean) => void;
    error?: string;
}

export function Checkbox({ id, name, label, checked, onChange, error }: CheckboxProps) {
    return (
        <div>
            <label htmlFor={id} className="inline-flex min-h-touch cursor-pointer items-center gap-2">
                {name && <input type="hidden" name={name} value="0" />}
                <UiCheckbox id={id} name={name} checked={checked} onCheckedChange={(value) => onChange?.(value === true)} />
                <span className="text-sm text-foreground">{label}</span>
            </label>
            <InputError message={error} />
        </div>
    );
}

interface SelectProps {
    id?: string;
    name?: string;
    label?: string;
    error?: string;
    options: Array<{ value: string | number; label: string }>;
    placeholder?: string;
    className?: string;
    required?: boolean;
    value?: string | number;
    onChange?: React.ChangeEventHandler<HTMLSelectElement>;
    disabled?: boolean;
}

export function Select({
    label,
    error,
    id,
    name,
    required,
    options,
    placeholder = 'Selecione...',
    className,
    value,
    onChange,
    disabled,
}: SelectProps) {
    const emptyValue = '__empty__';
    const stringValue = value === undefined || value === null || value === '' ? emptyValue : String(value);

    return (
        <div className="space-y-1.5">
            {label && (
                <InputLabel htmlFor={id} required={required}>
                    {label}
                </InputLabel>
            )}
            <UiSelect
                value={stringValue}
                onValueChange={(next) => {
                    onChange?.({
                        target: { value: next === emptyValue ? '' : next, name: name ?? id ?? '' },
                    } as React.ChangeEvent<HTMLSelectElement>);
                }}
                disabled={disabled}
                required={required}
                name={name}
            >
                <SelectTrigger id={id} className={className}>
                    <SelectValue placeholder={placeholder} />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value={emptyValue}>{placeholder}</SelectItem>
                    {options.map((option) => (
                        <SelectItem key={String(option.value)} value={String(option.value)}>
                            {option.label}
                        </SelectItem>
                    ))}
                </SelectContent>
            </UiSelect>
            <InputError message={error} />
        </div>
    );
}

interface RadioGroupProps {
    label?: string;
    name: string;
    value?: string;
    onChange?: (value: string) => void;
    options: Array<{ value: string; label: string }>;
    error?: string;
    required?: boolean;
}

export function RadioGroup({ label, name, value, onChange, options, error, required }: RadioGroupProps) {
    return (
        <div className="space-y-1.5">
            {label && <InputLabel required={required}>{label}</InputLabel>}
            <UiRadioGroup name={name} value={value} onValueChange={onChange} className="flex flex-wrap gap-x-4 gap-y-2">
                {options.map((option) => (
                    <label key={option.value} className="inline-flex min-h-touch cursor-pointer items-center gap-2">
                        <RadioGroupItem value={option.value} id={`${name}-${option.value}`} />
                        <span className="text-sm text-foreground">{option.label}</span>
                    </label>
                ))}
            </UiRadioGroup>
            <InputError message={error} />
        </div>
    );
}

interface FileInputProps extends React.InputHTMLAttributes<HTMLInputElement> {
    label?: string;
    error?: string;
    helpText?: string;
}

export function FileInput({ label, error, helpText, id, required, className, ...props }: FileInputProps) {
    return (
        <div className="space-y-1.5">
            {label && (
                <InputLabel htmlFor={id} required={required}>
                    {label}
                </InputLabel>
            )}
            <Input id={id} type="file" className={cn('pt-1.5', className)} {...props} />
            {helpText && <p className="text-sm text-muted-foreground">{helpText}</p>}
            <InputError message={error} />
        </div>
    );
}
