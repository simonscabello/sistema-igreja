import { FormEvent, useEffect, useState } from 'react';
import { useForm, usePage } from '@inertiajs/react';
import AppLayout, { AppPage } from '@/layouts/AppLayout';
import { Alert, Modal } from '@/components/ui/Alert';
import { DangerButton, PrimaryButton, SecondaryButton } from '@/components/ui/Button';
import { TextInput } from '@/components/ui/Input';
import { PageCard } from '@/components/ui/PageCard';
import { PageProps } from '@/types';
import { route } from '@/utils';

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
            <PageCard title="Perfil">
                {flash.status === 'profile-updated' && (
                    <Alert type="success" dismissible>
                        <span className="font-medium">Sucesso!</span> Perfil atualizado com sucesso.
                    </Alert>
                )}

                {flash.status === 'password-updated' && (
                    <Alert type="success" dismissible>
                        <span className="font-medium">Sucesso!</span> Senha atualizada com sucesso.
                    </Alert>
                )}

                <div className="space-y-6">
                    <section className="p-4 sm:p-8 shadow dark:shadow-gray-700 sm:rounded-lg">
                        <div className="max-w-xl">
                            <header>
                                <h2 className="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Informações do Perfil
                                </h2>
                                <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Atualize as informações do perfil da sua conta e endereço de e-mail.
                                </p>
                            </header>

                            <form onSubmit={handleProfileSubmit} className="mt-6 space-y-6">
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

                                <PrimaryButton type="submit" disabled={profileForm.processing}>
                                    Salvar
                                </PrimaryButton>
                            </form>
                        </div>
                    </section>

                    <section className="p-4 sm:p-8 shadow dark:shadow-gray-700 sm:rounded-lg">
                        <div className="max-w-xl">
                            <header>
                                <h2 className="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Atualizar Senha
                                </h2>
                                <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Certifique-se de que sua conta está usando uma senha longa e aleatória para manter a segurança.
                                </p>
                            </header>

                            <form onSubmit={handlePasswordSubmit} className="mt-6 space-y-6">
                                <TextInput
                                    id="update_password_current_password"
                                    label="Senha Atual"
                                    type="password"
                                    value={passwordForm.data.current_password}
                                    onChange={(event) => passwordForm.setData('current_password', event.target.value)}
                                    autoComplete="current-password"
                                    error={passwordForm.errors.current_password}
                                />

                                <TextInput
                                    id="update_password_password"
                                    label="Nova Senha"
                                    type="password"
                                    value={passwordForm.data.password}
                                    onChange={(event) => passwordForm.setData('password', event.target.value)}
                                    autoComplete="new-password"
                                    error={passwordForm.errors.password}
                                />

                                <TextInput
                                    id="update_password_password_confirmation"
                                    label="Confirmar Senha"
                                    type="password"
                                    value={passwordForm.data.password_confirmation}
                                    onChange={(event) => passwordForm.setData('password_confirmation', event.target.value)}
                                    autoComplete="new-password"
                                    error={passwordForm.errors.password_confirmation}
                                />

                                <PrimaryButton type="submit" disabled={passwordForm.processing}>
                                    Salvar
                                </PrimaryButton>
                            </form>
                        </div>
                    </section>

                    <section className="p-4 sm:p-8 shadow dark:shadow-gray-700 sm:rounded-lg">
                        <div className="max-w-xl space-y-6">
                            <header>
                                <h2 className="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Excluir Conta
                                </h2>
                                <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Depois que sua conta for excluída, todos os seus recursos e dados serão permanentemente excluídos. Antes de excluir sua conta, faça o download de quaisquer dados ou informações que você deseja manter.
                                </p>
                            </header>

                            <DangerButton type="button" onClick={() => setShowDeleteModal(true)}>
                                Excluir Conta
                            </DangerButton>

                            <Modal
                                show={showDeleteModal}
                                onClose={() => setShowDeleteModal(false)}
                                title="Tem certeza que deseja excluir sua conta?"
                            >
                                <form onSubmit={handleDeleteSubmit}>
                                    <p className="text-sm text-gray-600 dark:text-gray-400">
                                        Depois que sua conta for excluída, todos os seus recursos e dados serão permanentemente excluídos. Por favor, digite sua senha para confirmar que deseja excluir permanentemente sua conta.
                                    </p>

                                    <div className="mt-6">
                                        <TextInput
                                            id="password"
                                            label="Senha"
                                            type="password"
                                            value={deleteForm.data.password}
                                            onChange={(event) => deleteForm.setData('password', event.target.value)}
                                            placeholder="Senha"
                                            error={deleteForm.errors.password}
                                        />
                                    </div>

                                    <div className="mt-6 flex justify-end gap-3">
                                        <SecondaryButton type="button" onClick={() => setShowDeleteModal(false)}>
                                            Cancelar
                                        </SecondaryButton>
                                        <DangerButton type="submit" disabled={deleteForm.processing}>
                                            Excluir Conta
                                        </DangerButton>
                                    </div>
                                </form>
                            </Modal>
                        </div>
                    </section>
                </div>
            </PageCard>
        </AppPage>
    );
}

Edit.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;

export default Edit;
