<x-app-layout>
    <x-page-card title="{{ $song->name }}" actions="{{ route('songs.edit', $song) }}">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-white mb-4">Informações da Música</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Nome</dt>
                            <dd class="mt-1 text-sm text-neutral-dark dark:text-white">{{ $song->name }}</dd>
                        </div>
                        
                        @if($song->key)
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Tonalidade</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                    {{ $song->key }}
                                </span>
                            </dd>
                        </div>
                        @endif
                    </div>
                </div>

                @if($song->tags->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-white mb-4">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($song->tags as $tag)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-white mb-4">Links</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($song->youtube_link)
                        <div class="flex items-center space-x-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                            <a href="{{ $song->youtube_link }}" target="_blank" class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                Assistir no YouTube
                            </a>
                        </div>
                        @endif

                        @if($song->spotify_link)
                        <div class="flex items-center space-x-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/>
                            </svg>
                            <a href="{{ $song->spotify_link }}" target="_blank" class="text-sm font-medium text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                                Ouvir no Spotify
                            </a>
                        </div>
                        @endif

                        @if($song->lyrics_link)
                        <div class="flex items-center space-x-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <a href="{{ $song->lyrics_link }}" target="_blank" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                Ver Letra
                            </a>
                        </div>
                        @endif

                        @if($song->chords_link)
                        <div class="flex items-center space-x-3 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <a href="{{ $song->chords_link }}" target="_blank" class="text-sm font-medium text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300">
                                Ver Cifra
                            </a>
                        </div>
                        @endif
                    </div>

                    @if(!$song->youtube_link && !$song->spotify_link && !$song->lyrics_link && !$song->chords_link)
                        <p class="text-neutral-medium dark:text-gray-400 text-sm">Nenhum link cadastrado para esta música.</p>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-white mb-4">Ações</h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('songs.edit', $song) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark focus:bg-primary-dark active:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Editar Música
                        </a>
                        
                        <form action="{{ route('songs.destroy', $song) }}" method="POST" class="inline-block w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" onclick="return confirm('Tem certeza que deseja excluir esta música?')">
                                Excluir Música
                            </button>
                        </form>
                        
                        <a href="{{ route('songs.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-neutral-medium dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-neutral-dark dark:text-gray-300 uppercase tracking-widest hover:bg-neutral-dark dark:hover:bg-gray-500 focus:bg-neutral-dark dark:focus:bg-gray-500 active:bg-neutral-dark dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Voltar à Lista
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-white mb-4">Informações do Sistema</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div>
                            <dt class="font-medium text-neutral-medium dark:text-gray-400">ID</dt>
                            <dd class="text-neutral-dark dark:text-white">{{ $song->id }}</dd>
                        </div>
                        
                        <div>
                            <dt class="font-medium text-neutral-medium dark:text-gray-400">Criado em</dt>
                            <dd class="text-neutral-dark dark:text-white">{{ $song->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="font-medium text-neutral-medium dark:text-gray-400">Última atualização</dt>
                            <dd class="text-neutral-dark dark:text-white">{{ $song->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-page-card>
</x-app-layout> 