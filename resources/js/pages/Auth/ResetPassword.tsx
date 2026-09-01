import { FormEventHandler, ReactNode } from 'react';
import { useForm } from '@inertiajs/react';
import GuestLayout from '@/layouts/GuestLayout';
import { TextInput } from '@/components/ui/Input';
import { PrimaryButton } from '@/components/ui/Button';
import { route } from '@/utils';

interface ResetPasswordProps {
    email: string;
    token: string;
}

function ResetPassword({ email, token }: ResetPasswordProps) {
    const { data, setData, post, processing, errors } = useForm({
        token,
        email,
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (event) => {
        event.preventDefault();
        post(route('password.store'));
    };

    return (
        <form onSubmit={submit}>
            <input type="hidden" name="token" value={data.token} />

            <TextInput
                id="email"
                label="E-mail"
                type="email"
                name="email"
                value={data.email}
                readOnly
                autoComplete="username"
                required
                autoFocus
                error={errors.email}
                onChange={(event) => setData('email', event.target.value)}
            />

            <div className="mt-4">
                <TextInput
                    id="password"
                    label="Senha"
                    type="password"
                    name="password"
                    value={data.password}
                    autoComplete="new-password"
                    required
                    error={errors.password}
                    onChange={(event) => setData('password', event.target.value)}
                />
            </div>

            <div className="mt-4">
                <TextInput
                    id="password_confirmation"
                    label="Confirmar Senha"
                    type="password"
                    name="password_confirmation"
                    value={data.password_confirmation}
                    autoComplete="new-password"
                    required
                    error={errors.password_confirmation}
                    onChange={(event) => setData('password_confirmation', event.target.value)}
                />
            </div>

            <div className="flex items-center justify-end mt-4">
                <PrimaryButton type="submit" disabled={processing}>
                    Redefinir Senha
                </PrimaryButton>
            </div>
        </form>
    );
}

ResetPassword.layout = (page: ReactNode) => <GuestLayout>{page}</GuestLayout>;

export default ResetPassword;
