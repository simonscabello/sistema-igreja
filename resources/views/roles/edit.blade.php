<x-app-layout>
    <x-page-card title="Editar Role">
        @if($errors->any())
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
            </x-alert>
        @endif

        <form action="{{ route('roles.update', $role) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4">
                <x-text-input label="Nome" name="name" :value="old('name', $role->name)" placeholder="Digite o nome do role" required="true" />
            </div>

            <div class="border-t border-neutral-medium pt-6">
                <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Permissões</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($permissions as $permission)
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="permission_{{ $permission->id }}"
                                   name="permissions[]"
                                   value="{{ $permission->name }}"
                                   @checked(in_array($permission->name, old('permissions', $role->permissions->pluck('name')->toArray())))
                                   class="rounded border-neutral-medium text-primary focus:ring-primary focus:ring-offset-0">
                            <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-neutral-dark dark:text-gray-300">
                                {{ $permission->name }}
                            </label>
                        </div>
                    @empty
                        <p class="text-neutral-medium dark:text-gray-500">Nenhuma permissão encontrada. <a href="{{ route('permissions.create') }}" class="text-primary hover:underline">Criar permissão</a></p>
                    @endforelse
                </div>
            </div>

            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex gap-4">
                    <a href="{{ route('roles.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancelar
                    </a>
                    <x-primary-button type="submit">
                        Atualizar
                    </x-primary-button>
                </div>
            </div>
        </form>
    </x-page-card>
</x-app-layout>
