<x-app-layout>
    <x-page-card title="Detalhes do Role">
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-dark dark:text-gray-300">Nome</label>
                    <p class="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">{{ $role->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-dark dark:text-gray-300">Número de Usuários</label>
                    <p class="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">{{ $role->users->count() }}</p>
                </div>
            </div>

            <div class="border-t border-neutral-medium pt-6">
                <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Permissões Associadas</h3>
                @if($role->permissions->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($role->permissions as $permission)
                            <div class="bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">
                                <span class="text-sm text-neutral-dark dark:text-gray-300">{{ $permission->name }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-neutral-medium dark:text-gray-500">Nenhuma permissão associada a este role.</p>
                @endif
            </div>

            <div class="border-t border-neutral-medium pt-6">
                <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Usuários com este Role</h3>
                @if($role->users->count() > 0)
                    <div class="space-y-2">
                        @foreach($role->users as $user)
                            <div class="bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md flex justify-between items-center">
                                <div>
                                    <span class="text-sm font-medium text-neutral-dark dark:text-gray-300">{{ $user->name }}</span>
                                    <span class="text-xs text-neutral-medium dark:text-gray-400 ml-2">{{ $user->email }}</span>
                                </div>
                                <a href="{{ route('users.show', $user) }}" class="text-primary hover:underline text-sm">Ver</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-neutral-medium dark:text-gray-500">Nenhum usuário possui este role.</p>
                @endif
            </div>

            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex gap-4">
                    <a href="{{ route('roles.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
                        Voltar
                    </a>
                    <x-link-button href="{{ route('roles.edit', $role) }}">
                        Editar
                    </x-link-button>
                </div>
            </div>
        </div>
    </x-page-card>
</x-app-layout>
