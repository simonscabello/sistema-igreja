import { ChangeEvent, FormEvent, useState } from 'react';
import { Link, useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { Avatar } from '@/components/ui/Avatar';
import { PrimaryButton, SecondaryButton } from '@/components/ui/Button';
import { AddressFields } from '@/components/ui/AddressFields';
import { DateInput, FileInput, Select, TextInput } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { formatDateBr, route } from '@/utils';

const GENDER_OPTIONS = [
    { value: 'Masculino', label: 'Masculino' },
    { value: 'Feminino', label: 'Feminino' },
    { value: 'Outro', label: 'Outro' },
];

const MARITAL_STATUS_OPTIONS = [
    { value: 'Solteiro', label: 'Solteiro' },
    { value: 'Casado', label: 'Casado' },
    { value: 'Divorciado', label: 'Divorciado' },
    { value: 'Viúvo', label: 'Viúvo' },
];

interface Member {
    id: number;
    full_name: string;
    email: string | null;
    phone: string | null;
    mobile: string;
    gender: string;
    marital_status: string | null;
    birth_date: string | null;
    baptism_date: string | null;
    admission_date: string | null;
    wedding_date: string | null;
    zip_code: string;
    street: string | null;
    neighborhood: string | null;
    city: string | null;
    state: string | null;
    number: string | null;
    complement: string | null;
    foto_url?: string | null;
}

interface EditProps {
    member: Member;
}

function Edit({ member }: EditProps) {
    const [photoPreview, setPhotoPreview] = useState<string | null>(null);

    const { data, setData, post, processing, errors } = useForm({
        full_name: member.full_name,
        email: member.email ?? '',
        mobile: member.mobile,
        phone: member.phone ?? '',
        gender: member.gender,
        marital_status: member.marital_status ?? '',
        birth_date: member.birth_date ? formatDateBr(member.birth_date) : '',
        baptism_date: member.baptism_date ? formatDateBr(member.baptism_date) : '',
        admission_date: member.admission_date ? formatDateBr(member.admission_date) : '',
        wedding_date: member.wedding_date ? formatDateBr(member.wedding_date) : '',
        zip_code: member.zip_code,
        street: member.street ?? '',
        neighborhood: member.neighborhood ?? '',
        city: member.city ?? '',
        state: member.state ?? '',
        number: member.number ?? '',
        complement: member.complement ?? '',
        foto_perfil: null as File | null,
        _method: 'PUT',
    });

    const handlePhotoChange = (event: ChangeEvent<HTMLInputElement>) => {
        const file = event.target.files?.[0] ?? null;
        setData('foto_perfil', file);

        if (photoPreview) {
            URL.revokeObjectURL(photoPreview);
        }

        setPhotoPreview(file ? URL.createObjectURL(file) : null);
    };

    const handleAddressChange = (field: 'zip_code' | 'street' | 'neighborhood' | 'city' | 'state' | 'number' | 'complement', value: string) => {
        setData(field, value);
    };

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        post(route('members.update', member.id), { forceFormData: true });
    };

    const displayPhoto = photoPreview ?? member.foto_url ?? null;
    const hasErrors = Object.keys(errors).length > 0;

    return (
        <AppPage>
            <PageCard title="Editar Membro">
                {hasErrors && (
                    <Alert type="error" dismissible>
                        <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
                    <div className="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 w-full mb-2">
                        <div className="relative mx-auto sm:mx-0">
                            {displayPhoto ? (
                                <img
                                    src={displayPhoto}
                                    alt="Preview da foto"
                                    className="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-2 border-primary"
                                />
                            ) : (
                                <Avatar name={data.full_name} size="w-24 h-24 sm:w-32 sm:h-32" />
                            )}
                        </div>
                        <div className="text-center sm:text-left flex-1">
                            <FileInput
                                id="foto_perfil"
                                accept="image/jpeg,image/jpg,image/png"
                                onChange={handlePhotoChange}
                                helpText="Clique para alterar a foto (jpg, jpeg, png, até 5MB)"
                                error={errors.foto_perfil}
                            />
                        </div>
                    </div>

                    <hr className="border-neutral-medium mb-6" />

                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <TextInput
                            id="full_name"
                            label="Nome Completo"
                            value={data.full_name}
                            onChange={(event) => setData('full_name', event.target.value)}
                            placeholder="Digite o nome completo"
                            required
                            error={errors.full_name}
                        />
                        <TextInput
                            id="email"
                            label="Email"
                            type="email"
                            value={data.email}
                            onChange={(event) => setData('email', event.target.value)}
                            placeholder="Digite o email"
                            error={errors.email}
                        />
                        <TextInput
                            id="mobile"
                            label="Celular"
                            value={data.mobile}
                            onChange={(event) => setData('mobile', event.target.value)}
                            placeholder="Digite o celular"
                            required
                            error={errors.mobile}
                        />
                        <TextInput
                            id="phone"
                            label="Telefone"
                            value={data.phone}
                            onChange={(event) => setData('phone', event.target.value)}
                            placeholder="Digite o telefone"
                            error={errors.phone}
                        />
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <Select
                            id="gender"
                            label="Gênero"
                            value={data.gender}
                            onChange={(event) => setData('gender', event.target.value)}
                            options={GENDER_OPTIONS}
                            required
                            error={errors.gender}
                        />
                        <Select
                            id="marital_status"
                            label="Estado Civil"
                            value={data.marital_status}
                            onChange={(event) => setData('marital_status', event.target.value)}
                            options={MARITAL_STATUS_OPTIONS}
                            error={errors.marital_status}
                        />
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <DateInput
                            id="birth_date"
                            label="Data de Nascimento"
                            value={data.birth_date}
                            onChange={(event) => setData('birth_date', event.target.value)}
                            required
                            error={errors.birth_date}
                        />
                        <DateInput
                            id="baptism_date"
                            label="Data de Batismo"
                            value={data.baptism_date}
                            onChange={(event) => setData('baptism_date', event.target.value)}
                            error={errors.baptism_date}
                        />
                        <DateInput
                            id="admission_date"
                            label="Data de Admissão"
                            value={data.admission_date}
                            onChange={(event) => setData('admission_date', event.target.value)}
                            error={errors.admission_date}
                        />
                        <DateInput
                            id="wedding_date"
                            label="Data de Casamento"
                            value={data.wedding_date}
                            onChange={(event) => setData('wedding_date', event.target.value)}
                            error={errors.wedding_date}
                        />
                    </div>

                    <AddressFields
                        data={{
                            zip_code: data.zip_code,
                            street: data.street,
                            neighborhood: data.neighborhood,
                            city: data.city,
                            state: data.state,
                            number: data.number,
                            complement: data.complement,
                        }}
                        errors={errors}
                        onChange={handleAddressChange}
                    />

                    <div className="border-t border-neutral-medium mt-6 pt-6">
                        <div className="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <Link href={route('members.index')}>
                                <SecondaryButton type="button">Cancelar</SecondaryButton>
                            </Link>
                            <PrimaryButton type="submit" disabled={processing}>
                                Salvar
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
