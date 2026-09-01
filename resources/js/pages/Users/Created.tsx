import { useEffect } from 'react';
import { toast } from 'sonner';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { Copy, KeyRound } from 'lucide-react';
import { Badge } from '@/components/ui/Badge';
import { Button, LinkButton, PrimaryButton, SecondaryButton } from '@/components/ui/Button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { DetailField, DetailGrid } from '@/components/ui/Detail';
import { PageCard } from '@/components/ui/PageCard';
import { route } from '@/utils';

interface Role {
    id: number;
    name: string;
    display_name: string | null;
}

interface User {
    id: number;
    name: string;
    email: string;
    roles: Role[];
}

interface CreatedProps {
    user: User;
    temporaryPassword: string;
}

async function copyPassword(password: string) {
    try {
        if (navigator.clipboard) {
            await navigator.clipboard.writeText(password);
        } else {
            const textArea = document.createElement('textarea');
            textArea.value = password;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
        }

        toast.success('Senha copiada para a área de transferência.');
    } catch {
        toast.error('Não foi possível copiar. Copie a senha manualmente.');
    }
}

function Created({ user, temporaryPassword }: CreatedProps) {
    useEffect(() => {
        toast.warning('Esta senha aparece só agora. Copie antes de sair da tela.');
    }, []);

    return (
        <AppPage>
            <PageCard
                title="Usuário criado"
                description="A conta já pode entrar no sistema. Entregue a senha temporária com segurança."
                breadcrumbs={[{ label: 'Usuários', href: route('users.index') }, { label: 'Criado' }]}
            >
                <div className="space-y-6">
                    <Alert type="success">O usuário foi criado com sucesso.</Alert>

                    <div className="grid gap-6 lg:grid-cols-2">
                        <Card className="shadow-none">
                            <CardHeader>
                                <CardTitle>Dados da conta</CardTitle>
                                <CardDescription>Quem acessa o sistema, não o cadastro de membro.</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <DetailGrid>
                                    <DetailField label="Nome" value={user.name} />
                                    <DetailField label="E-mail" value={user.email} />
                                </DetailGrid>
                                {user.roles.length > 0 && (
                                    <div className="mt-4">
                                        <p className="mb-2 text-sm text-muted-foreground">Papéis</p>
                                        <div className="flex flex-wrap gap-1">
                                            {user.roles.map((role) => (
                                                <Badge key={role.id} tone="primary">
                                                    {role.display_name ?? role.name}
                                                </Badge>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </CardContent>
                        </Card>

                        <Card className="border-warning/40 shadow-none">
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2">
                                    <KeyRound className="h-4 w-4" />
                                    Senha temporária
                                </CardTitle>
                                <CardDescription>Será pedida a troca no primeiro acesso.</CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div className="flex flex-wrap items-center gap-3">
                                    <code className="rounded-md border bg-muted px-3 py-2 font-mono text-lg">{temporaryPassword}</code>
                                    <Button type="button" variant="secondary" icon={Copy} onClick={() => copyPassword(temporaryPassword)}>
                                        Copiar
                                    </Button>
                                </div>
                                <Alert type="warning">Copie agora. Esta senha não será mostrada de novo.</Alert>
                            </CardContent>
                        </Card>
                    </div>

                    <div className="flex flex-wrap gap-2">
                        <PrimaryButton href={route('users.index')}>Voltar à lista</PrimaryButton>
                        <SecondaryButton href={route('users.create')}>Criar outro usuário</SecondaryButton>
                        <LinkButton href={route('users.show', user.id)}>Ver usuário</LinkButton>
                    </div>
                </div>
            </PageCard>
        </AppPage>
    );
}

Created.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Created;
