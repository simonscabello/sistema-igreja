import { Link } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton, SecondaryButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { PageCard } from '@/components/ui/PageCard';
import { formatDateBr, route } from '@/utils';

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
    created_at: string;
    updated_at: string;
}

interface ShowProps {
    song: Song;
}

function Show({ song }: ShowProps) {
    const hasLinks = song.youtube_link || song.spotify_link || song.lyrics_link || song.chords_link;

    return (
        <AppPage>
            <PageCard title={song.name} actions={route('songs.edit', song.id)}>
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div className="lg:col-span-2 space-y-6">
                        <div className="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                            <h3 className="text-lg font-medium text-neutral-dark dark:text-white mb-4">Informações da Música</h3>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Nome</dt>
                                    <dd className="mt-1 text-sm text-neutral-dark dark:text-white">{song.name}</dd>
                                </div>
                                {song.key && (
                                    <div>
                                        <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Tonalidade</dt>
                                        <dd className="mt-1">
                                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                                {song.key}
                                            </span>
                                        </dd>
                                    </div>
                                )}
                            </div>
                        </div>

                        {song.tags.length > 0 && (
                            <div className="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                <h3 className="text-lg font-medium text-neutral-dark dark:text-white mb-4">Tags</h3>
                                <div className="flex flex-wrap gap-2">
                                    {song.tags.map((tag) => (
                                        <span key={tag.id} className="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                            {tag.name}
                                        </span>
                                    ))}
                                </div>
                            </div>
                        )}

                        <div className="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                            <h3 className="text-lg font-medium text-neutral-dark dark:text-white mb-4">Links</h3>
                            {hasLinks ? (
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {song.youtube_link && (
                                        <a href={song.youtube_link} target="_blank" rel="noreferrer" className="text-sm font-medium text-red-600 dark:text-red-400 hover:underline">
                                            Assistir no YouTube
                                        </a>
                                    )}
                                    {song.spotify_link && (
                                        <a href={song.spotify_link} target="_blank" rel="noreferrer" className="text-sm font-medium text-green-600 dark:text-green-400 hover:underline">
                                            Ouvir no Spotify
                                        </a>
                                    )}
                                    {song.lyrics_link && (
                                        <a href={song.lyrics_link} target="_blank" rel="noreferrer" className="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                            Ver Letra
                                        </a>
                                    )}
                                    {song.chords_link && (
                                        <a href={song.chords_link} target="_blank" rel="noreferrer" className="text-sm font-medium text-purple-600 dark:text-purple-400 hover:underline">
                                            Ver Cifra
                                        </a>
                                    )}
                                </div>
                            ) : (
                                <p className="text-neutral-medium dark:text-gray-400 text-sm">Nenhum link cadastrado para esta música.</p>
                            )}
                        </div>
                    </div>

                    <div className="space-y-6">
                        <div className="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                            <h3 className="text-lg font-medium text-neutral-dark dark:text-white mb-4">Ações</h3>
                            <div className="space-y-3">
                                <LinkButton href={route('songs.edit', song.id)} className="w-full justify-center">
                                    Editar Música
                                </LinkButton>
                                <DeleteButton href={route('songs.destroy', song.id)}>
                                    Excluir Música
                                </DeleteButton>
                                <Link href={route('songs.index')}>
                                    <SecondaryButton type="button" className="w-full justify-center">
                                        Voltar à Lista
                                    </SecondaryButton>
                                </Link>
                            </div>
                        </div>

                        <div className="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                            <h3 className="text-lg font-medium text-neutral-dark dark:text-white mb-4">Informações do Sistema</h3>
                            <dl className="space-y-3 text-sm">
                                <div>
                                    <dt className="font-medium text-neutral-medium dark:text-gray-400">ID</dt>
                                    <dd className="text-neutral-dark dark:text-white">{song.id}</dd>
                                </div>
                                <div>
                                    <dt className="font-medium text-neutral-medium dark:text-gray-400">Criado em</dt>
                                    <dd className="text-neutral-dark dark:text-white">{formatDateBr(song.created_at)}</dd>
                                </div>
                                <div>
                                    <dt className="font-medium text-neutral-medium dark:text-gray-400">Última atualização</dt>
                                    <dd className="text-neutral-dark dark:text-white">{formatDateBr(song.updated_at)}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
