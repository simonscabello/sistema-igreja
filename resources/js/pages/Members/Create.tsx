import { ChangeEvent, FormEvent, useState } from 'react';
import { Link, useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { Avatar } from '@/components/ui/Avatar';
import { PrimaryButton, SecondaryButton } from '@/components/ui/Button';
import { AddressFields } from '@/components/ui/AddressFields';
import { DateInput, FileInput, Select, TextInput } from '@/components/ui/Input';
import { FormActions, FormPanel, FormSection } from '@/components/ui/FormSection';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';

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

function Create() {
    const [photoPreview, setPhotoPreview] = useState<string | null>(null);

    const { data, setData, post, processing, errors } = useForm({
        full_name: '',
        email: '',
        mobile: '',
        phone: '',
        gender: '',
        marital_status: '',
        birth_date: '',
        baptism_date: '',
        admission_date: '',
        wedding_date: '',
        zip_code: '',
        street: '',
        neighborhood: '',
        city: '',
        state: '',
        number: '',
        complement: '',
        foto_perfil: null as File | null,
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
        post(route('members.store'), { forceFormData: true });
    };

    const hasErrors = Object.keys(errors).length > 0;

    return (
        <AppPage>
            <PageCard
                title="Novo membro"
                description="Dados para o cadastro pastoral. Celular, gênero, nascimento e CEP são obrigatórios."
                breadcrumbs={[{ label: 'Membros', href: route('members.index') }, { label: 'Novo' }]}
            >
                {hasErrors && (
                    <Alert type="error" dismissible>
                        Corrija os campos destacados antes de salvar.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-8">
                    <FormPanel>
                        <div className="mb-6 flex flex-col items-center gap-4 sm:flex-row">
                            {photoPreview ? (
                                <img
                                    src={photoPreview}
                                    alt="Preview da foto"
                                    className="h-24 w-24 rounded-full object-cover sm:h-28 sm:w-28"
                                />
                            ) : (
                                <Avatar name={data.full_name || 'Membro'} size="w-24 h-24 sm:w-28 sm:h-28" />
                            )}
                            <FileInput
                                id="foto_perfil"
                                label="Foto"
                                accept="image/jpeg,image/jpg,image/png"
                                onChange={handlePhotoChange}
                                helpText="JPG ou PNG, até 5 MB."
                                error={errors.foto_perfil}
                            />
                        </div>

                        <div className="space-y-8">
                            <FormSection title="Identificação" description="Como a pessoa é encontrada na secretaria.">
                                <TextInput
                                    id="full_name"
                                    label="Nome completo"
                                    value={data.full_name}
                                    onChange={(event) => setData('full_name', event.target.value)}
                                    required
                                    error={errors.full_name}
                                />
                                <TextInput
                                    id="email"
                                    label="E-mail"
                                    type="email"
                                    value={data.email}
                                    onChange={(event) => setData('email', event.target.value)}
                                    error={errors.email}
                                />
                                <TextInput
                                    id="mobile"
                                    label="Celular"
                                    value={data.mobile}
                                    onChange={(event) => setData('mobile', event.target.value)}
                                    required
                                    error={errors.mobile}
                                />
                                <TextInput
                                    id="phone"
                                    label="Telefone"
                                    value={data.phone}
                                    onChange={(event) => setData('phone', event.target.value)}
                                    error={errors.phone}
                                />
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
                                    label="Estado civil"
                                    value={data.marital_status}
                                    onChange={(event) => setData('marital_status', event.target.value)}
                                    options={MARITAL_STATUS_OPTIONS}
                                    error={errors.marital_status}
                                />
                            </FormSection>

                            <FormSection title="Datas" columns={4} description="Nascimento é obrigatório. As demais são opcionais.">
                                <DateInput
                                    id="birth_date"
                                    label="Nascimento"
                                    value={data.birth_date}
                                    onChange={(event) => setData('birth_date', event.target.value)}
                                    required
                                    error={errors.birth_date}
                                />
                                <DateInput
                                    id="baptism_date"
                                    label="Batismo"
                                    value={data.baptism_date}
                                    onChange={(event) => setData('baptism_date', event.target.value)}
                                    error={errors.baptism_date}
                                />
                                <DateInput
                                    id="admission_date"
                                    label="Admissão"
                                    value={data.admission_date}
                                    onChange={(event) => setData('admission_date', event.target.value)}
                                    error={errors.admission_date}
                                />
                                <DateInput
                                    id="wedding_date"
                                    label="Casamento"
                                    value={data.wedding_date}
                                    onChange={(event) => setData('wedding_date', event.target.value)}
                                    error={errors.wedding_date}
                                />
                            </FormSection>

                            <FormSection title="Endereço" columns={1}>
                                <div className="sm:col-span-2">
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
                                </div>
                            </FormSection>
                        </div>
                    </FormPanel>

                    <FormActions>
                        <Link href={route('members.index')}>
                            <SecondaryButton type="button">Cancelar</SecondaryButton>
                        </Link>
                        <PrimaryButton type="submit" processing={processing}>
                            Salvar membro
                        </PrimaryButton>
                    </FormActions>
                </form>
            </PageCard>
        </AppPage>
    );
}

Create.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Create;
