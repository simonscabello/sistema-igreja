import { FormEventHandler, ReactNode } from 'react';
import { useForm, usePage } from '@inertiajs/react';
import GuestLayout from '@/layouts/GuestLayout';
import { TextInput } from '@/components/ui/Input';
import { PrimaryButton } from '@/components/ui/Button';
import { Alert } from '@/components/ui/Alert';
import { route } from '@/utils';
import { PageProps } from '@/types';

function ForgotPassword() {
    const { flash } = usePage<PageProps>().props;
    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });

    const submit: FormEventHandler = (event) => {
        event.preventDefault();
        post(route('password.email'));
    };

    return (
        <>
            <div className="mb-4 text-sm text-muted-foreground">Informe o e-mail da conta. Enviamos um link para criar uma senha nova.</div>

            {flash.status && (
                <Alert type="success" className="mb-4">
                    {flash.status}
                </Alert>
            )}

            <form onSubmit={submit}>
                <TextInput
                    id="email"
                    label="E-mail"
                    type="email"
                    name="email"
                    value={data.email}
                    required
                    autoFocus
                    error={errors.email}
                    onChange={(event) => setData('email', event.target.value)}
                />

                <div className="flex items-center justify-end mt-4">
                    <PrimaryButton type="submit" processing={processing}>
                        Enviar link
                    </PrimaryButton>
                </div>
            </form>
        </>
    );
}

ForgotPassword.layout = (page: ReactNode) => <GuestLayout>{page}</GuestLayout>;

export default ForgotPassword;
