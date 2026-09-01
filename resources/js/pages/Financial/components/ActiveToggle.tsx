import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';

interface ActiveToggleProps {
    id?: string;
    checked: boolean;
    onChange: (checked: boolean) => void;
    label?: string;
}

export function ActiveToggle({ id, checked, onChange, label = 'Ativo' }: ActiveToggleProps) {
    return (
        <div className="inline-flex min-h-touch items-center gap-3">
            <Switch id={id} checked={checked} onCheckedChange={onChange} />
            {label && (
                <Label htmlFor={id} className="cursor-pointer font-medium">
                    {label}
                </Label>
            )}
        </div>
    );
}
