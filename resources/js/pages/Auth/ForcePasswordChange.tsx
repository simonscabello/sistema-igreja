import { FormEventHandler, ReactNode } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/layouts/GuestLayout';
import { PasswordInput } from '@/components/ui/Input';
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
            <Head title="Alterar senha" />
            <div className="mb-6 flex justify-center">
                <img src="/sib-logo-dark.png" alt="SIB Barcelona" className="mt-1 block w-36 dark:hidden" />
                <img src="/sib-logo-white.png" alt="SIB Barcelona" className="mt-1 hidden w-36 dark:block" />
            </div>

            <h1 className="mb-1 text-center text-xl font-semibold text-foreground">Alterar senha</h1>
            <p className="mb-6 text-center text-sm text-muted-foreground">Antes de continuar, troque a senha temporária.</p>

            <Alert type="info" className="mb-4">
                Use pelo menos 8 caracteres, misturando letras, números e um símbolo.
            </Alert>

            <form onSubmit={submit} className="space-y-4">
                <PasswordInput
                    id="current_password"
                    label="Senha atual"
                    name="current_password"
                    value={data.current_password}
                    required
                    autoComplete="current-password"
                    error={errors.current_password}
                    onChange={(event) => setData('current_password', event.target.value)}
                />

                <PasswordInput
                    id="password"
                    label="Nova senha"
                    name="password"
                    value={data.password}
                    required
                    autoComplete="new-password"
                    error={errors.password}
                    onChange={(event) => setData('password', event.target.value)}
                />

                <PasswordInput
                    id="password_confirmation"
                    label="Confirmar nova senha"
                    name="password_confirmation"
                    value={data.password_confirmation}
                    required
                    autoComplete="new-password"
                    error={errors.password_confirmation}
                    onChange={(event) => setData('password_confirmation', event.target.value)}
                />

                <div className="flex items-center justify-between gap-3 pt-2">
                    <Link href={route('logout')} method="post" as="button" className="text-sm text-muted-foreground hover:text-foreground">
                        Sair
                    </Link>
                    <PrimaryButton type="submit" processing={processing}>
                        Alterar senha
                    </PrimaryButton>
                </div>
            </form>
        </>
    );
}

ForcePasswordChange.layout = (page: ReactNode) => <GuestLayout>{page}</GuestLayout>;

export default ForcePasswordChange;
