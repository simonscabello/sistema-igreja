import { FormEventHandler, ReactNode } from 'react';
import { Link, useForm, usePage } from '@inertiajs/react';
import GuestLayout from '@/layouts/GuestLayout';
import { PrimaryButton } from '@/components/ui/Button';
import { Alert } from '@/components/ui/Alert';
import { route } from '@/utils';
import { PageProps } from '@/types';

function VerifyEmail() {
    const { flash } = usePage<PageProps>().props;
    const { post, processing } = useForm({});

    const resendVerification: FormEventHandler = (event) => {
        event.preventDefault();
        post(route('verification.send'));
    };

    return (
        <>
            <div className="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Obrigado por se cadastrar! Antes de começar, você poderia verificar seu endereço de e-mail clicando no
                link que acabamos de enviar? Se você não recebeu o e-mail, ficaremos felizes em enviar outro.
            </div>

            {flash.status === 'verification-link-sent' && (
                <Alert type="success" className="mb-4">
                    Um novo link de verificação foi enviado para o endereço de e-mail fornecido durante o registro.
                </Alert>
            )}

            <div className="mt-4 flex items-center justify-between">
                <form onSubmit={resendVerification}>
                    <PrimaryButton type="submit" disabled={processing}>
                        Reenviar E-mail de Verificação
                    </PrimaryButton>
                </form>

                <Link
                    href={route('logout')}
                    method="post"
                    as="button"
                    className="underline text-sm text-neutral-dark dark:text-gray-300 hover:text-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                >
                    Sair
                </Link>
            </div>
        </>
    );
}

VerifyEmail.layout = (page: ReactNode) => <GuestLayout>{page}</GuestLayout>;

export default VerifyEmail;
