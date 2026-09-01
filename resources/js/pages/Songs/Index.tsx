import { useState } from 'react';
import { router } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Badge } from '@/components/ui/Badge';
import { EditButton, RowActions, ViewButton } from '@/components/ui/Button';
import { ActionsTh, DesktopOnly, MobileCard, MobileCardHeader, MobileList, Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { EmptyState } from '@/components/ui/EmptyState';
import { PageCard, Pagination, SearchForm } from '@/components/ui/PageCard';
import { Select } from '@/components/ui/Input';
import { route } from '@/utils';
import { Paginated } from '@/types';
import { Music } from 'lucide-react';

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

function SongLinks({ song }: { song: Song }) {
    const links = [
        song.youtube_link && { href: song.youtube_link, label: 'YouTube' },
        song.spotify_link && { href: song.spotify_link, label: 'Spotify' },
        song.lyrics_link && { href: song.lyrics_link, label: 'Letra' },
        song.chords_link && { href: song.chords_link, label: 'Cifra' },
    ].filter(Boolean) as Array<{ href: string; label: string }>;

    if (links.length === 0) {
        return <span className="text-muted-foreground">—</span>;
    }

    return (
        <div className="flex flex-wrap gap-2">
            {links.map((link) => (
                <a
                    key={link.label}
                    href={link.href}
                    target="_blank"
                    rel="noreferrer"
                    className="text-sm font-medium hover:underline"
                >
                    {link.label}
                </a>
            ))}
        </div>
    );
}

function Index({ songs, tags, filters = {} }: IndexProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const [tag, setTag] = useState(filters.tag ?? '');
    const empty = songs.data.length === 0;

    const tagOptions = [{ value: '', label: 'Todas as tags' }, ...tags.map((item) => ({ value: String(item.id), label: item.name }))];

    const handleSearch = () => {
        router.get(route('songs.index'), { search: search || undefined, tag: tag || undefined }, { preserveState: true, replace: true });
    };

    return (
        <AppPage>
            <PageCard
                title="Músicas"
                description="Catálogo de louvor. Use as tags para achar o que entra no culto."
                actions={route('songs.create')}
                actionsLabel="Nova música"
            >
                <div className="mb-4">
                    <SearchForm placeholder="Buscar músicas..." value={search} onChange={setSearch} onSubmit={handleSearch}>
                        <div className="w-full sm:w-48">
                            <Select
                                id="tag"
                                value={tag}
                                onChange={(event) => setTag(event.target.value)}
                                options={tagOptions}
                                placeholder=""
                            />
                        </div>
                    </SearchForm>
                </div>

                {empty ? (
                    <EmptyState
                        title={search || tag ? 'Nenhuma música encontrada' : 'Nenhuma música cadastrada'}
                        description={search || tag ? 'Tente outro nome ou tag.' : 'Inclua o repertório que a igreja já canta.'}
                        actionLabel={search || tag ? undefined : 'Nova música'}
                        actionHref={search || tag ? undefined : route('songs.create')}
                        icon={<Music className="h-8 w-8" />}
                    />
                ) : (
                    <>
                        <DesktopOnly>
                            <TableShell>
                                <Table>
                                    <THead>
                                        <Th>Nome</Th>
                                        <Th>Tonalidade</Th>
                                        <Th>Tags</Th>
                                        <Th>Links</Th>
                                        <ActionsTh />
                                    </THead>
                                    <TBody>
                                        {songs.data.map((song) => (
                                            <Tr key={song.id}>
                                                <Td className="font-medium">{song.name}</Td>
                                                <Td>
                                                    {song.key ? (
                                                        <Badge tone="primary">{song.key}</Badge>
                                                    ) : (
                                                        <span className="text-muted-foreground">—</span>
                                                    )}
                                                </Td>
                                                <Td>
                                                    {song.tags.length > 0 ? (
                                                        <div className="flex flex-wrap gap-1">
                                                            {song.tags.slice(0, 3).map((item) => (
                                                                <Badge key={item.id}>{item.name}</Badge>
                                                            ))}
                                                            {song.tags.length > 3 && <Badge>+{song.tags.length - 3}</Badge>}
                                                        </div>
                                                    ) : (
                                                        <span className="text-muted-foreground">—</span>
                                                    )}
                                                </Td>
                                                <Td>
                                                    <SongLinks song={song} />
                                                </Td>
                                                <Td align="right">
                                                    <RowActions>
                                                        <ViewButton href={route('songs.show', song.id)} />
                                                        <EditButton href={route('songs.edit', song.id)} />
                                                    </RowActions>
                                                </Td>
                                            </Tr>
                                        ))}
                                    </TBody>
                                </Table>
                            </TableShell>
                        </DesktopOnly>

                        <MobileList>
                            {songs.data.map((song) => (
                                <MobileCard key={song.id}>
                                    <MobileCardHeader
                                        actions={
                                            <RowActions>
                                                <ViewButton href={route('songs.show', song.id)} />
                                                <EditButton href={route('songs.edit', song.id)} />
                                            </RowActions>
                                        }
                                    >
                                        <p className="font-semibold">{song.name}</p>
                                        {song.key && <p className="mt-1 text-sm text-muted-foreground">Tom {song.key}</p>}
                                    </MobileCardHeader>
                                </MobileCard>
                            ))}
                        </MobileList>

                        <Pagination paginator={songs} />
                    </>
                )}
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
