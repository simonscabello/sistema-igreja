<x-app-layout>
    <x-page-card :title="__('Perfil')">
        @if(session('status') === 'profile-updated')
            <x-alert type="success" dismissible>
                <span class="font-medium">Sucesso!</span> Perfil atualizado com sucesso.
            </x-alert>
        @endif

        @if(session('status') === 'password-updated')
            <x-alert type="success" dismissible>
                <span class="font-medium">Sucesso!</span> Senha atualizada com sucesso.
            </x-alert>
        @endif

        <div class="space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </x-page-card>
</x-app-layout>
