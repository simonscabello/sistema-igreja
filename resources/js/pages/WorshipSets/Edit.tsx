import { FormEvent } from 'react';
import { useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';
import { WorshipSetForm, buildInitialAssignments, formatDateForInput } from './components/WorshipSetForm';

interface Song {
    id: number;
    name: string;
    key: string | null;
    pivot?: {
        key_used: string | null;
        order: number;
    };
}

interface Assignment {
    worship_function_id: number;
    member_id: number;
    order: number;
    worship_function?: {
        id: number;
        name: string;
    };
    member?: {
        id: number;
        full_name: string;
    };
}

interface WorshipSet {
    id: number;
    date: string;
    period: 'manha' | 'noite';
    order_notes: string | null;
    observations: string | null;
    songs: Song[];
    assignments: Assignment[];
}

interface ScheduleFunction {
    id: number;
    name: string;
    members: Array<{
        id: number;
        name: string;
        image: string | null;
    }>;
}

interface EditProps {
    worshipSet: WorshipSet;
    songs: Song[];
    functions: ScheduleFunction[];
}

function Edit({ worshipSet, songs, functions }: EditProps) {
    const initialSongIds = worshipSet.songs.map((song) => song.id);
    const initialSongKeys = worshipSet.songs.reduce<Record<number, string>>((accumulator, song) => {
        if (song.pivot?.key_used) {
            accumulator[song.id] = song.pivot.key_used;
        }
        return accumulator;
    }, {});

    const { data, setData, put, processing, errors } = useForm({
        date: formatDateForInput(worshipSet.date),
        period: worshipSet.period,
        order_notes: worshipSet.order_notes ?? '',
        observations: worshipSet.observations ?? '',
        songs: initialSongIds,
        song_keys: initialSongKeys,
        assignments: buildInitialAssignments(
            functions,
            worshipSet.assignments.map((assignment) => ({
                worship_function_id: assignment.worship_function_id,
                member_id: assignment.member_id,
                order: assignment.order,
            })),
        ),
    });

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        put(route('worship-sets.update', worshipSet.id));
    };

    return (
        <AppPage>
            <PageCard title="Editar culto">
                <WorshipSetForm
                    title="Editar culto"
                    submitLabel="Atualizar"
                    songs={songs}
                    functions={functions}
                    data={data}
                    setData={setData}
                    setDataObject={(updater) => setData(updater(data))}
                    errors={errors}
                    processing={processing}
                    onSubmit={handleSubmit}
                />
            </PageCard>
        </AppPage>
    );
}

Edit.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Edit;
