import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Badge } from '@/components/ui/Badge';
import { BackButton, EditButton, LinkButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { DetailActions, DetailField, DetailGrid, DetailSection } from '@/components/ui/Detail';
import { PageCard } from '@/components/ui/PageCard';
import { formatDateBr, route } from '@/utils';
import { WorshipFunctionIcon } from '@/utils/worshipFunctionIcon';

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

interface Assignment {
    id: number;
    worship_function_id: number;
    member_id: number;
    order: number;
    worship_function?: {
        id: number;
        name: string;
        slug?: string;
        sort_order?: number;
    };
    member?: {
        id: number;
        full_name: string;
    };
}

interface WorshipSet {
    id: number;
    date: string;
    formatted_date: string;
    period: 'manha' | 'noite';
    period_label: string;
    order_notes: string | null;
    observations: string | null;
    songs: Song[];
    assignments: Assignment[];
    created_at: string;
    updated_at: string;
}

interface ShowProps {
    worshipSet: WorshipSet;
}

function groupAssignments(assignments: Assignment[]): Array<{ functionName: string; slug: string; members: string[] }> {
    const grouped = new Map<string, { slug: string; members: Array<{ order: number; name: string }> }>();

    assignments.forEach((assignment) => {
        const functionName = assignment.worship_function?.name ?? 'Função';
        const functionSlug = assignment.worship_function?.slug ?? 'vocal';
        const memberName = assignment.member?.full_name;

        if (!memberName) {
            return;
        }

        const current = grouped.get(functionName) ?? { slug: functionSlug, members: [] };
        current.members.push({ order: assignment.order, name: memberName });
        grouped.set(functionName, current);
    });

    return Array.from(grouped.entries()).map(([functionName, data]) => ({
        functionName,
        slug: data.slug,
        members: data.members.sort((left, right) => left.order - right.order).map((member) => member.name),
    }));
}

function Show({ worshipSet }: ShowProps) {
    const scheduleGroups = groupAssignments(worshipSet.assignments);
    const title = `Culto — ${worshipSet.formatted_date ?? formatDateBr(worshipSet.date)}`;

    return (
        <AppPage>
            <PageCard
                title={title}
                breadcrumbs={[
                    { label: 'Cultos', href: route('worship-sets.index') },
                    { label: worshipSet.formatted_date ?? formatDateBr(worshipSet.date) },
                ]}
                action={<EditButton href={route('worship-sets.edit', worshipSet.id)} size="md" />}
            >
                <div className="space-y-6">
                    <DetailSection title="Culto">
                        <DetailGrid>
                            <DetailField label="Data" value={worshipSet.formatted_date ?? formatDateBr(worshipSet.date)} />
                            <DetailField label="Período">
                                <Badge tone={worshipSet.period === 'manha' ? 'warning' : 'info'}>
                                    {worshipSet.period_label ?? (worshipSet.period === 'manha' ? 'Manhã' : 'Noite')}
                                </Badge>
                            </DetailField>
                        </DetailGrid>
                    </DetailSection>

                    <DetailSection title="Escala">
                        {scheduleGroups.length > 0 ? (
                            <ul className="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                {scheduleGroups.map((group) => (
                                    <li
                                        key={group.functionName}
                                        className="rounded-lg border border-border bg-muted/40 p-3"
                                    >
                                        <div className="flex items-center gap-2">
                                            <span className="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-muted text-foreground">
                                                <WorshipFunctionIcon slug={group.slug} />
                                            </span>
                                            <p className="text-sm text-muted-foreground">{group.functionName}</p>
                                        </div>
                                        <ul className="mt-2 flex flex-wrap gap-1.5">
                                            {group.members.map((name) => (
                                                <li key={name}>
                                                    <Badge>{name}</Badge>
                                                </li>
                                            ))}
                                        </ul>
                                    </li>
                                ))}
                            </ul>
                        ) : (
                            <p className="text-sm text-muted-foreground">Nenhuma pessoa escalada para este culto.</p>
                        )}
                    </DetailSection>

                    {worshipSet.songs.length > 0 && (
                        <DetailSection title="Repertório">
                            <ol className="space-y-3">
                                {worshipSet.songs.map((song, index) => (
                                    <li key={song.id} className="flex items-center justify-between gap-3 rounded-lg bg-muted/40 p-3">
                                        <div className="flex items-center gap-3">
                                            <span className="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-sm font-medium text-primary-foreground">
                                                {index + 1}
                                            </span>
                                            <div>
                                                <h4 className="font-medium">{song.name}</h4>
                                                {song.key && (
                                                    <p className="text-sm text-muted-foreground">Tonalidade original: {song.key}</p>
                                                )}
                                            </div>
                                        </div>
                                        {song.pivot?.key_used && <Badge tone="success">Tom usado: {song.pivot.key_used}</Badge>}
                                    </li>
                                ))}
                            </ol>
                        </DetailSection>
                    )}

                    {(worshipSet.order_notes || worshipSet.observations) && (
                        <DetailSection title="Observações">
                            <DetailGrid columns={1}>
                                <DetailField label="Ordem das músicas" value={worshipSet.order_notes} />
                                <DetailField label="Observações gerais" value={worshipSet.observations} />
                            </DetailGrid>
                        </DetailSection>
                    )}

                    <DetailSection title="Registro">
                        <DetailGrid>
                            <DetailField label="Criado em" value={formatDateBr(worshipSet.created_at)} />
                            <DetailField label="Última atualização" value={formatDateBr(worshipSet.updated_at)} />
                        </DetailGrid>
                    </DetailSection>

                    <DetailActions>
                        <BackButton href={route('worship-sets.index')}>Voltar à lista</BackButton>
                        <LinkButton href={route('worship-sets.clone', worshipSet.id)} size="md">
                            Clonar culto
                        </LinkButton>
                        <DeleteButton href={route('worship-sets.destroy', worshipSet.id)} />
                    </DetailActions>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
