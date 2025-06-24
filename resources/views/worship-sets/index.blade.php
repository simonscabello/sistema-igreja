<x-app-layout>
    <x-page-card title="Repertórios por Culto">
        <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
            <div class="flex-1">
                <form method="GET" action="{{ route('worship-sets.index') }}" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <x-text-input 
                            type="text" 
                            name="search" 
                            placeholder="Buscar por cantor ou ministro..." 
                            :value="request('search')"
                            class="w-full"
                        />
                    </div>
                    <div class="flex gap-2">
                        <select name="period" class="w-32 border-neutral-medium dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary rounded-md shadow-sm">
                            <option value="">Período</option>
                            <option value="manha" {{ request('period') === 'manha' ? 'selected' : '' }}>Manhã</option>
                            <option value="noite" {{ request('period') === 'noite' ? 'selected' : '' }}>Noite</option>
                        </select>
                        <x-text-input 
                            type="date" 
                            name="date_from" 
                            :value="request('date_from')"
                            class="w-40"
                        />
                        <x-text-input 
                            type="date" 
                            name="date_to" 
                            :value="request('date_to')"
                            class="w-40"
                        />
                        <x-primary-button type="submit">
                            Filtrar
                        </x-primary-button>
                        @if(request('search') || request('period') || request('date_from') || request('date_to'))
                            <a href="{{ route('worship-sets.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-medium dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-neutral-dark dark:text-gray-300 uppercase tracking-widest hover:bg-neutral-dark dark:hover:bg-gray-500 focus:bg-neutral-dark dark:focus:bg-gray-500 active:bg-neutral-dark dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            <x-primary-button href="{{ route('worship-sets.create') }}">
                Novo Repertório
            </x-primary-button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-medium dark:divide-gray-700">
                <thead class="bg-neutral-light dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">
                            Data
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">
                            Período
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">
                            Cantor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">
                            Ministro
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">
                            Músicas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">
                            Tons
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                    @forelse($worshipSets as $worshipSet)
                        <tr class="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300 font-medium">
                                {{ $worshipSet->formatted_date }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $worshipSet->period === 'manha' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                    {{ $worshipSet->period_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                {{ $worshipSet->singer }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                {{ $worshipSet->preacher }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($worshipSet->songs->take(3) as $song)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                            {{ $song->name }}
                                        </span>
                                    @endforeach
                                    @if($worshipSet->songs->count() > 3)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                            +{{ $worshipSet->songs->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                @if($worshipSet->used_keys)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                        {{ $worshipSet->used_keys }}
                                    </span>
                                @else
                                    <span class="text-neutral-medium dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-link-button href="{{ route('worship-sets.show', $worshipSet) }}" class="mr-2">
                                    Ver
                                </x-link-button>
                                <x-link-button href="{{ route('worship-sets.edit', $worshipSet) }}" class="mr-2">
                                    Editar
                                </x-link-button>
                                <x-link-button href="{{ route('worship-sets.clone', $worshipSet) }}" class="mr-2">
                                    Clonar
                                </x-link-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                Nenhum repertório encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $worshipSets->links() }}
        </div>
    </x-page-card>
</x-app-layout> 