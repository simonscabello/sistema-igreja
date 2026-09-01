import { FormEventHandler, ReactNode } from 'react';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import GuestLayout from '@/layouts/GuestLayout';
import { Checkbox, PasswordInput, TextInput } from '@/components/ui/Input';
import { PrimaryButton } from '@/components/ui/Button';
import { Alert } from '@/components/ui/Alert';
import { route } from '@/utils';
import { PageProps } from '@/types';

function Login() {
    const { flash } = usePage<PageProps>().props;
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit: FormEventHandler = (event) => {
        event.preventDefault();
        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <>
            <Head title="Entrar" />
            <div className="mb-6 flex justify-center">
                <img src="/sib-logo-dark.png" alt="SIB Barcelona" className="mt-1 block w-36 dark:hidden" />
                <img src="/sib-logo-white.png" alt="SIB Barcelona" className="mt-1 hidden w-36 dark:block" />
            </div>

            <h1 className="mb-1 text-center text-xl font-semibold">Entrar</h1>
            <p className="mb-6 text-center text-sm text-muted-foreground">Use o e-mail da sua conta no sistema.</p>

            {flash.status && (
                <Alert type="success" className="mb-4">
                    {flash.status}
                </Alert>
            )}

            <form onSubmit={submit} className="space-y-4">
                <TextInput
                    id="email"
                    label="E-mail"
                    type="email"
                    name="email"
                    value={data.email}
                    autoComplete="username"
                    required
                    autoFocus
                    error={errors.email}
                    onChange={(event) => setData('email', event.target.value)}
                />

                <PasswordInput
                    id="password"
                    label="Senha"
                    name="password"
                    value={data.password}
                    autoComplete="current-password"
                    required
                    error={errors.password}
                    onChange={(event) => setData('password', event.target.value)}
                />

                <Checkbox
                    id="remember_me"
                    name="remember"
                    label="Manter-me conectado"
                    checked={data.remember}
                    onChange={(checked) => setData('remember', checked)}
                />

                <div className="flex items-center justify-between gap-3 pt-2">
                    <Link href={route('password.request')} className="text-sm text-muted-foreground hover:text-foreground">
                        Esqueceu a senha?
                    </Link>

                    <PrimaryButton type="submit" processing={processing}>
                        Entrar
                    </PrimaryButton>
                </div>
            </form>
        </>
    );
}

Login.layout = (page: ReactNode) => <GuestLayout>{page}</GuestLayout>;

export default Login;
