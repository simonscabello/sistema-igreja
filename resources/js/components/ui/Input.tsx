import { useState } from 'react';
import { Eye, EyeOff } from 'lucide-react';
import { cn } from '@/utils';

interface InputLabelProps {
    htmlFor?: string;
    required?: boolean;
    children: React.ReactNode;
}

export function InputLabel({ htmlFor, required, children }: InputLabelProps) {
    return (
        <label htmlFor={htmlFor} className="block text-sm font-medium text-ink dark:text-ink-inverse">
            {children}
            {required && (
                <span className="ms-1 text-saida" aria-hidden>
                    *
                </span>
            )}
        </label>
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
        <p className={cn('mt-1.5 text-sm text-saida dark:text-red-400', className)} role="alert">
            {message}
        </p>
    );
}

const controlClass =
    'mt-1.5 block w-full min-h-touch rounded-lg border-line bg-surface text-ink shadow-none transition-colors placeholder:text-ink-muted/70 focus:border-primary focus:ring-primary dark:border-line-dark dark:bg-white/5 dark:text-ink-inverse';

interface TextInputProps extends React.InputHTMLAttributes<HTMLInputElement> {
    label?: string;
    error?: string;
    hint?: string;
}

export function TextInput({ label, error, hint, id, required, className, ...props }: TextInputProps) {
    return (
        <div>
            {label && (
                <InputLabel htmlFor={id} required={required}>
                    {label}
                </InputLabel>
            )}
            <input id={id} required={required} className={cn(controlClass, className)} {...props} />
            {hint && !error && <p className="mt-1.5 text-sm text-ink-muted dark:text-ink-inverse/60">{hint}</p>}
            <InputError message={error} />
        </div>
    );
}

export function PasswordInput({ label, error, id, required, className, ...props }: TextInputProps) {
    const [visible, setVisible] = useState(false);

    return (
        <div>
            {label && (
                <InputLabel htmlFor={id} required={required}>
                    {label}
                </InputLabel>
            )}
            <div className="relative mt-1.5">
                <input
                    id={id}
                    required={required}
                    {...props}
                    type={visible ? 'text' : 'password'}
                    className={cn(controlClass, 'mt-0 pr-11', className)}
                />
                <button
                    type="button"
                    onClick={() => setVisible((current) => !current)}
                    className="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-ink-muted hover:text-ink dark:text-ink-inverse/70"
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
        <div>
            {label && (
                <InputLabel htmlFor={id} required={required}>
                    {label}
                </InputLabel>
            )}
            <textarea id={id} required={required} className={cn(controlClass, 'min-h-24 py-2.5', className)} {...props} />
            <InputError message={error} />
        </div>
    );
}

export function DateInput(props: Omit<TextInputProps, 'type'>) {
    return <TextInput type="text" inputMode="numeric" placeholder="dd/mm/aaaa" {...props} />;
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
            <label htmlFor={id} className="inline-flex min-h-touch cursor-pointer items-center">
                <input type="hidden" name={name} value="0" />
                <input
                    id={id}
                    name={name}
                    type="checkbox"
                    value="1"
                    checked={checked}
                    onChange={(event) => onChange?.(event.target.checked)}
                    className="rounded border-line text-primary shadow-none focus:ring-primary dark:border-line-dark dark:bg-white/5"
                />
                <span className="ms-2 text-sm text-ink dark:text-ink-inverse">{label}</span>
            </label>
            <InputError message={error} />
        </div>
    );
}

interface SelectProps extends React.SelectHTMLAttributes<HTMLSelectElement> {
    label?: string;
    error?: string;
    options: Array<{ value: string | number; label: string }>;
    placeholder?: string;
}

export function Select({
    label,
    error,
    id,
    required,
    options,
    placeholder = 'Selecione...',
    className,
    ...props
}: SelectProps) {
    return (
        <div>
            {label && (
                <InputLabel htmlFor={id} required={required}>
                    {label}
                </InputLabel>
            )}
            <select id={id} required={required} className={cn(controlClass, className)} {...props}>
                {placeholder !== '' && <option value="">{placeholder}</option>}
                {options.map((option) => (
                    <option key={option.value} value={option.value}>
                        {option.label}
                    </option>
                ))}
            </select>
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
        <div>
            {label && <InputLabel required={required}>{label}</InputLabel>}
            <div className="mt-2 flex flex-wrap gap-x-4 gap-y-2">
                {options.map((option) => (
                    <label key={option.value} className="inline-flex min-h-touch cursor-pointer items-center">
                        <input
                            type="radio"
                            name={name}
                            value={option.value}
                            checked={value === option.value}
                            onChange={() => onChange?.(option.value)}
                            className="border-line text-primary focus:ring-primary dark:border-line-dark"
                        />
                        <span className="ms-2 text-sm text-ink dark:text-ink-inverse">{option.label}</span>
                    </label>
                ))}
            </div>
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
        <div>
            {label && (
                <InputLabel htmlFor={id} required={required}>
                    {label}
                </InputLabel>
            )}
            <input
                id={id}
                type="file"
                className={cn(
                    'mt-1.5 block w-full text-sm text-ink file:mr-4 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-primary-dark dark:text-ink-inverse',
                    className,
                )}
                {...props}
            />
            {helpText && <p className="mt-1.5 text-sm text-ink-muted dark:text-ink-inverse/60">{helpText}</p>}
            <InputError message={error} />
        </div>
    );
}
