import { useEffect } from 'react';
import { Link } from '@inertiajs/react';
import Swal from 'sweetalert2';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert } from '@/components/ui/Alert';
import { LinkButton, PrimaryButton, SecondaryButton } from '@/components/ui/Button';
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

function getSwalTheme() {
    const isDark = document.documentElement.classList.contains('dark');

    return {
        background: isDark ? '#374151' : '#ffffff',
        color: isDark ? '#f3f4f6' : '#111827',
    };
}

async function copyPassword(password: string) {
    const theme = getSwalTheme();

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

        await Swal.fire({
            title: 'Senha Copiada!',
            text: 'A senha foi copiada para a área de transferência.',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3BA99C',
            timer: 3000,
            timerProgressBar: true,
            ...theme,
        });
    } catch {
        await Swal.fire({
            title: 'Erro ao Copiar',
            text: 'Não foi possível copiar automaticamente. Copie manualmente a senha abaixo:',
            icon: 'error',
            html: `<div class="mt-4"><code class="px-3 py-2 rounded border text-lg font-mono">${password}</code></div>`,
            confirmButtonText: 'Entendi',
            confirmButtonColor: '#ef4444',
            allowOutsideClick: false,
            allowEscapeKey: false,
            ...theme,
        });
    }
}

function Created({ user, temporaryPassword }: CreatedProps) {
    useEffect(() => {
        const theme = getSwalTheme();

        Swal.fire({
            title: 'Senha Temporária Gerada',
            text: 'Esta senha será exibida apenas uma vez. Certifique-se de copiá-la agora!',
            icon: 'warning',
            confirmButtonText: 'Entendi',
            confirmButtonColor: '#3BA99C',
            allowOutsideClick: false,
            allowEscapeKey: false,
            ...theme,
        });
    }, []);

    return (
        <AppPage>
            <PageCard title="Usuário Criado com Sucesso">
                <div className="space-y-6">
                    <Alert type="success">
                        <span className="font-medium">Sucesso!</span> O usuário foi criado com sucesso no sistema.
                    </Alert>

                    <div className="bg-white dark:bg-gray-800 border border-neutral-medium dark:border-gray-700 rounded-lg p-6">
                        <h3 className="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Dados do Usuário</h3>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-neutral-dark dark:text-gray-300">Nome</label>
                                <p className="mt-1 text-sm text-neutral-medium dark:text-gray-400">{user.name}</p>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-neutral-dark dark:text-gray-300">Email</label>
                                <p className="mt-1 text-sm text-neutral-medium dark:text-gray-400">{user.email}</p>
                            </div>
                        </div>

                        {user.roles.length > 0 && (
                            <div className="mt-4">
                                <label className="block text-sm font-medium text-neutral-dark dark:text-gray-300 mb-2">Roles Atribuídos</label>
                                <div className="flex flex-wrap gap-2">
                                    {user.roles.map((role) => (
                                        <span key={role.id} className="inline-block bg-primary text-white text-xs px-3 py-1 rounded-full">
                                            {role.display_name ?? role.name}
                                        </span>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>

                    <div className="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                        <h3 className="text-lg font-medium text-yellow-800 dark:text-yellow-300 mb-4">Senha Temporária Gerada</h3>

                        <div className="bg-white dark:bg-gray-800 border border-yellow-300 dark:border-yellow-700 rounded-lg p-4 mb-4">
                            <label className="block text-sm font-medium text-yellow-800 dark:text-yellow-300 mb-1">Senha:</label>
                            <div className="flex items-center gap-3 flex-wrap">
                                <code className="text-lg font-mono bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded border text-neutral-dark dark:text-gray-300">
                                    {temporaryPassword}
                                </code>
                                <button
                                    type="button"
                                    onClick={() => copyPassword(temporaryPassword)}
                                    className="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded text-sm transition-colors duration-200"
                                >
                                    Copiar
                                </button>
                            </div>
                        </div>

                        <div className="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                            <h4 className="text-sm font-medium text-red-800 dark:text-red-300 mb-2">Importante:</h4>
                            <ul className="text-sm text-red-700 dark:text-red-300 space-y-1 list-disc pl-5">
                                <li>Esta senha será exibida apenas uma vez</li>
                                <li>Copie e anote a senha agora antes de continuar</li>
                                <li>O usuário deve alterar esta senha no primeiro login</li>
                                <li>Compartilhe esta senha de forma segura com o usuário</li>
                            </ul>
                        </div>
                    </div>

                    <div className="flex flex-wrap gap-4 pt-6 border-t border-neutral-medium">
                        <Link href={route('users.index')}>
                            <PrimaryButton type="button">Voltar para Lista de Usuários</PrimaryButton>
                        </Link>
                        <Link href={route('users.create')}>
                            <SecondaryButton type="button">Criar Outro Usuário</SecondaryButton>
                        </Link>
                        <LinkButton href={route('users.show', user.id)}>Ver Usuário</LinkButton>
                    </div>
                </div>
            </PageCard>
        </AppPage>
    );
}

Created.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Created;
