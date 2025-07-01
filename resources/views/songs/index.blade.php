<x-app-layout>
    <x-page-card title="Músicas" actions="{{ route('songs.create') }}">
        @if(session('success'))
            <x-alert type="success" dismissible>
                <span class="font-medium">Sucesso!</span> {{ session('success') }}
            </x-alert>
        @endif

        <div class="flex justify-between items-center mb-4">
            <div class="flex-1">
                <form action="{{ route('songs.index') }}" method="GET" class="flex gap-2">
                    <x-text-input name="search" placeholder="Buscar músicas..." value="{{ request('search') }}" />
                    <select name="tag" class="border-neutral-medium dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary rounded-md shadow-sm px-4 py-3 text-sm">
                        <option value="">Todas as tags</option>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Tonalidade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Tags</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Links</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                    @forelse($songs as $song)
                        <tr class="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300 font-medium">
                                {{ $song->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                @if($song->key)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                        {{ $song->key }}
                                    </span>
                                @else
                                    <span class="text-neutral-medium dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                @if($song->tags->count() > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($song->tags->take(3) as $tag)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                        @if($song->tags->count() > 3)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                                +{{ $song->tags->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-neutral-medium dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">
                                <div class="flex gap-2">
                                    @if($song->youtube_link)
                                        <a href="{{ $song->youtube_link }}" target="_blank" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if($song->spotify_link)
                                        <a href="{{ $song->spotify_link }}" target="_blank" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if($song->lyrics_link)
                                        <a href="{{ $song->lyrics_link }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </a>
                                    @endif
                                    @if($song->chords_link)
                                        <a href="{{ $song->chords_link }}" target="_blank" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-link-button href="{{ route('songs.show', $song) }}" class="mr-2">
                                    Ver
                                </x-link-button>
                                <x-link-button href="{{ route('songs.edit', $song) }}">
                                    Editar
                                </x-link-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                Nenhuma música encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $songs->links() }}
        </div>
    </x-page-card>
</x-app-layout>
