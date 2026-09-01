import { FormEvent, useMemo, useState } from 'react';
import { Link, router, useForm } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { PrimaryButton, SecondaryButton } from '@/components/ui/Button';
import { AdvancedSelect } from '@/components/ui/AdvancedSelect';
import { TextInput } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';

interface Tag {
    id: number;
    name: string;
}

interface Song {
    id: number;
    name: string;
    key: string | null;
    youtube_link: string | null;
    spotify_link: string | null;
    lyrics_link: string | null;
    chords_link: string | null;
    tags: Tag[];
}

interface EditProps {
    song: Song;
    tags: Tag[];
}

function Edit({ song, tags }: EditProps) {
    const tagOptions = useMemo(
        () => tags.map((tag) => ({ value: tag.name, label: tag.name })),
        [tags],
    );

    const [newTags, setNewTags] = useState('');

    const { data, setData, processing, errors } = useForm({
        name: song.name,
        key: song.key ?? '',
        youtube_link: song.youtube_link ?? '',
        spotify_link: song.spotify_link ?? '',
        lyrics_link: song.lyrics_link ?? '',
        chords_link: song.chords_link ?? '',
        tags: song.tags.map((tag) => tag.name),
    });

    const handleSubmit = (event: FormEvent) => {
        event.preventDefault();

        const extraTags = newTags
            .split(',')
            .map((tag) => tag.trim())
            .filter(Boolean);

        const allTags = [...new Set([...data.tags.map(String), ...extraTags])];

        router.put(route('songs.update', song.id), { ...data, tags: allTags });
    };

    const hasErrors = Object.keys(errors).length > 0;

    return (
        <AppPage>
            <PageCard title="Editar Música">
                {hasErrors && (
                    <Alert type="error" dismissible>
                        <span className="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div className="md:col-span-2">
                            <TextInput
                                id="name"
                                label="Nome da Música"
                                value={data.name}
                                onChange={(event) => setData('name', event.target.value)}
                                placeholder="Digite o nome da música"
                                required
                                error={errors.name}
                            />
                        </div>
                        <TextInput
                            id="key"
                            label="Tonalidade"
                            value={data.key}
                            onChange={(event) => setData('key', event.target.value)}
                            placeholder="Ex: C, Dm, F#m"
                            maxLength={10}
                            error={errors.key}
                        />
                        <TextInput
                            id="youtube_link"
                            label="Link do YouTube"
                            type="url"
                            value={data.youtube_link}
                            onChange={(event) => setData('youtube_link', event.target.value)}
                            placeholder="https://youtube.com/watch?v=..."
                            error={errors.youtube_link}
                        />
                        <TextInput
                            id="spotify_link"
                            label="Link do Spotify"
                            type="url"
                            value={data.spotify_link}
                            onChange={(event) => setData('spotify_link', event.target.value)}
                            placeholder="https://open.spotify.com/track/..."
                            error={errors.spotify_link}
                        />
                        <TextInput
                            id="lyrics_link"
                            label="Link da Letra"
                            type="url"
                            value={data.lyrics_link}
                            onChange={(event) => setData('lyrics_link', event.target.value)}
                            placeholder="https://..."
                            error={errors.lyrics_link}
                        />
                        <TextInput
                            id="chords_link"
                            label="Link da Cifra"
                            type="url"
                            value={data.chords_link}
                            onChange={(event) => setData('chords_link', event.target.value)}
                            placeholder="https://..."
                            error={errors.chords_link}
                        />
                        <div className="md:col-span-2">
                            <AdvancedSelect
                                id="tags"
                                name="tags"
                                label="Tags"
                                options={tagOptions}
                                value={data.tags}
                                onChange={(values) => setData('tags', values.map(String))}
                                multiple
                                searchable
                                creatable
                                placeholder="Buscar tags..."
                                error={errors.tags}
                            />
                            <p className="mt-1 text-sm text-neutral-medium dark:text-gray-400">
                                Selecione tags existentes ou digite novas tags no campo abaixo.
                            </p>
                            <div className="mt-2">
                                <TextInput
                                    id="new_tags"
                                    value={newTags}
                                    onChange={(event) => setNewTags(event.target.value)}
                                    placeholder="Digite novas tags separadas por vírgula"
                                />
                            </div>
                        </div>
                    </div>

                    <div className="flex gap-4">
                        <Link href={route('songs.index')}>
                            <SecondaryButton type="button">Cancelar</SecondaryButton>
                        </Link>
                        <PrimaryButton type="submit" disabled={processing}>
                            Atualizar
                        </PrimaryButton>
                    </div>
                </form>
            </PageCard>
        </AppPage>
    );
}

Edit.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Edit;
