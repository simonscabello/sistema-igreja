<x-app-layout>
    <x-page-card title="Membros" actions="{{ route('members.create') }}">
        @if(session('success'))
            <x-alert type="success" dismissible>
                <span class="font-medium">Sucesso!</span> {{ session('success') }}
            </x-alert>
        @endif

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-3 sm:gap-0">
            <div class="flex-1">
                <form action="{{ route('members.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                    <x-text-input name="search" placeholder="Buscar membros..." value="{{ request('search') }}" class="flex-1" />
                    <div class="flex items-end">
                        <x-primary-button type="submit" class="w-full sm:w-auto px-4 py-3 text-sm">Buscar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Layout Desktop -->
        <div class="hidden md:block overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-neutral-medium dark:divide-gray-700">
                <thead class="bg-neutral-light dark:bg-gray-700">
                    <tr>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Nome</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Telefone</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Data de nascimento</th>
                        <th class="px-4 lg:px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                    @forelse($members as $member)
                        <tr class="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <x-avatar :member="$member" class="mr-3" />
                                    <div class="ps-3">
                                        <div class="text-base font-semibold text-neutral-dark dark:text-gray-300">{{ $member->full_name }}</div>
                                        <div class="font-normal text-gray-500 dark:text-gray-400">{{ $member->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">{{ $member->mobile }}</td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">{{ $member->birth_date ? \Carbon\Carbon::parse($member->birth_date)->format('d/m/Y') : '-' }}</td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <x-link-button href="{{ route('members.show', $member) }}" class="text-xs px-3 py-1">
                                        Ver
                                    </x-link-button>
                                    <x-link-button href="{{ route('members.edit', $member) }}" class="text-xs px-3 py-1">
                                        Editar
                                    </x-link-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 lg:px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                Nenhum membro encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Layout Mobile -->
        <div class="md:hidden space-y-3">
            @forelse($members as $member)
                <div class="bg-white dark:bg-gray-700 border border-neutral-medium dark:border-gray-600 rounded-lg p-4 space-y-3">
                    <div class="flex items-center space-x-3">
                        <x-avatar :member="$member" />
                        <div class="flex-1 min-w-0">
                            <div class="text-base font-semibold text-neutral-dark dark:text-gray-300 truncate">{{ $member->full_name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $member->email }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-2 text-sm">
                        @if($member->mobile)
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Telefone:</span>
                                <span class="text-neutral-dark dark:text-gray-300">{{ $member->mobile }}</span>
                            </div>
                        @endif
                        @if($member->birth_date)
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Nascimento:</span>
                                <span class="text-neutral-dark dark:text-gray-300">{{ \Carbon\Carbon::parse($member->birth_date)->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-neutral-medium dark:border-gray-600">
                        <x-link-button href="{{ route('members.show', $member) }}" class="text-xs px-3 py-2 flex-1 text-center">
                            Ver
                        </x-link-button>
                        <x-link-button href="{{ route('members.edit', $member) }}" class="text-xs px-3 py-2 flex-1 text-center">
                            Editar
                        </x-link-button>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-neutral-medium dark:text-gray-500">
                    Nenhum membro encontrado.
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $members->links() }}
        </div>
    </x-page-card>
</x-app-layout>
