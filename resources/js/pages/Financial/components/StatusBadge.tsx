import { Badge } from '@/components/ui/Badge';

export function ActiveStatusBadge({ active }: { active: boolean }) {
    return <Badge tone={active ? 'success' : 'neutral'}>{active ? 'Ativo' : 'Inativo'}</Badge>;
}

export function TransactionTypeBadge({ type }: { type: 'entrada' | 'saida' }) {
    return <Badge tone={type === 'entrada' ? 'success' : 'danger'}>{type === 'entrada' ? 'Entrada' : 'Saída'}</Badge>;
}

export function CampaignStatusBadge({ status }: { status: 'ativo' | 'encerrado' | 'cancelada' }) {
    const tone = status === 'ativo' ? 'success' : status === 'encerrado' ? 'info' : 'danger';
    const label = status === 'ativo' ? 'Ativa' : status === 'encerrado' ? 'Encerrada' : 'Cancelada';

    return <Badge tone={tone}>{label}</Badge>;
}
