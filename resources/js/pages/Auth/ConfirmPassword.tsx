import { FormEventHandler, ReactNode } from 'react';
import { Head, useForm } from '@inertiajs/react';
import GuestLayout from '@/layouts/GuestLayout';
import { PasswordInput } from '@/components/ui/Input';
import { PrimaryButton } from '@/components/ui/Button';
import { route } from '@/utils';

function ConfirmPassword() {
    const { data, setData, post, processing, errors } = useForm({
        password: '',
    });

    const submit: FormEventHandler = (event) => {
        event.preventDefault();
        post(route('password.confirm'));
    };

    return (
        <>
            <Head title="Confirmar senha" />
            <h1 className="mb-1 text-center text-xl font-semibold text-foreground">Confirmar senha</h1>
            <p className="mb-6 text-center text-sm text-muted-foreground">Área protegida. Confirme sua senha para continuar.</p>

            <form onSubmit={submit} className="space-y-4">
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

                <div className="flex justify-end pt-2">
                    <PrimaryButton type="submit" processing={processing}>
                        Confirmar
                    </PrimaryButton>
                </div>
            </form>
        </>
    );
}

ConfirmPassword.layout = (page: ReactNode) => <GuestLayout>{page}</GuestLayout>;

export default ConfirmPassword;
