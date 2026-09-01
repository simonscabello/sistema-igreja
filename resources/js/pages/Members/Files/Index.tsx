import { ChangeEvent, FormEvent } from 'react';
import Swal from 'sweetalert2';
import { Link, router, useForm, usePage } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { PrimaryButton, SecondaryButton } from '@/components/ui/Button';
import { FileInput } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { PageProps } from '@/types';
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
    const { flash } = usePage<PageProps>().props;

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

    const handleDelete = async (file: MemberFile) => {
        const isDark = document.documentElement.classList.contains('dark');

        const result = await Swal.fire({
            title: 'Remover este arquivo?',
            text: 'Esta ação não poderá ser desfeita.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3BA99C',
            cancelButtonColor: '#F44336',
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar',
            background: isDark ? '#1f2937' : '#fff',
            color: isDark ? '#f3f4f6' : '#111827',
        });

        if (result.isConfirmed) {
            router.delete(route('members.files.destroy', [member.id, file.id]));
        }
    };

    return (
        <AppPage>
            <PageCard title={`Arquivos de ${member.full_name}`}>
                {flash.success && (
                    <Alert type="success" dismissible>
                        <span className="font-medium">Sucesso!</span> {flash.success}
                    </Alert>
                )}

                <form onSubmit={handleUpload} className="mb-8">
                    <div className="flex flex-col sm:flex-row items-start sm:items-end gap-4">
                        <div className="flex-1 w-full">
                            <FileInput
                                id="files"
                                label="Enviar arquivos"
                                multiple
                                required
                                onChange={handleFileChange}
                                error={errors.files}
                            />
                        </div>
                        <PrimaryButton type="submit" disabled={processing || data.files.length === 0}>
                            Enviar
                        </PrimaryButton>
                    </div>
                </form>

                <div className="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <h3 className="font-semibold p-4 border-b border-neutral-medium dark:border-gray-700 text-neutral-dark dark:text-gray-300">
                        Arquivos vinculados
                    </h3>

                    <div className="hidden md:block overflow-x-auto">
                        <table className="w-full text-left">
                            <thead className="bg-neutral-light dark:bg-gray-700">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Nome</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Tamanho</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Visualizar</th>
                                    <th className="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-neutral-medium dark:divide-gray-700">
                                {files.length === 0 ? (
                                    <tr>
                                        <td colSpan={4} className="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                            Nenhum arquivo enviado.
                                        </td>
                                    </tr>
                                ) : (
                                    files.map((file) => (
                                        <tr key={file.id}>
                                            <td className="px-6 py-3 text-sm text-neutral-dark dark:text-gray-300">{file.original_name}</td>
                                            <td className="px-6 py-3 text-sm text-neutral-dark dark:text-gray-300">{formatFileSize(file.size)} KB</td>
                                            <td className="px-6 py-3 text-sm">
                                                <a href={file.url} target="_blank" rel="noopener noreferrer" className="text-primary underline">
                                                    Abrir
                                                </a>
                                            </td>
                                            <td className="px-6 py-3 text-sm text-right">
                                                <button
                                                    type="button"
                                                    onClick={() => handleDelete(file)}
                                                    className="text-red-600 hover:underline"
                                                >
                                                    Excluir
                                                </button>
                                            </td>
                                        </tr>
                                    ))
                                )}
                            </tbody>
                        </table>
                    </div>

                    <div className="md:hidden divide-y divide-neutral-medium dark:divide-gray-700">
                        {files.length === 0 ? (
                            <div className="p-4 text-center text-neutral-medium dark:text-gray-500">
                                Nenhum arquivo enviado.
                            </div>
                        ) : (
                            files.map((file) => (
                                <div key={file.id} className="p-4 space-y-2">
                                    <div className="font-medium text-neutral-dark dark:text-gray-300">{file.original_name}</div>
                                    <div className="text-sm text-gray-500 dark:text-gray-400">{formatFileSize(file.size)} KB</div>
                                    <div className="flex gap-4 pt-2">
                                        <a href={file.url} target="_blank" rel="noopener noreferrer" className="text-primary underline text-sm">
                                            Abrir
                                        </a>
                                        <button
                                            type="button"
                                            onClick={() => handleDelete(file)}
                                            className="text-red-600 hover:underline text-sm"
                                        >
                                            Excluir
                                        </button>
                                    </div>
                                </div>
                            ))
                        )}
                    </div>
                </div>

                <div className="mt-6">
                    <Link href={route('members.show', member.id)}>
                        <SecondaryButton type="button">Voltar ao membro</SecondaryButton>
                    </Link>
                </div>
            </PageCard>
        </AppPage>
    );
}

Index.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Index;
