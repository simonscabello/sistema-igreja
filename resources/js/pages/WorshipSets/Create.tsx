import { FormEvent, useMemo } from 'react';
import { Link, useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { PrimaryButton, SecondaryButton } from '@/components/ui/Button';
import { AdvancedSelect, AdvancedSelectOption, OrderedSongList } from '@/components/ui/AdvancedSelect';
import { Select, TextInput, Textarea } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';

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
    singer: string;
    preacher: string;
    order_notes: string | null;
    observations: string | null;
    songs: Song[];
}

interface CreateProps {
    songs: Song[];
    clonedSet?: ClonedSet | null;
}

const PERIOD_OPTIONS = [
    { value: 'manha', label: 'Manhã' },
    { value: 'noite', label: 'Noite' },
];

function formatDateForInput(value: string | null | undefined): string {
    if (!value) {
        return '';
    }

    const match = value.match(/^(\d{4})-(\d{2})-(\d{2})/);
    return match ? `${match[1]}-${match[2]}-${match[3]}` : value;
}

function Create({ songs, clonedSet = null }: CreateProps) {
    const songOptions: AdvancedSelectOption[] = useMemo(
        () =>
            songs.map((song) => ({
                value: song.id,
                label: song.key ? `${song.name} (${song.key})` : song.name,
            })),
        [songs],
    );

    const initialSongIds = clonedSet?.songs?.map((song) => song.id) ?? [];
    const initialSongKeys = clonedSet?.songs?.reduce<Record<number, string>>((accumulator, song) => {
        if (song.pivot?.key_used) {
            accumulator[song.id] = song.pivot.key_used;
        }
        return accumulator;
    }, {}) ?? {};

    const { data, setData, post, processing, errors } = useForm({
        singer: clonedSet?.singer ?? '',
        preacher: clonedSet?.preacher ?? '',
        date: formatDateForInput(clonedSet?.date),
        period: clonedSet?.period ?? '',
        order_notes: clonedSet?.order_notes ?? '',
        observations: clonedSet?.observations ?? '',
        songs: initialSongIds as number[],
        song_keys: initialSongKeys as Record<number, string>,
    });

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

        setData({ ...data, songs: ids, song_keys: nextKeys });
    };

    const handleKeyChange = (songId: string | number, key: string) => {
        setData('song_keys', { ...data.song_keys, [songId]: key });
    };

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();
        post(route('worship-sets.store'));
    };

    const hasErrors = Object.keys(errors).length > 0;
    const title = clonedSet ? 'Clonar Repertório' : 'Novo Repertório';

    return (
        <AppPage>
            <PageCard title={title}>
                {hasErrors && (
                    <Alert type="error" dismissible>
                        <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <TextInput
                            id="singer"
                            label="Cantor"
                            value={data.singer}
                            onChange={(event) => setData('singer', event.target.value)}
                            required
                            autoFocus
                            error={errors.singer}
                        />
                        <TextInput
                            id="preacher"
                            label="Ministro"
                            value={data.preacher}
                            onChange={(event) => setData('preacher', event.target.value)}
                            required
                            error={errors.preacher}
                        />
                        <TextInput
                            id="date"
                            label="Data"
                            type="date"
                            value={data.date}
                            onChange={(event) => setData('date', event.target.value)}
                            required
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
                    </div>

                    <div>
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
                        <p className="mt-1 text-sm text-neutral-medium dark:text-gray-400">
                            A ordem de seleção será a ordem de execução no culto.
                        </p>
                        <OrderedSongList
                            items={selectedSongItems}
                            keys={data.song_keys}
                            onKeyChange={handleKeyChange}
                        />
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <Textarea
                            id="order_notes"
                            label="Ordem das Músicas / Observações"
                            value={data.order_notes}
                            onChange={(event) => setData('order_notes', event.target.value)}
                            rows={4}
                            placeholder="Observações sobre a ordem das músicas..."
                            error={errors.order_notes}
                        />
                        <Textarea
                            id="observations"
                            label="Observações Gerais"
                            value={data.observations}
                            onChange={(event) => setData('observations', event.target.value)}
                            rows={4}
                            placeholder="Observações gerais sobre o culto..."
                            error={errors.observations}
                        />
                    </div>

                    <div className="flex items-center justify-end gap-4">
                        <Link href={route('worship-sets.index')}>
                            <SecondaryButton type="button">Cancelar</SecondaryButton>
                        </Link>
                        <PrimaryButton type="submit" disabled={processing}>
                            {clonedSet ? 'Criar Repertório' : 'Cadastrar'}
                        </PrimaryButton>
                    </div>
                </form>
            </PageCard>
        </AppPage>
    );
}

Create.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Create;
