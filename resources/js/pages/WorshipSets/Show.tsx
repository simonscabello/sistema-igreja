import { Link } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton, SecondaryButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { PageCard } from '@/components/ui/PageCard';
import { formatDateBr, route } from '@/utils';

interface Song {
    id: number;
    name: string;
    key: string | null;
    youtube_link: string | null;
    spotify_link: string | null;
    lyrics_link: string | null;
    chords_link: string | null;
    pivot?: {
        key_used: string | null;
        order: number;
    };
}

interface WorshipSet {
    id: number;
    singer: string;
    preacher: string;
    date: string;
    formatted_date: string;
    period: 'manha' | 'noite';
    period_label: string;
    order_notes: string | null;
    observations: string | null;
    songs: Song[];
    created_at: string;
    updated_at: string;
}

interface ShowProps {
    worshipSet: WorshipSet;
}

function Show({ worshipSet }: ShowProps) {
    return (
        <AppPage>
            <PageCard title={`Repertório - ${worshipSet.formatted_date ?? formatDateBr(worshipSet.date)}`} actions={route('worship-sets.edit', worshipSet.id)}>
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div className="lg:col-span-2 space-y-6">
                        <div className="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                            <h3 className="text-lg font-medium text-neutral-dark dark:text-white mb-4">Informações do Culto</h3>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Data</dt>
                                    <dd className="mt-1 text-sm text-neutral-dark dark:text-white font-medium">
                                        {worshipSet.formatted_date ?? formatDateBr(worshipSet.date)}
                                    </dd>
                                </div>
                                <div>
                                    <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Período</dt>
                                    <dd className="mt-1">
                                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${worshipSet.period === 'manha' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'}`}>
                                            {worshipSet.period_label ?? (worshipSet.period === 'manha' ? 'Manhã' : 'Noite')}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Cantor</dt>
                                    <dd className="mt-1 text-sm text-neutral-dark dark:text-white">{worshipSet.singer}</dd>
                                </div>
                                <div>
                                    <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400">Ministro</dt>
                                    <dd className="mt-1 text-sm text-neutral-dark dark:text-white">{worshipSet.preacher}</dd>
                                </div>
                            </div>
                        </div>

                        {worshipSet.songs.length > 0 && (
                            <div className="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                <h3 className="text-lg font-medium text-neutral-dark dark:text-white mb-4">Repertório</h3>
                                <div className="space-y-3">
                                    {worshipSet.songs.map((song, index) => (
                                        <div key={song.id} className="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div className="flex items-center gap-3">
                                                <span className="flex items-center justify-center w-8 h-8 bg-primary text-white text-sm font-medium rounded-full">
                                                    {index + 1}
                                                </span>
                                                <div>
                                                    <h4 className="font-medium text-neutral-dark dark:text-white">{song.name}</h4>
                                                    {song.key && (
                                                        <p className="text-sm text-neutral-medium dark:text-gray-400">Tonalidade original: {song.key}</p>
                                                    )}
                                                </div>
                                            </div>
                                            <div className="flex items-center gap-2">
                                                {song.pivot?.key_used && (
                                                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        Tom usado: {song.pivot.key_used}
                                                    </span>
                                                )}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}

                        {(worshipSet.order_notes || worshipSet.observations) && (
                            <div className="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                <h3 className="text-lg font-medium text-neutral-dark dark:text-white mb-4">Observações</h3>
                                {worshipSet.order_notes && (
                                    <div className="mb-4">
                                        <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400 mb-2">Ordem das Músicas</dt>
                                        <dd className="text-sm text-neutral-dark dark:text-white whitespace-pre-wrap">{worshipSet.order_notes}</dd>
                                    </div>
                                )}
                                {worshipSet.observations && (
                                    <div>
                                        <dt className="text-sm font-medium text-neutral-medium dark:text-gray-400 mb-2">Observações Gerais</dt>
                                        <dd className="text-sm text-neutral-dark dark:text-white whitespace-pre-wrap">{worshipSet.observations}</dd>
                                    </div>
                                )}
                            </div>
                        )}
                    </div>

                    <div className="space-y-6">
                        <div className="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                            <h3 className="text-lg font-medium text-neutral-dark dark:text-white mb-4">Ações</h3>
                            <div className="space-y-3">
                                <LinkButton href={route('worship-sets.edit', worshipSet.id)} className="w-full justify-center">
                                    Editar Repertório
                                </LinkButton>
                                <LinkButton href={route('worship-sets.clone', worshipSet.id)} className="w-full justify-center">
                                    Clonar Repertório
                                </LinkButton>
                                <DeleteButton href={route('worship-sets.destroy', worshipSet.id)}>
                                    Excluir Repertório
                                </DeleteButton>
                                <Link href={route('worship-sets.index')}>
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
                                    <dd className="text-neutral-dark dark:text-white">{worshipSet.id}</dd>
                                </div>
                                <div>
                                    <dt className="font-medium text-neutral-medium dark:text-gray-400">Criado em</dt>
                                    <dd className="text-neutral-dark dark:text-white">{formatDateBr(worshipSet.created_at)}</dd>
                                </div>
                                <div>
                                    <dt className="font-medium text-neutral-medium dark:text-gray-400">Última atualização</dt>
                                    <dd className="text-neutral-dark dark:text-white">{formatDateBr(worshipSet.updated_at)}</dd>
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
