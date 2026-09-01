import { useMemo, useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { LinkButton } from '@/components/ui/Button';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { Select, TextInput } from '@/components/ui/Input';
import { formatDateBr, route } from '@/utils';
import { Paginated } from '@/types';

interface Song {
    id: number;
    name: string;
}

interface WorshipSet {
    id: number;
    date: string;
    formatted_date: string;
    period: 'manha' | 'noite';
    period_label: string;
    singer: string;
    preacher: string;
    songs: Song[];
}

interface IndexProps {
    worshipSets: Paginated<WorshipSet>;
    filters?: {
        search?: string;
        period?: string;
        date_from?: string;
        date_to?: string;
    };
}

const PERIOD_OPTIONS = [
    { value: '', label: 'Período' },
    { value: 'manha', label: 'Manhã' },
    { value: 'noite', label: 'Noite' },
];

function Index({ worshipSets, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const [period, setPeriod] = useState(filters.period ?? '');
    const [dateFrom, setDateFrom] = useState(filters.date_from ?? '');
    const [dateTo, setDateTo] = useState(filters.date_to ?? '');

    const hasFilters = useMemo(
        () => Boolean(search || period || dateFrom || dateTo),
        [search, period, dateFrom, dateTo],
    );

    const handleSearch = () => {
        router.get(
            route('worship-sets.index'),
            {
                search: search || undefined,
                period: period || undefined,
                date_from: dateFrom || undefined,
                date_to: dateTo || undefined,
            },
            { preserveState: true, replace: true },
        );
    };

    const handleClear = () => {
        setSearch('');
        setPeriod('');
        setDateFrom('');
        setDateTo('');
        router.get(route('worship-sets.index'), {}, { preserveState: true, replace: true });
    };

    return (
        <AppPage>
            <PageCard
                title="Cultos"
                description="Repertório de cada culto: data, período, cantor e músicas."
                actions={route('worship-sets.create')}
                actionsLabel="Novo culto"
            >
                <div className="flex flex-col lg:flex-row lg:items-end mb-4 gap-3">
                    <SearchForm
                        action={route('worship-sets.index')}
                        placeholder="Buscar por cantor ou ministro..."
                        value={search}
                        onChange={setSearch}
                        onSubmit={handleSearch}
                    />
                    <div className="w-full lg:w-36">
                        <Select
                            id="period"
                            value={period}
                            onChange={(event) => setPeriod(event.target.value)}
                            options={PERIOD_OPTIONS}
                            placeholder=""
                        />
                    </div>
                    <TextInput
                        id="date_from"
                        type="date"
                        value={dateFrom}
                        onChange={(event) => setDateFrom(event.target.value)}
                        placeholder="Data inicial"
                    />
                    <TextInput
                        id="date_to"
                        type="date"
                        value={dateTo}
                        onChange={(event) => setDateTo(event.target.value)}
                        placeholder="Data final"
                    />
                    <div className="flex gap-2">
                        <button
                            type="button"
                            onClick={handleSearch}
                            className="inline-flex items-center justify-center px-4 py-3 text-sm bg-primary text-white rounded-md hover:bg-primary-dark"
                        >
                            Buscar
                        </button>
                        {hasFilters && (
                            <button
                                type="button"
                                onClick={handleClear}
                                className="inline-flex items-center justify-center px-4 py-3 text-sm bg-neutral-medium dark:bg-gray-600 text-neutral-dark dark:text-gray-300 rounded-md hover:bg-neutral-dark dark:hover:bg-gray-500"
                            >
                                Limpar
                            </button>
                        )}
                    </div>
                </div>

                <div className="hidden lg:block overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table className="min-w-full divide-y divide-neutral-medium dark:divide-gray-700">
                        <thead className="bg-neutral-light dark:bg-gray-700">
                            <tr>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Data</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Período</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Músicas</th>
                                <th className="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                            {worshipSets.data.length === 0 ? (
                                <tr>
                                    <td colSpan={4} className="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                        Nenhum repertório encontrado.
                                    </td>
                                </tr>
                            ) : (
                                worshipSets.data.map((worshipSet) => (
                                    <tr key={worshipSet.id} className="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td className="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300 font-medium">
                                            {worshipSet.formatted_date ?? formatDateBr(worshipSet.date)}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${worshipSet.period === 'manha' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'}`}>
                                                {worshipSet.period_label ?? (worshipSet.period === 'manha' ? 'Manhã' : 'Noite')}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="flex flex-wrap gap-1">
                                                {worshipSet.songs.slice(0, 3).map((song) => (
                                                    <span key={song.id} className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                                        {song.name}
                                                    </span>
                                                ))}
                                                {worshipSet.songs.length > 3 && (
                                                    <span className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                                        +{worshipSet.songs.length - 3}
                                                    </span>
                                                )}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div className="flex justify-end gap-2">
                                                <LinkButton href={route('worship-sets.show', worshipSet.id)} className="text-xs px-3 py-1">
                                                    Ver
                                                </LinkButton>
                                                <LinkButton href={route('worship-sets.edit', worshipSet.id)} className="text-xs px-3 py-1">
                                                    Editar
                                                </LinkButton>
                                                <LinkButton href={route('worship-sets.clone', worshipSet.id)} className="text-xs px-3 py-1">
                                                    Clonar
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
                    {worshipSets.data.length === 0 ? (
                        <p className="py-8 text-center text-sm text-ink-muted">Nenhum culto encontrado.</p>
                    ) : (
                        worshipSets.data.map((worshipSet) => (
                            <div key={worshipSet.id} className="rounded-xl border border-line bg-surface p-4 dark:border-line-dark dark:bg-surface-dark">
                                <div className="flex items-start justify-between gap-2">
                                    <div>
                                        <p className="font-semibold">{worshipSet.formatted_date ?? formatDateBr(worshipSet.date)}</p>
                                        <p className="text-sm text-ink-muted">{worshipSet.period_label}</p>
                                    </div>
                                </div>
                                {worshipSet.singer && (
                                    <p className="mt-2 text-sm text-ink-muted">Cantor: {worshipSet.singer}</p>
                                )}
                                <p className="mt-1 text-sm text-ink-muted">
                                    {worshipSet.songs.length} {worshipSet.songs.length === 1 ? 'música' : 'músicas'}
                                </p>
                                <div className="mt-3 flex flex-wrap gap-3">
                                    <LinkButton href={route('worship-sets.show', worshipSet.id)}>Ver</LinkButton>
                                    <LinkButton href={route('worship-sets.edit', worshipSet.id)}>Editar</LinkButton>
                                    <LinkButton href={route('worship-sets.clone', worshipSet.id)}>Clonar</LinkButton>
                                </div>
                            </div>
                        ))
                    )}
                </div>

                <Pagination paginator={worshipSets} />
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
