<x-app-layout>
    <x-page-card title="Usuários do Sistema" actions="{{ route('users.create') }}">
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
                <form action="{{ route('users.index') }}" method="GET" class="flex gap-2">
                    <x-text-input name="search" placeholder="Buscar usuários..." value="{{ request('search') }}" />
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Roles</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-base font-semibold text-neutral-dark dark:text-gray-300">{{ $user->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                @if($user->roles->count() > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user->roles as $role)
                                            <span class="inline-block bg-primary text-white text-xs px-2 py-1 rounded-full">
                                                {{ $role->display_name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-neutral-medium dark:text-gray-500">Nenhuma role</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-link-button href="{{ route('users.show', $user) }}" class="mr-2">
                                    Ver
                                </x-link-button>
                                <x-link-button href="{{ route('users.edit', $user) }}" class="mr-2">
                                    Editar
                                </x-link-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                Nenhum usuário encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </x-page-card>
</x-app-layout>
