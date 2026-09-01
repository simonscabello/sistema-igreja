import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert, Modal } from '@/components/ui/Alert';
import { CancelButton, DangerButton, SaveButton } from '@/components/ui/Button';
import { PasswordInput, TextInput } from '@/components/ui/Input';
import { FormCard } from '@/components/ui/FormSection';
import { PageCard } from '@/components/ui/PageCard';
import { PageProps } from '@/types';
import { route } from '@/utils';
import { FormEvent, useEffect, useState } from 'react';
import { useForm, usePage } from '@inertiajs/react';

interface ProfileUser {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
}

interface EditProps {
    user: ProfileUser;
}

function Edit({ user }: EditProps) {
    const { flash } = usePage<PageProps>().props;
    const [showDeleteModal, setShowDeleteModal] = useState(false);

    const profileForm = useForm({
        name: user.name,
        email: user.email,
    });

    const passwordForm = useForm(
        {
            current_password: '',
            password: '',
            password_confirmation: '',
        },
        { errorBag: 'updatePassword' },
    );

    const deleteForm = useForm(
        {
            password: '',
        },
        { errorBag: 'userDeletion' },
    );

    useEffect(() => {
        if (deleteForm.errors.password) {
            setShowDeleteModal(true);
        }
    }, [deleteForm.errors.password]);

    const handleProfileSubmit = (event: FormEvent) => {
        event.preventDefault();
        profileForm.patch(route('profile.update'), { preserveScroll: true });
    };

    const handlePasswordSubmit = (event: FormEvent) => {
        event.preventDefault();
        passwordForm.put(route('password.update'), {
            preserveScroll: true,
            onSuccess: () => passwordForm.reset(),
        });
    };

    const handleDeleteSubmit = (event: FormEvent) => {
        event.preventDefault();
        deleteForm.delete(route('profile.destroy'), {
            preserveScroll: true,
            onFinish: () => deleteForm.reset(),
        });
    };

    return (
        <AppPage>
            <PageCard title="Perfil" description="Dados da sua conta de acesso ao sistema.">
                {flash.status === 'profile-updated' && (
                    <Alert type="success" dismissible>
                        Perfil atualizado.
                    </Alert>
                )}

                {flash.status === 'password-updated' && (
                    <Alert type="success" dismissible>
                        Senha atualizada.
                    </Alert>
                )}

                <div className="space-y-6">
                    <FormCard title="Informações do perfil" description="Nome e e-mail usados no login.">
                        <form onSubmit={handleProfileSubmit} className="max-w-xl space-y-4">
                            <TextInput
                                id="name"
                                label="Nome"
                                value={profileForm.data.name}
                                onChange={(event) => profileForm.setData('name', event.target.value)}
                                required
                                autoComplete="name"
                                error={profileForm.errors.name}
                            />
                            <TextInput
                                id="email"
                                label="E-mail"
                                type="email"
                                value={profileForm.data.email}
                                onChange={(event) => profileForm.setData('email', event.target.value)}
                                required
                                autoComplete="username"
                                error={profileForm.errors.email}
                            />
                            <SaveButton processing={profileForm.processing} />
                        </form>
                    </FormCard>

                    <FormCard title="Senha" description="Use uma senha longa e difícil de adivinhar.">
                        <form onSubmit={handlePasswordSubmit} className="max-w-xl space-y-4">
                            <PasswordInput
                                id="update_password_current_password"
                                label="Senha atual"
                                value={passwordForm.data.current_password}
                                onChange={(event) => passwordForm.setData('current_password', event.target.value)}
                                autoComplete="current-password"
                                error={passwordForm.errors.current_password}
                            />
                            <PasswordInput
                                id="update_password_password"
                                label="Nova senha"
                                value={passwordForm.data.password}
                                onChange={(event) => passwordForm.setData('password', event.target.value)}
                                autoComplete="new-password"
                                error={passwordForm.errors.password}
                            />
                            <PasswordInput
                                id="update_password_password_confirmation"
                                label="Confirmar senha"
                                value={passwordForm.data.password_confirmation}
                                onChange={(event) => passwordForm.setData('password_confirmation', event.target.value)}
                                autoComplete="new-password"
                                error={passwordForm.errors.password_confirmation}
                            />
                            <SaveButton processing={passwordForm.processing} />
                        </form>
                    </FormCard>

                    <FormCard
                        title="Excluir conta"
                        description="Todos os dados desta conta serão apagados. Esta ação não pode ser desfeita."
                    >
                        <DangerButton type="button" onClick={() => setShowDeleteModal(true)}>
                            Excluir conta
                        </DangerButton>

                        <Modal show={showDeleteModal} onClose={() => setShowDeleteModal(false)} title="Excluir sua conta?">
                            <form onSubmit={handleDeleteSubmit} className="space-y-4">
                                <p className="text-sm text-muted-foreground">
                                    Digite sua senha para confirmar. Depois disso, a conta e os dados associados serão apagados.
                                </p>
                                <PasswordInput
                                    id="password"
                                    label="Senha"
                                    value={deleteForm.data.password}
                                    onChange={(event) => deleteForm.setData('password', event.target.value)}
                                    error={deleteForm.errors.password}
                                />
                                <div className="flex justify-end gap-2">
                                    <CancelButton type="button" onClick={() => setShowDeleteModal(false)} />
                                    <DangerButton type="submit" processing={deleteForm.processing}>
                                        Excluir conta
                                    </DangerButton>
                                </div>
                            </form>
                        </Modal>
                    </FormCard>
                </div>
            </PageCard>
        </AppPage>
    );
}

Edit.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Edit;
