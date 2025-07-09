<x-app-layout>
    <x-page-card title="Detalhes do Usuário">
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-dark dark:text-gray-300">Nome</label>
                    <p class="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">{{ $user->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-dark dark:text-gray-300">Email</label>
                    <p class="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">{{ $user->email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-dark dark:text-gray-300">Criado em</label>
                    <p class="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-dark dark:text-gray-300">Última atualização</label>
                    <p class="mt-1 text-sm text-neutral-medium dark:text-gray-400 bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="border-t border-neutral-medium pt-6">
                <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Roles Associados</h3>
                @if($user->roles->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($user->roles as $role)
                            <div class="bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md flex justify-between items-center">
                                <span class="text-sm text-neutral-dark dark:text-gray-300">{{ $role->name }}</span>
                                <a href="{{ route('roles.show', $role) }}" class="text-primary hover:underline text-sm">Ver</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-neutral-medium dark:text-gray-500">Nenhum role associado a este usuário.</p>
                @endif
            </div>

            <div class="border-t border-neutral-medium pt-6">
                <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Permissões (através dos roles)</h3>
                @php
                    $permissions = $user->getAllPermissions();
                @endphp
                @if($permissions->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($permissions as $permission)
                            <div class="bg-neutral-light dark:bg-gray-700 px-3 py-2 rounded-md">
                                <span class="text-sm text-neutral-dark dark:text-gray-300">{{ $permission->name }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-neutral-medium dark:text-gray-500">Nenhuma permissão associada a este usuário.</p>
                @endif
            </div>

            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex gap-4">
                    <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
                        Voltar
                    </a>
                    <x-link-button href="{{ route('users.edit', $user) }}">
                        Editar
                    </x-link-button>
                </div>
            </div>
        </div>
    </x-page-card>
</x-app-layout>
