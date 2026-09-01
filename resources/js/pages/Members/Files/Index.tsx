import { ChangeEvent, FormEvent } from 'react';
import { useForm } from '@inertiajs/react';
import { Eye, FileUp } from 'lucide-react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Button, PrimaryButton, SecondaryButton } from '@/components/ui/Button';
import { DeleteButton } from '@/components/ui/DeleteButton';
import { FileInput } from '@/components/ui/Input';
import { EmptyState } from '@/components/ui/EmptyState';
import { PageCard } from '@/components/ui/PageCard';
import { DesktopOnly, MobileCard, MobileCardHeader, MobileList, Table, TableShell, TBody, Td, Th, THead, Tr } from '@/components/ui/DataTable';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { route } from '@/utils';

interface Member {
    id: number;
    full_name: string;
}

interface MemberFile {
    id: number;
    original_name: string;
    size: number;
    url: string;
}

interface IndexProps {
    member: Member;
    files: MemberFile[];
}

function formatFileSize(bytes: number): string {
    return new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(bytes / 1024);
}

function Index({ member, files }: IndexProps) {
    const { data, setData, post, processing, errors, reset } = useForm<{ files: File[] }>({
        files: [],
    });

    const handleFileChange = (event: ChangeEvent<HTMLInputElement>) => {
        setData('files', Array.from(event.target.files ?? []));
    };

    const handleUpload = (event: FormEvent) => {
        event.preventDefault();
        post(route('members.files.store', member.id), {
            forceFormData: true,
            onSuccess: () => reset('files'),
        });
    };

    return (
        <AppPage>
            <PageCard
                title={`Arquivos de ${member.full_name}`}
                description="Documentos vinculados ao cadastro pastoral."
                breadcrumbs={[
                    { label: 'Membros', href: route('members.index') },
                    { label: member.full_name, href: route('members.show', member.id) },
                    { label: 'Arquivos' },
                ]}
            >
                <Card className="mb-6 shadow-none">
                    <CardHeader>
                        <CardTitle className="text-base">Enviar arquivos</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleUpload} className="flex flex-col items-start gap-4 sm:flex-row sm:items-end">
                            <div className="w-full flex-1">
                                <FileInput id="files" label="Arquivos" multiple required onChange={handleFileChange} error={errors.files} />
                            </div>
                            <PrimaryButton type="submit" disabled={processing || data.files.length === 0} icon={FileUp}>
                                Enviar
                            </PrimaryButton>
                        </form>
                    </CardContent>
                </Card>

                {files.length === 0 ? (
                    <EmptyState title="Nenhum arquivo enviado" description="Envie comprovantes ou documentos deste membro." />
                ) : (
                    <>
                        <DesktopOnly>
                            <TableShell>
                                <Table>
                                    <THead>
                                        <Th>Nome</Th>
                                        <Th>Tamanho</Th>
                                        <Th align="right">
                                            <span className="sr-only">Ações</span>
                                        </Th>
                                    </THead>
                                    <TBody>
                                        {files.map((file) => (
                                            <Tr key={file.id}>
                                                <Td className="font-medium">{file.original_name}</Td>
                                                <Td className="tabular">{formatFileSize(file.size)} KB</Td>
                                                <Td align="right">
                                                    <div className="inline-flex items-center gap-1">
                                                        <Button variant="ghost" size="sm" asChild icon={false}>
                                                            <a href={file.url} target="_blank" rel="noopener noreferrer">
                                                                <Eye className="h-4 w-4" />
                                                                Abrir
                                                            </a>
                                                        </Button>
                                                        <DeleteButton
                                                            href={route('members.files.destroy', { member: member.id, file: file.id })}
                                                            compact
                                                            title="Remover este arquivo?"
                                                            text="Esta ação não poderá ser desfeita."
                                                        />
                                                    </div>
                                                </Td>
                                            </Tr>
                                        ))}
                                    </TBody>
                                </Table>
                            </TableShell>
                        </DesktopOnly>

                        <MobileList>
                            {files.map((file) => (
                                <MobileCard key={file.id}>
                                    <MobileCardHeader
                                        actions={
                                            <div className="inline-flex items-center gap-1">
                                                <Button variant="ghost" size="sm" asChild icon={false}>
                                                    <a href={file.url} target="_blank" rel="noopener noreferrer">
                                                        <Eye className="h-4 w-4" />
                                                        Abrir
                                                    </a>
                                                </Button>
                                                <DeleteButton
                                                    href={route('members.files.destroy', { member: member.id, file: file.id })}
                                                    compact
                                                    title="Remover este arquivo?"
                                                />
                                            </div>
                                        }
                                    >
                                        <p className="font-medium">{file.original_name}</p>
                                        <p className="text-sm text-muted-foreground">{formatFileSize(file.size)} KB</p>
                                    </MobileCardHeader>
                                </MobileCard>
                            ))}
                        </MobileList>
                    </>
                )}

                <div className="mt-6">
                    <SecondaryButton href={route('members.show', member.id)}>Voltar ao membro</SecondaryButton>
                </div>
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
