import { FormEvent, useMemo } from 'react';
import { Alert } from '@/components/ui/Alert';
import { CancelButton, SaveButton } from '@/components/ui/Button';
import { AdvancedSelect, AdvancedSelectOption, OrderedSongList } from '@/components/ui/AdvancedSelect';
import { DateInput, Select, Textarea } from '@/components/ui/Input';
import { FormActions, FormPanel, FormSection } from '@/components/ui/FormSection';
import { route } from '@/utils';
import { WorshipFunctionIcon } from '@/utils/worshipFunctionIcon';

interface Song {
    id: number;
    name: string;
    key: string | null;
    pivot?: {
        key_used: string | null;
        order: number;
    };
}

interface FunctionMember {
    id: number;
    name: string;
    image: string | null;
}

interface ScheduleFunction {
    id: number;
    name: string;
    slug: string;
    members: FunctionMember[];
}

export interface WorshipSetFormData {
    date: string;
    period: string;
    order_notes: string;
    observations: string;
    songs: number[];
    song_keys: Record<number, string>;
    assignments: Record<number, number[]>;
}

interface WorshipSetFormProps {
    title: string;
    submitLabel: string;
    cancelHref?: string;
    songs: Song[];
    functions: ScheduleFunction[];
    data: WorshipSetFormData;
    setData: (key: string, value: unknown) => void;
    setDataObject: (updater: (current: WorshipSetFormData) => WorshipSetFormData) => void;
    errors: Record<string, string>;
    processing: boolean;
    onSubmit: (event: FormEvent) => void;
}

const PERIOD_OPTIONS = [
    { value: 'manha', label: 'Manhã' },
    { value: 'noite', label: 'Noite' },
];

