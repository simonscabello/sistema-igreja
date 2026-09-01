import { FormEventHandler, ReactNode } from 'react';
import { useForm } from '@inertiajs/react';
import GuestLayout from '@/layouts/GuestLayout';
import { TextInput } from '@/components/ui/Input';
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
            <div className="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Esta é uma área segura da aplicação. Por favor, confirme sua senha antes de continuar.
            </div>

            <form onSubmit={submit}>
                <TextInput
                    id="password"
                    label="Senha"
                    type="password"
                    name="password"
                    value={data.password}
                    autoComplete="current-password"
                    required
                    error={errors.password}
                    onChange={(event) => setData('password', event.target.value)}
                />

                <div className="flex justify-end mt-4">
                    <PrimaryButton type="submit" disabled={processing}>
                        Confirmar
                    </PrimaryButton>
                </div>
            </form>
        </>
    );
}

ConfirmPassword.layout = (page: ReactNode) => <GuestLayout>{page}</GuestLayout>;

export default ConfirmPassword;
