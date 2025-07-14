<x-app-layout>
    <x-page-card title="Novo Role">
        @if($errors->any())
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
            </x-alert>
        @endif

        <form action="{{ route('roles.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-4">
                <x-text-input label="Nome" name="name" :value="old('name')" placeholder="Digite o nome do role" required="true" />
            </div>

            <div class="border-t border-neutral-medium pt-6">
                <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-6">Permissões</h3>
                
                @if(!empty($groupedPermissions))
                    <div class="space-y-8">
                        @foreach($groupedPermissions as $groupName => $group)
                            <div>
                                <h4 class="mb-4 font-semibold text-gray-900 dark:text-white text-base">{{ $groupName }}</h4>
                                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">{{ $group['description'] }}</p>
                                
                                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @foreach($group['permissions'] as $index => $permission)
                                        <li class="w-full @if(!$loop->last) border-b border-gray-200 dark:border-gray-600 @endif">
                                            <div class="flex items-center ps-3">
                                                <input 
                                                    id="permission_{{ $permission->id }}" 
                                                    name="permissions[]" 
                                                    type="checkbox" 
                                                    value="{{ $permission->name }}"
                                                    @checked(in_array($permission->name, old('permissions', [])))
                                                    class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded-sm focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                                >
                                                <label for="permission_{{ $permission->id }}" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                    {{ $permission->display_name ?? ucfirst(str_replace('_', ' ', $permission->name)) }}
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-neutral-medium dark:text-gray-500">
                        Nenhuma permissão encontrada. 
                        <a href="{{ route('permissions.create') }}" class="text-primary hover:underline">Criar permissão</a>
                    </p>
                @endif
            </div>

            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex gap-4">
                    <a href="{{ route('roles.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
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
