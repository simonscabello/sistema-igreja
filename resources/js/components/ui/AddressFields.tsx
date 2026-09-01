import { useViaCep } from '@/hooks/useViaCep';
import { TextInput } from './Input';

interface AddressFieldsProps {
    data: {
        zip_code?: string;
        street?: string;
        neighborhood?: string;
        city?: string;
        state?: string;
        number?: string;
        complement?: string;
    };
    errors?: Record<string, string>;
    onChange: (field: string, value: string) => void;
}

export function AddressFields({ data, errors = {}, onChange }: AddressFieldsProps) {
    const { fetchAddress, loading } = useViaCep();

    const handleZipBlur = async () => {
        const address = await fetchAddress(data.zip_code ?? '');

        if (!address) {
            onChange('street', '');
            onChange('neighborhood', '');
            onChange('city', '');
            onChange('state', '');
            return;
        }

        onChange('street', address.street);
        onChange('neighborhood', address.neighborhood);
        onChange('city', address.city);
        onChange('state', address.state);
    };

    return (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <TextInput
                id="zip_code"
                name="zip_code"
                label="CEP"
                value={data.zip_code ?? ''}
                onChange={(event) => onChange('zip_code', event.target.value)}
                onBlur={handleZipBlur}
                error={errors.zip_code}
                required
            />
            <TextInput
                id="street"
                name="street"
                label="Rua"
                value={data.street ?? ''}
                onChange={(event) => onChange('street', event.target.value)}
                error={errors.street}
            />
            <TextInput
                id="number"
                name="number"
                label="Número"
                value={data.number ?? ''}
                onChange={(event) => onChange('number', event.target.value)}
                error={errors.number}
            />
            <TextInput
                id="complement"
                name="complement"
                label="Complemento"
                value={data.complement ?? ''}
                onChange={(event) => onChange('complement', event.target.value)}
                error={errors.complement}
            />
            <TextInput
                id="neighborhood"
                name="neighborhood"
                label="Bairro"
                value={data.neighborhood ?? ''}
                onChange={(event) => onChange('neighborhood', event.target.value)}
                error={errors.neighborhood}
            />
            <TextInput
                id="city"
                name="city"
                label="Cidade"
                value={data.city ?? ''}
                onChange={(event) => onChange('city', event.target.value)}
                error={errors.city}
            />
            <TextInput
                id="state"
                name="state"
                label="Estado"
                value={data.state ?? ''}
                onChange={(event) => onChange('state', event.target.value)}
                error={errors.state}
            />
            {loading && <p className="text-sm text-ink-muted md:col-span-2">Buscando CEP…</p>}
        </div>
    );
}
