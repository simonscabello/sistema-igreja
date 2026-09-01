import { useCallback, useState } from 'react';

interface ViaCepResponse {
    erro?: boolean;
    logradouro?: string;
    bairro?: string;
    localidade?: string;
    uf?: string;
}

export function useViaCep() {
    const [loading, setLoading] = useState(false);

    const fetchAddress = useCallback(async (zipCode: string) => {
        const cep = zipCode.replace(/\D/g, '');

        if (cep.length !== 8) {
            return null;
        }

        setLoading(true);

        try {
            const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            const data: ViaCepResponse = await response.json();

            if (data.erro) {
                return null;
            }

            return {
                street: data.logradouro ?? '',
                neighborhood: data.bairro ?? '',
                city: data.localidade ?? '',
                state: data.uf ?? '',
            };
        } catch {
            return null;
        } finally {
            setLoading(false);
        }
    }, []);

    return { fetchAddress, loading };
}
