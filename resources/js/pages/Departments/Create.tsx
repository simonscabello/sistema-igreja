import { FormEvent, useMemo } from 'react';
import { Link, useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { PrimaryButton, SecondaryButton } from '@/components/ui/Button';
import { AdvancedSelect, AdvancedSelectOption } from '@/components/ui/AdvancedSelect';
import { Checkbox, TextInput, Textarea } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { useDisjointSelection } from '@/hooks';
import { route } from '@/utils';

interface MemberOption {
    id: number;
    name: string;
    image: string | null;
}

type MemberOptionsInput = MemberOption[] | Record<string | number, { name: string; image: string | null }>;

interface CreateProps {
    memberOptions: MemberOptionsInput;
}

function normalizeMemberOptions(input: MemberOptionsInput): AdvancedSelectOption[] {
    if (Array.isArray(input)) {
        return input.map((member) => ({
            value: member.id,
            label: member.name,
            image: member.image,
        }));
    }

    return Object.entries(input).map(([id, member]) => ({
        value: Number(id),
        label: member.name,
        image: member.image,
    }));
}

function Create({ memberOptions }: CreateProps) {
    const options = useMemo(() => normalizeMemberOptions(memberOptions), [memberOptions]);

    const { data, setData, post, processing, errors } = useForm({
        title: '',
        description: '',
        is_active: true,
        responsible_members: [] as number[],
        members: [] as number[],
    });

    const { selectedA, selectedB, updateA, updateB, excludedForA, excludedForB } = useDisjointSelection<number>(
        data.responsible_members,
        data.members,
    );

    const handleResponsibleChange = (values: Array<string | number>) => {
        const ids = values.map(Number);
        updateA(ids);
        setData((current) => ({ ...current, responsible_members: ids }));
    };

    const handleMembersChange = (values: Array<string | number>) => {
        const ids = values.map(Number);
        updateB(ids);
        setData((current) => ({ ...current, members: ids }));
    };

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        post(route('departments.store'));
    };

    const hasErrors = Object.keys(errors).length > 0;

    return (
        <AppPage>
            <PageCard title="Novo Departamento">
                {hasErrors && (
                    <Alert type="error" dismissible>
                        <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <TextInput
                            id="title"
                            label="Título"
                            value={data.title}
                            onChange={(event) => setData('title', event.target.value)}
                            placeholder="Digite o título do departamento"
                            required
                            error={errors.title}
                        />
                        <Checkbox
                            id="is_active"
                            label="Ativo"
                            checked={data.is_active}
                            onChange={(checked) => setData('is_active', checked)}
                            error={errors.is_active}
                        />
                    </div>

                    <Textarea
                        id="description"
                        label="Descrição"
                        value={data.description}
                        onChange={(event) => setData('description', event.target.value)}
                        placeholder="Digite a descrição do departamento"
                        error={errors.description}
                    />

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <AdvancedSelect
                            id="responsible_members"
                            name="responsible_members"
                            label="Líderes"
                            options={options}
                            value={selectedA}
                            onChange={handleResponsibleChange}
                            excludedIds={excludedForA}
                            multiple
                            searchable
                            imageField
                            placeholder="Buscar líderes..."
                            error={errors.responsible_members}
                        />
                        <AdvancedSelect
                            id="members"
                            name="members"
                            label="Membros"
                            options={options}
                            value={selectedB}
                            onChange={handleMembersChange}
                            excludedIds={excludedForB}
                            multiple
                            searchable
                            imageField
                            placeholder="Buscar membros..."
                            error={errors.members}
                        />
                    </div>

                    <div className="border-t border-neutral-medium mt-6 pt-6">
                        <div className="flex gap-4">
                            <Link href={route('departments.index')}>
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

Create.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Create;
