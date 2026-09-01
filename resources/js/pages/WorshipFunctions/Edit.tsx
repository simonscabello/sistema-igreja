import { FormEvent, useMemo } from 'react';
import { useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { CancelButton, SaveButton } from '@/components/ui/Button';
import { AdvancedSelect, AdvancedSelectOption } from '@/components/ui/AdvancedSelect';
import { Checkbox, TextInput } from '@/components/ui/Input';
import { FormActions, FormPanel } from '@/components/ui/FormSection';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';

interface Member {
    id: number;
    full_name: string;
}

interface WorshipFunction {
    id: number;
    name: string;
    sort_order: number;
    is_active: boolean;
    members: Member[];
}

interface MemberOption {
    id: number;
    name: string;
    image: string | null;
}

type MemberOptionsInput = MemberOption[] | Record<string | number, { name: string; image: string | null }>;

interface EditProps {
    worshipFunction: WorshipFunction;
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

function Edit({ worshipFunction, memberOptions }: EditProps) {
    const options = useMemo(() => normalizeMemberOptions(memberOptions), [memberOptions]);

    const { data, setData, put, processing, errors } = useForm({
        name: worshipFunction.name,
        sort_order: worshipFunction.sort_order,
        is_active: worshipFunction.is_active,
        members: worshipFunction.members.map((member) => member.id),
    });

    const handleMembersChange = (values: Array<string | number>) => {
        setData('members', values.map(Number));
    };

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        put(route('worship-functions.update', worshipFunction.id));
    };

    const hasErrors = Object.keys(errors).length > 0;

    return (
        <AppPage>
            <PageCard title="Editar Função">
                {hasErrors && (
                    <Alert type="error" dismissible>
                        <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
                    <FormPanel>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <TextInput
                                id="name"
                                label="Nome"
                                value={data.name}
                                onChange={(event) => setData('name', event.target.value)}
                                placeholder="Ex.: Vocal, Violão..."
                                required
                                autoFocus
                                error={errors.name}
                            />
                            <TextInput
                                id="sort_order"
                                label="Ordem"
                                type="number"
                                min={0}
                                value={data.sort_order}
                                onChange={(event) => setData('sort_order', Number(event.target.value))}
                                error={errors.sort_order}
                            />
                            <Checkbox
                                id="is_active"
                                label="Ativo"
                                checked={data.is_active}
                                onChange={(checked) => setData('is_active', checked)}
                                error={errors.is_active}
                            />
                        </div>

                        <AdvancedSelect
                            id="members"
                            name="members"
                            label="Membros que podem exercer esta função"
                            options={options}
                            value={data.members}
                            onChange={handleMembersChange}
                            multiple
                            searchable
                            imageField
                            placeholder="Buscar membros..."
                            error={errors.members}
                        />
                    </FormPanel>

                    <FormActions>
                        <CancelButton href={route('worship-functions.index')} />
                        <SaveButton processing={processing}>Atualizar</SaveButton>
                    </FormActions>
                </form>
            </PageCard>
        </AppPage>
    );
}

Edit.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Edit;
