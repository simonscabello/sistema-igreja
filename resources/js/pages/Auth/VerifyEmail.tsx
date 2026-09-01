import { FormEventHandler, ReactNode } from 'react';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
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
            <Head title="Verificar e-mail" />
            <h1 className="mb-1 text-center text-xl font-semibold text-foreground">Verificar e-mail</h1>
            <p className="mb-6 text-center text-sm text-muted-foreground">Enviamos um link de verificação. Se não chegou, pedimos outro.</p>

            {flash.status === 'verification-link-sent' && (
                <Alert type="success" className="mb-4">
                    Um novo link foi enviado para o e-mail cadastrado.
                </Alert>
            )}

            <div className="flex items-center justify-between gap-3">
                <form onSubmit={resendVerification}>
                    <PrimaryButton type="submit" processing={processing}>
                        Reenviar e-mail
                    </PrimaryButton>
                </form>

                <Link href={route('logout')} method="post" as="button" className="text-sm text-muted-foreground hover:text-foreground">
                    Sair
                </Link>
            </div>
        </>
    );
}

VerifyEmail.layout = (page: ReactNode) => <GuestLayout>{page}</GuestLayout>;

export default VerifyEmail;
