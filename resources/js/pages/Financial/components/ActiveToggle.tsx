interface ActiveToggleProps {
    id?: string;
    checked: boolean;
    onChange: (checked: boolean) => void;
    label?: string;
}

export function ActiveToggle({ id, checked, onChange, label = 'Ativo' }: ActiveToggleProps) {
    return (
        <label htmlFor={id} className="inline-flex items-center cursor-pointer">
            <input
                id={id}
                type="checkbox"
                className="sr-only peer"
                checked={checked}
                onChange={(event) => onChange(event.target.checked)}
            />
            <div
                className="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary dark:peer-checked:bg-primary"
            />
            {label && <span className="ms-3 text-sm font-medium text-neutral-dark dark:text-gray-300">{label}</span>}
        </label>
    );
}
