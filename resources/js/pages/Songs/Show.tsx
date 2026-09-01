import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Badge } from '@/components/ui/Badge';
import { BackButton, EditButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { DetailActions, DetailField, DetailGrid, DetailSection, ExternalLink } from '@/components/ui/Detail';
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
            <PageCard
                title={song.name}
                breadcrumbs={[{ label: 'Músicas', href: route('songs.index') }, { label: song.name }]}
                action={<EditButton href={route('songs.edit', song.id)} size="md" />}
            >
                <div className="space-y-6">
                    <DetailSection title="Música">
                        <DetailGrid>
                            <DetailField label="Nome" value={song.name} />
                            {song.key && (
                                <DetailField label="Tonalidade">
                                    <Badge>{song.key}</Badge>
                                </DetailField>
                            )}
                        </DetailGrid>
                    </DetailSection>

                    {song.tags.length > 0 && (
                        <DetailSection title="Tags">
                            <div className="flex flex-wrap gap-2">
                                {song.tags.map((tag) => (
                                    <Badge key={tag.id}>{tag.name}</Badge>
                                ))}
                            </div>
                        </DetailSection>
                    )}

                    <DetailSection title="Links">
                        {hasLinks ? (
                            <div className="flex flex-col gap-2">
                                {song.youtube_link && <ExternalLink href={song.youtube_link}>Assistir no YouTube</ExternalLink>}
                                {song.spotify_link && <ExternalLink href={song.spotify_link}>Ouvir no Spotify</ExternalLink>}
                                {song.lyrics_link && <ExternalLink href={song.lyrics_link}>Ver letra</ExternalLink>}
                                {song.chords_link && <ExternalLink href={song.chords_link}>Ver cifra</ExternalLink>}
                            </div>
                        ) : (
                            <p className="text-sm text-muted-foreground">Nenhum link cadastrado para esta música.</p>
                        )}
                    </DetailSection>

                    <DetailSection title="Registro">
                        <DetailGrid>
                            <DetailField label="Criado em" value={formatDateBr(song.created_at)} />
                            <DetailField label="Última atualização" value={formatDateBr(song.updated_at)} />
                        </DetailGrid>
                    </DetailSection>

                    <DetailActions>
                        <BackButton href={route('songs.index')}>Voltar à lista</BackButton>
                        <DeleteButton href={route('songs.destroy', song.id)}>Excluir</DeleteButton>
                    </DetailActions>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