export function WorshipSetForm({
    title,
    submitLabel,
    cancelHref = route('worship-sets.index'),
    songs,
    functions,
    data,
    setData,
    setDataObject,
    errors,
    processing,
    onSubmit,
}: WorshipSetFormProps) {
    const songOptions: AdvancedSelectOption[] = useMemo(
        () =>
            songs.map((song) => ({
                value: song.id,
                label: song.key ? `${song.name} (${song.key})` : song.name,
            })),
        [songs],
    );

    const selectedSongItems = data.songs
        .map((songId) => songOptions.find((option) => option.value === songId))
        .filter((item): item is AdvancedSelectOption => Boolean(item));

    const handleSongsChange = (values: Array<string | number>) => {
        const ids = values.map(Number);
        const nextKeys = { ...data.song_keys };

        Object.keys(nextKeys).forEach((key) => {
            if (!ids.includes(Number(key))) {
                delete nextKeys[Number(key)];
            }
        });

        setDataObject((current) => ({ ...current, songs: ids, song_keys: nextKeys }));
    };

    const handleKeyChange = (songId: string | number, key: string) => {
        setData('song_keys', { ...data.song_keys, [songId]: key });
    };

    const handleAssignmentChange = (functionId: number, values: Array<string | number>) => {
        setData('assignments', {
            ...data.assignments,
            [functionId]: values.map(Number),
        });
    };

    const hasErrors = Object.keys(errors).length > 0;

    return (
        <>
            {hasErrors && (
                <Alert type="error" dismissible>
                    <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                </Alert>
            )}

            <form onSubmit={onSubmit} className="space-y-6">
                <FormPanel>
                    <FormSection title="Culto" description="Data e período do culto." columns={2}>
                        <DateInput
                            id="date"
                            label="Data"
                            output="iso"
                            value={data.date}
                            onChange={(event) => setData('date', event.target.value)}
                            required
                            autoFocus
                            error={errors.date}
                        />
                        <Select
                            id="period"
                            label="Período"
                            value={data.period}
                            onChange={(event) => setData('period', event.target.value)}
                            options={PERIOD_OPTIONS}
                            placeholder="Selecione..."
                            required
                            error={errors.period}
                        />
                    </FormSection>
                </FormPanel>

                <FormPanel>
                    <FormSection title="Escala" description="Escolha quem vai servir em cada função neste culto." columns={1}>
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                            {functions.map((worshipFunction) => {
                                const memberOptions: AdvancedSelectOption[] = worshipFunction.members.map((member) => ({
                                    value: member.id,
                                    label: member.name,
                                    image: member.image,
                                }));

                                const assignmentError = errors[`assignments.${worshipFunction.id}`];

                                return (
                                    <div key={worshipFunction.id}>
                                        <AdvancedSelect
                                            id={`assignment-${worshipFunction.id}`}
                                            name={`assignments[${worshipFunction.id}]`}
                                            label={worshipFunction.name}
                                            labelIcon={
                                                <span className="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                                    <WorshipFunctionIcon slug={worshipFunction.slug} />
                                                </span>
                                            }
                                            options={memberOptions}
                                            value={data.assignments[worshipFunction.id] ?? []}
                                            onChange={(values) => handleAssignmentChange(worshipFunction.id, values)}
                                            multiple
                                            searchable
                                            imageField
                                            placeholder={
                                                memberOptions.length > 0
                                                    ? `Selecionar ${worshipFunction.name.toLowerCase()}...`
                                                    : 'Nenhum membro cadastrado'
                                            }
                                            error={assignmentError}
                                        />
                                        {memberOptions.length === 0 && (
                                            <p className="mt-1 text-sm text-muted-foreground">
                                                Cadastre membros em{' '}
                                                <Link
                                                    href={route('worship-functions.edit', worshipFunction.id)}
                                                    className="font-medium text-primary hover:underline"
                                                >
                                                    Funções
                                                </Link>
                                                .
                                            </p>
                                        )}
                                    </div>
                                );
                            })}
                        </div>
                    </FormSection>
                </FormPanel>

                <FormPanel>
                    <FormSection title="Repertório" description="Músicas e tonalidades para este culto." columns={1}>
                        <AdvancedSelect
                            id="songs"
                            name="songs"
                            label="Músicas"
                            options={songOptions}
                            value={data.songs}
                            onChange={handleSongsChange}
                            multiple
                            searchable
                            placeholder="Buscar músicas..."
                            error={errors.songs}
                        />
                        <p className="text-sm text-muted-foreground">A ordem de seleção será a ordem de execução no culto.</p>
                        <OrderedSongList items={selectedSongItems} keys={data.song_keys} onKeyChange={handleKeyChange} />
                    </FormSection>
                </FormPanel>

                <FormPanel>
                    <FormSection title="Observações" columns={2}>
                        <Textarea
                            id="order_notes"
                            label="Ordem das músicas / observações"
                            value={data.order_notes}
                            onChange={(event) => setData('order_notes', event.target.value)}
                            rows={4}
                            placeholder="Observações sobre a ordem das músicas..."
                            error={errors.order_notes}
                        />
                        <Textarea
                            id="observations"
                            label="Observações gerais"
                            value={data.observations}
                            onChange={(event) => setData('observations', event.target.value)}
                            rows={4}
                            placeholder="Observações gerais sobre o culto..."
                            error={errors.observations}
                        />
                    </FormSection>
                </FormPanel>

                <FormActions>
                    <CancelButton href={cancelHref} />
                    <SaveButton processing={processing}>{submitLabel}</SaveButton>
                </FormActions>
            </form>
        </>
    );
}

export function formatDateForInput(value: string | null | undefined): string {
    if (!value) {
        return '';
    }

    const match = value.match(/^(\d{4})-(\d{2})-(\d{2})/);
    return match ? `${match[1]}-${match[2]}-${match[3]}` : value;
}

export function buildInitialAssignments(
    functions: ScheduleFunction[],
    existingAssignments?: Array<{
        worship_function_id: number;
        member_id: number;
        order: number;
    }>,
): Record<number, number[]> {
    const assignments: Record<number, number[]> = {};

    functions.forEach((worshipFunction) => {
        assignments[worshipFunction.id] = [];
    });

    if (!existingAssignments) {
        return assignments;
    }

    const sorted = [...existingAssignments].sort((left, right) => left.order - right.order);

    sorted.forEach((assignment) => {
        if (!assignments[assignment.worship_function_id]) {
            assignments[assignment.worship_function_id] = [];
        }

        assignments[assignment.worship_function_id].push(assignment.member_id);
    });

    return assignments;
}
