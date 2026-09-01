import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Badge } from '@/components/ui/Badge';
import { BackButton, EditButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { DetailActions, DetailField, DetailGrid, DetailSection } from '@/components/ui/Detail';
import { PageCard } from '@/components/ui/PageCard';
import { formatDateBr, route } from '@/utils';

interface Visitor {
    id: number;
    name: string;
    mobile: string | null;
    age_group: string | null;
    gender: string | null;
    visit_date: string | null;
    wants_contact: boolean;
    full_address: string | null;
    notes: string | null;
}

interface ShowProps {
    visitor: Visitor;
}

function formatAgeGroup(value: string | null): string {
    if (!value) {
        return '';
    }

    if (value === 'crianca_adolescente') {
        return 'Criança / Adolescente';
    }

    return value.charAt(0).toUpperCase() + value.slice(1);
}

function Show({ visitor }: ShowProps) {
    return (
        <AppPage>
            <PageCard
                title={visitor.name}
                breadcrumbs={[{ label: 'Visitantes', href: route('visitors.index') }, { label: visitor.name }]}
                action={
                    <>
                        <EditButton href={route('visitors.edit', visitor.id)} size="md" />
                        <DeleteButton href={route('visitors.destroy', visitor.id)} />
                    </>
                }
            >
                <div className="space-y-6">
                    <DetailSection title="Pessoais">
                        <DetailGrid>
                            <DetailField label="Nome" value={visitor.name} />
                            <DetailField label="Celular" value={visitor.mobile} />
                            <DetailField label="Faixa etária" value={formatAgeGroup(visitor.age_group)} />
                            <DetailField
                                label="Gênero"
                                value={visitor.gender ? visitor.gender.charAt(0).toUpperCase() + visitor.gender.slice(1) : null}
                            />
                        </DetailGrid>
                    </DetailSection>

                    <DetailSection title="Visita">
                        <DetailGrid>
                            <DetailField label="Data da visita" value={visitor.visit_date ? formatDateBr(visitor.visit_date) : null} />
                            <DetailField label="Deseja ser contactado?">
                                <Badge tone={visitor.wants_contact ? 'success' : 'neutral'}>{visitor.wants_contact ? 'Sim' : 'Não'}</Badge>
                            </DetailField>
                        </DetailGrid>
                    </DetailSection>

                    {visitor.full_address && (
                        <DetailSection title="Endereço">
                            <p className="whitespace-pre-line text-sm">{visitor.full_address}</p>
                        </DetailSection>
                    )}

                    {visitor.notes && (
                        <DetailSection title="Observações">
                            <p className="whitespace-pre-line text-sm">{visitor.notes}</p>
                        </DetailSection>
                    )}

                    <DetailActions>
                        <BackButton href={route('visitors.index')} />
                    </DetailActions>
                </div>
            </PageCard>
        </AppPage>
    );
}

Show.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Show;
