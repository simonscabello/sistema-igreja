<x-app-layout>
    <x-page-card title="Novo Usuário">
        @if($errors->any())
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
            </x-alert>
        @endif

        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-text-input label="Nome" name="name" :value="old('name')" placeholder="Digite o nome completo" required="true" />
                <x-text-input label="Email" name="email" type="email" :value="old('email')" placeholder="Digite o email" required="true" />
                <x-text-input label="Senha" name="password" type="password" placeholder="Digite a senha" required="true" />
                <x-text-input label="Confirmar Senha" name="password_confirmation" type="password" placeholder="Confirme a senha" required="true" />
            </div>

            <div class="border-t border-neutral-medium pt-6">
                <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Roles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($roles as $role)
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="role_{{ $role->id }}"
                                   name="roles[]"
                                   value="{{ $role->name }}"
                                   @checked(in_array($role->name, old('roles', [])))
                                   class="rounded border-neutral-medium text-primary focus:ring-primary focus:ring-offset-0">
                            <label for="role_{{ $role->id }}" class="ml-2 text-sm text-neutral-dark dark:text-gray-300">
                                {{ $role->name }}
                            </label>
                        </div>
                    @empty
                        <p class="text-neutral-medium dark:text-gray-500">Nenhum role encontrado. <a href="{{ route('roles.create') }}" class="text-primary hover:underline">Criar role</a></p>
                    @endforelse
                </div>
            </div>

            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex gap-4">
                    <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancelar
                    </a>
                    <x-primary-button type="submit">
                        Salvar
                    </x-primary-button>
                </div>
            </div>
        </form>
    </x-page-card>
</x-app-layout>
