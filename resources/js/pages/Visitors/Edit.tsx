import { FormEvent } from 'react';
import { Link, useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { PrimaryButton, SecondaryButton } from '@/components/ui/Button';
import { Checkbox, DateInput, Select, TextInput, Textarea } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { formatDateBr, route } from '@/utils';

const AGE_GROUP_OPTIONS = [
    { value: 'crianca_adolescente', label: 'Criança / Adolescente' },
    { value: 'jovem', label: 'Jovem' },
    { value: 'adulto', label: 'Adulto' },
    { value: 'idoso', label: 'Idoso' },
];

const GENDER_OPTIONS = [
    { value: 'feminino', label: 'Feminino' },
    { value: 'masculino', label: 'Masculino' },
];

interface Visitor {
    id: number;
    name: string;
    mobile: string;
    age_group: string | null;
    gender: string | null;
    visit_date: string | null;
    wants_contact: boolean;
    notes: string | null;
}

interface EditProps {
    visitor: Visitor;
}

function Edit({ visitor }: EditProps) {
    const { data, setData, put, processing, errors } = useForm({
        name: visitor.name,
        mobile: visitor.mobile ?? '',
        age_group: visitor.age_group ?? '',
        gender: visitor.gender ?? '',
        visit_date: formatDateBr(visitor.visit_date) === '-' ? '' : formatDateBr(visitor.visit_date),
        wants_contact: visitor.wants_contact,
        notes: visitor.notes ?? '',
    });

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        put(route('visitors.update', visitor.id));
    };

    const hasErrors = Object.keys(errors).length > 0;

    return (
        <AppPage>
            <PageCard title="Editar Visitante">
                {hasErrors && (
                    <Alert type="error" dismissible>
                        <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <TextInput
                            id="name"
                            label="Nome"
                            value={data.name}
                            onChange={(event) => setData('name', event.target.value)}
                            placeholder="Digite o nome"
                            required
                            error={errors.name}
                        />
                        <TextInput
                            id="mobile"
                            label="Celular"
                            value={data.mobile}
                            onChange={(event) => setData('mobile', event.target.value)}
                            placeholder="Digite o celular"
                            error={errors.mobile}
                        />
                        <Select
                            id="age_group"
                            label="Faixa Etária"
                            value={data.age_group}
                            onChange={(event) => setData('age_group', event.target.value)}
                            options={AGE_GROUP_OPTIONS}
                            error={errors.age_group}
                        />
                        <Select
                            id="gender"
                            label="Gênero"
                            value={data.gender}
                            onChange={(event) => setData('gender', event.target.value)}
                            options={GENDER_OPTIONS}
                            error={errors.gender}
                        />
                        <DateInput
                            id="visit_date"
                            label="Data da Visita"
                            value={data.visit_date}
                            onChange={(event) => setData('visit_date', event.target.value)}
                            error={errors.visit_date}
                        />
                        <Checkbox
                            id="wants_contact"
                            label="Deseja ser contactado?"
                            checked={data.wants_contact}
                            onChange={(checked) => setData('wants_contact', checked)}
                            error={errors.wants_contact}
                        />
                    </div>

                    <div className="space-y-4">
                        <Textarea
                            id="notes"
                            label="Observações"
                            value={data.notes}
                            onChange={(event) => setData('notes', event.target.value)}
                            placeholder="Digite observações sobre o visitante"
                            error={errors.notes}
                        />
                    </div>

                    <div className="border-t border-neutral-medium mt-6 pt-6">
                        <div className="flex gap-4">
                            <Link href={route('visitors.index')}>
                                <SecondaryButton type="button">Cancelar</SecondaryButton>
                            </Link>
                            <PrimaryButton type="submit" disabled={processing}>
                                Atualizar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </PageCard>
        </AppPage>
    );
}

Edit.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Edit;
