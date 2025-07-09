<x-app-layout>
    <x-page-card title="Permissões" actions="{{ route('permissions.create') }}">
        @if(session('success'))
            <x-alert type="success" dismissible>
                <span class="font-medium">Sucesso!</span> {{ session('success') }}
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> {{ session('error') }}
            </x-alert>
        @endif

        <div class="flex justify-between items-center mb-4">
            <div class="flex-1">
                <form action="{{ route('permissions.index') }}" method="GET" class="flex gap-2">
                    <x-text-input name="search" placeholder="Buscar permissões..." value="{{ request('search') }}" />
                    <div class="flex items-end">
                        <x-primary-button type="submit" class="px-4 py-3 text-sm">Buscar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-neutral-medium dark:divide-gray-700">
                <thead class="bg-neutral-light dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Roles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Criado em</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                    @forelse($permissions as $permission)
                        <tr class="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-base font-semibold text-neutral-dark dark:text-gray-300">{{ $permission->display_name ?? $permission->name }}</div>
                                <div class="text-xs text-neutral-medium dark:text-gray-400">{{ $permission->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                {{ $permission->roles->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                {{ $permission->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-link-button href="{{ route('permissions.show', $permission) }}" class="mr-2">
                                    Ver
                                </x-link-button>
                                <x-link-button href="{{ route('permissions.edit', $permission) }}" class="mr-2">
                                    Editar
                                </x-link-button>
                                <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Tem certeza que deseja excluir esta permissão?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button type="submit">
                                        Excluir
                                    </x-danger-button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                Nenhuma permissão encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $permissions->links() }}
        </div>
    </x-page-card>
</x-app-layout>
