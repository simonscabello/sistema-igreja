import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton } from '@/components/ui/Button';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { Select } from '@/components/ui/Input';
import { route } from '@/utils';
import { Paginated } from '@/types';

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

interface IndexProps {
    songs: Paginated<Song>;
    tags: Tag[];
    filters?: {
        search?: string;
        tag?: string;
    };
}

function Index({ songs, tags, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const [tag, setTag] = useState(filters.tag ?? '');

    const tagOptions = [
        { value: '', label: 'Todas as tags' },
        ...tags.map((item) => ({ value: String(item.id), label: item.name })),
    ];

    const handleSearch = () => {
        router.get(
            route('songs.index'),
            { search: search || undefined, tag: tag || undefined },
            { preserveState: true, replace: true },
        );
    };

    return (
        <AppPage>
            <PageCard
                title="Músicas"
                description="Catálogo de louvor. Use as tags para achar o que entra no culto."
                actions={route('songs.create')}
                actionsLabel="Nova música"
            >
                <div className="flex flex-col sm:flex-row sm:items-end mb-4 gap-3">
                    <SearchForm
                        action={route('songs.index')}
                        placeholder="Buscar músicas..."
                        value={search}
                        onChange={setSearch}
                        onSubmit={handleSearch}
                    />
                    <div className="w-full sm:w-48">
                        <Select
                            id="tag"
                            value={tag}
                            onChange={(event) => setTag(event.target.value)}
                            options={tagOptions}
                            placeholder=""
                        />
                    </div>
                    <button
                        type="button"
                        onClick={handleSearch}
                        className="inline-flex items-center justify-center px-4 py-3 text-sm bg-primary text-white rounded-md hover:bg-primary-dark"
                    >
                        Buscar
                    </button>
                </div>

                <div className="hidden lg:block overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table className="min-w-full divide-y divide-neutral-medium dark:divide-gray-700">
                        <thead className="bg-neutral-light dark:bg-gray-700">
                            <tr>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Nome</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Tonalidade</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Tags</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Links</th>
                                <th className="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                            {songs.data.length === 0 ? (
                                <tr>
                                    <td colSpan={5} className="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                        Nenhuma música encontrada.
                                    </td>
                                </tr>
                            ) : (
                                songs.data.map((song) => (
                                    <tr key={song.id} className="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td className="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300 font-medium">{song.name}</td>
                                        <td className="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                            {song.key ? (
                                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                                    {song.key}
                                                </span>
                                            ) : (
                                                <span className="text-neutral-medium dark:text-gray-500">-</span>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            {song.tags.length > 0 ? (
                                                <div className="flex flex-wrap gap-1">
                                                    {song.tags.slice(0, 3).map((item) => (
                                                        <span key={item.id} className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                                            {item.name}
                                                        </span>
                                                    ))}
                                                    {song.tags.length > 3 && (
                                                        <span className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                                            +{song.tags.length - 3}
                                                        </span>
                                                    )}
                                                </div>
                                            ) : (
                                                <span className="text-neutral-medium dark:text-gray-500">-</span>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="flex gap-2">
                                                {song.youtube_link && (
                                                    <a href={song.youtube_link} target="_blank" rel="noreferrer" className="text-red-600 hover:text-red-800 dark:text-red-400" title="YouTube">
                                                        YT
                                                    </a>
                                                )}
                                                {song.spotify_link && (
                                                    <a href={song.spotify_link} target="_blank" rel="noreferrer" className="text-green-600 hover:text-green-800 dark:text-green-400" title="Spotify">
                                                        SP
                                                    </a>
                                                )}
                                                {song.lyrics_link && (
                                                    <a href={song.lyrics_link} target="_blank" rel="noreferrer" className="text-blue-600 hover:text-blue-800 dark:text-blue-400" title="Letra">
                                                        LT
                                                    </a>
                                                )}
                                                {song.chords_link && (
                                                    <a href={song.chords_link} target="_blank" rel="noreferrer" className="text-purple-600 hover:text-purple-800 dark:text-purple-400" title="Cifra">
                                                        CF
                                                    </a>
                                                )}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div className="flex justify-end gap-2">
                                                <LinkButton href={route('songs.show', song.id)} className="text-xs px-3 py-1">
                                                    Ver
                                                </LinkButton>
                                                <LinkButton href={route('songs.edit', song.id)} className="text-xs px-3 py-1">
                                                    Editar
                                                </LinkButton>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>

                <div className="space-y-3 lg:hidden">
                    {songs.data.length === 0 ? (
                        <p className="py-8 text-center text-sm text-ink-muted">Nenhuma música encontrada.</p>
                    ) : (
                        songs.data.map((song) => (
                            <div key={song.id} className="rounded-xl border border-line bg-surface p-4 dark:border-line-dark dark:bg-surface-dark">
                                <p className="font-semibold">{song.name}</p>
                                {song.key && <p className="mt-1 text-sm text-ink-muted">Tom {song.key}</p>}
                                <div className="mt-3 flex gap-3">
                                    <LinkButton href={route('songs.show', song.id)}>Ver</LinkButton>
                                    <LinkButton href={route('songs.edit', song.id)}>Editar</LinkButton>
                                </div>
                            </div>
                        ))
                    )}
                </div>

                <Pagination paginator={songs} />
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
