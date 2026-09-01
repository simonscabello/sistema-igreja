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

interface ClonedSet {
    date: string;
    period: 'manha' | 'noite';
    order_notes: string | null;
    observations: string | null;
    songs: Song[];
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

interface CreateProps {
    songs: Song[];
    functions: ScheduleFunction[];
    clonedSet?: ClonedSet | null;
}

function Create({ songs, functions, clonedSet = null }: CreateProps) {
    const initialSongIds = clonedSet?.songs?.map((song) => song.id) ?? [];
    const initialSongKeys =
        clonedSet?.songs?.reduce<Record<number, string>>((accumulator, song) => {
            if (song.pivot?.key_used) {
                accumulator[song.id] = song.pivot.key_used;
            }
            return accumulator;
        }, {}) ?? {};

    const { data, setData, post, processing, errors } = useForm({
        date: formatDateForInput(clonedSet?.date),
        period: clonedSet?.period ?? '',
        order_notes: clonedSet?.order_notes ?? '',
        observations: clonedSet?.observations ?? '',
        songs: initialSongIds as number[],
        song_keys: initialSongKeys as Record<number, string>,
        assignments: buildInitialAssignments(functions),
    });

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        post(route('worship-sets.store'));
    };

    const title = clonedSet ? 'Clonar culto' : 'Novo culto';

    return (
        <AppPage>
            <PageCard title={title}>
                <WorshipSetForm
                    title={title}
                    submitLabel={clonedSet ? 'Criar culto' : 'Cadastrar'}
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

Create.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Create;
