import { FormEventHandler, ReactNode } from 'react';
import { Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/layouts/GuestLayout';
import { TextInput } from '@/components/ui/Input';
import { PrimaryButton } from '@/components/ui/Button';
import { Alert } from '@/components/ui/Alert';
import { route } from '@/utils';

function ForcePasswordChange() {
    const { data, setData, post, processing, errors } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (event) => {
        event.preventDefault();
        post(route('password.force-change.update'));
    };

    return (
        <>
            <div className="flex justify-center mb-6">
                <img
                    src="/sib-logo-dark.png"
                    alt="Logo Sib Barcelona"
                    className="block dark:hidden w-[9rem] mb-2 mt-2"
                />
                <img
                    src="/sib-logo-white.png"
                    alt="Logo Sib Barcelona"
                    className="dark:block hidden w-[9rem] mb-2 mt-2"
                />
            </div>

            <div className="mb-6 text-center">
                <h2 className="text-2xl font-bold text-gray-900 dark:text-white">Alterar Senha</h2>
                <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Por segurança, você deve alterar sua senha antes de acessar o sistema
                </p>
            </div>

            <Alert type="info" className="mb-6">
                <h3 className="text-sm font-medium mb-2">🔐 Mudança de Senha Obrigatória</h3>
                <p className="text-sm">
                    Esta é sua primeira vez no sistema. Por motivos de segurança, você deve alterar a senha temporária
                    fornecida pelo administrador antes de acessar o painel.
                </p>
            </Alert>

            <form onSubmit={submit}>
                <div className="mb-4">
                    <TextInput
                        id="current_password"
                        label="Senha Atual"
                        type="password"
                        name="current_password"
                        value={data.current_password}
                        required
                        autoComplete="current-password"
                        error={errors.current_password}
                        onChange={(event) => setData('current_password', event.target.value)}
                    />
                </div>

                <div className="mb-4">
                    <TextInput
                        id="password"
                        label="Nova Senha"
                        type="password"
                        name="password"
                        value={data.password}
                        required
                        placeholder="Digite sua nova senha"
                        autoComplete="new-password"
                        error={errors.password}
                        onChange={(event) => setData('password', event.target.value)}
                    />
                    <p className="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Mínimo de 8 caracteres. Use uma senha forte e segura.
                    </p>
                </div>

                <div className="mb-6">
                    <TextInput
                        id="password_confirmation"
                        label="Confirmar Nova Senha"
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        required
                        placeholder="Confirme sua nova senha"
                        autoComplete="new-password"
                        error={errors.password_confirmation}
                        onChange={(event) => setData('password_confirmation', event.target.value)}
                    />
                </div>

                <Alert type="success" className="mb-6">
                    <h4 className="text-sm font-medium mb-2">💡 Dicas para uma senha segura:</h4>
                    <ul className="text-sm space-y-1">
                        <li>• Use pelo menos 8 caracteres</li>
                        <li>• Combine letras maiúsculas e minúsculas</li>
                        <li>• Inclua números e símbolos especiais</li>
                        <li>• Evite informações pessoais óbvias</li>
                    </ul>
                </Alert>

                <div className="flex items-center justify-end">
                    <PrimaryButton type="submit" disabled={processing}>
                        Alterar Senha
                    </PrimaryButton>
                </div>
            </form>

            <div className="mt-4 text-center">
                <Link
                    href={route('logout')}
                    method="post"
                    as="button"
                    className="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline"
                >
                    Sair do Sistema
                </Link>
            </div>

            <div className="mt-6 text-center">
                <p className="text-xs text-gray-500 dark:text-gray-400">
                    Após alterar sua senha, você será redirecionado automaticamente para o painel administrativo.
                </p>
            </div>
        </>
    );
}

ForcePasswordChange.layout = (page: ReactNode) => <GuestLayout>{page}</GuestLayout>;

export default ForcePasswordChange;
