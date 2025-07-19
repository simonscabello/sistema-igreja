<x-app-layout>
    <x-page-card title="Visitantes" actions="{{ route('visitors.create') }}">
        @if(session('success'))
            <x-alert type="success" dismissible>
                <span class="font-medium">Sucesso!</span> {{ session('success') }}
            </x-alert>
        @endif

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-3 sm:gap-0">
            <div class="flex-1">
                <form action="{{ route('visitors.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                    <x-text-input name="search" placeholder="Buscar visitantes..." value="{{ request('search') }}" class="flex-1" />
                    <div class="flex items-end">
                        <x-primary-button type="submit" class="w-full sm:w-auto px-4 py-3 text-sm">Buscar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Layout Desktop -->
        <div class="hidden lg:block overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-neutral-medium dark:divide-gray-700">
                <thead class="bg-neutral-light dark:bg-gray-700">
                    <tr>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Nome</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Celular</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Faixa Etária</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Data da Visita</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Contato</th>
                        <th class="px-4 xl:px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                    @forelse($visitors as $visitor)
                        <tr class="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-4 xl:px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">{{ $visitor->name }}</td>
                            <td class="px-4 xl:px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">{{ $visitor->mobile }}</td>
                            <td class="px-4 xl:px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                @if($visitor->age_group)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $visitor->age_group === 'crianca_adolescente' ? 'Criança / Adolescente' : ucfirst($visitor->age_group) }}
                                    </span>
                                @else
                                    <span class="text-neutral-medium dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-4 xl:px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                {{ $visitor->visit_date ? $visitor->visit_date->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-4 xl:px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                @if($visitor->wants_contact)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Sim
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Não
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 xl:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <x-link-button href="{{ route('visitors.show', $visitor) }}" class="text-xs px-3 py-1">
                                        Ver
                                    </x-link-button>
                                    <x-link-button href="{{ route('visitors.edit', $visitor) }}" class="text-xs px-3 py-1">
                                        Editar
                                    </x-link-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 xl:px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                Nenhum visitante encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Layout Mobile -->
        <div class="lg:hidden space-y-3">
            @forelse($visitors as $visitor)
                <div class="bg-white dark:bg-gray-700 border border-neutral-medium dark:border-gray-600 rounded-lg p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="text-base font-semibold text-neutral-dark dark:text-gray-300 truncate mr-2">
                            {{ $visitor->name }}
                        </div>
                        @if($visitor->wants_contact)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 flex-shrink-0">
                                Quer contato
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 gap-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Celular:</span>
                            <span class="text-neutral-dark dark:text-gray-300">{{ $visitor->mobile }}</span>
                        </div>
                        @if($visitor->age_group)
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Faixa Etária:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $visitor->age_group === 'crianca_adolescente' ? 'Criança / Adolescente' : ucfirst($visitor->age_group) }}
                                </span>
                            </div>
                        @endif
                        @if($visitor->visit_date)
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Data da Visita:</span>
                                <span class="text-neutral-dark dark:text-gray-300">{{ $visitor->visit_date->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-neutral-medium dark:border-gray-600">
                        <x-link-button href="{{ route('visitors.show', $visitor) }}" class="text-xs px-3 py-2 flex-1 text-center">
                            Ver
                        </x-link-button>
                        <x-link-button href="{{ route('visitors.edit', $visitor) }}" class="text-xs px-3 py-2 flex-1 text-center">
                            Editar
                        </x-link-button>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-neutral-medium dark:text-gray-500">
                    Nenhum visitante encontrado.
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $visitors->links() }}
        </div>
    </x-page-card>
</x-app-layout>
