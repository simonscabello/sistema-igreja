<x-app-layout>
    <x-page-card title="Repertório - {{ $worshipSet->formatted_date }}" actions="{{ route('worship-sets.edit', $worshipSet) }}">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-white mb-4">Informações do Culto</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Data</dt>
                            <dd class="mt-1 text-sm text-neutral-dark dark:text-white font-medium">{{ $worshipSet->formatted_date }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Período</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $worshipSet->period === 'manha' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                    {{ $worshipSet->period_label }}
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Cantor</dt>
                            <dd class="mt-1 text-sm text-neutral-dark dark:text-white">{{ $worshipSet->singer }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Ministro</dt>
                            <dd class="mt-1 text-sm text-neutral-dark dark:text-white">{{ $worshipSet->preacher }}</dd>
                        </div>
                    </div>
                </div>

                @if($worshipSet->songs->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-white mb-4">Repertório</h3>
                    
                    <div class="space-y-3">
                        @foreach($worshipSet->songs as $index => $song)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <span class="flex items-center justify-center w-8 h-8 bg-primary text-white text-sm font-medium rounded-full">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <h4 class="font-medium text-neutral-dark dark:text-white">{{ $song->name }}</h4>
                                        @if($song->key)
                                            <p class="text-sm text-neutral-medium dark:text-gray-400">Tonalidade original: {{ $song->key }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($song->pivot->key_used)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Tom usado: {{ $song->pivot->key_used }}
                                        </span>
                                    @endif
                                    <div class="flex gap-1">
                                        @if($song->youtube_link)
                                            <a href="{{ $song->youtube_link }}" target="_blank" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                </svg>
                                            </a>
                                        @endif
                                        @if($song->spotify_link)
                                            <a href="{{ $song->spotify_link }}" target="_blank" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/>
                                                </svg>
                                            </a>
                                        @endif
                                        @if($song->lyrics_link)
                                            <a href="{{ $song->lyrics_link }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </a>
                                        @endif
                                        @if($song->chords_link)
                                            <a href="{{ $song->chords_link }}" target="_blank" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($worshipSet->order_notes || $worshipSet->observations)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-white mb-4">Observações</h3>
                    
                    @if($worshipSet->order_notes)
                    <div class="mb-4">
                        <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400 mb-2">Ordem das Músicas</dt>
                        <dd class="text-sm text-neutral-dark dark:text-white whitespace-pre-wrap">{{ $worshipSet->order_notes }}</dd>
                    </div>
                    @endif
                    
                    @if($worshipSet->observations)
                    <div>
                        <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400 mb-2">Observações Gerais</dt>
                        <dd class="text-sm text-neutral-dark dark:text-white whitespace-pre-wrap">{{ $worshipSet->observations }}</dd>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-white mb-4">Ações</h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('worship-sets.edit', $worshipSet) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark focus:bg-primary-dark active:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Editar Repertório
                        </a>
                        
                        <a href="{{ route('worship-sets.clone', $worshipSet) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Clonar Repertório
                        </a>
                        
                        <form action="{{ route('worship-sets.destroy', $worshipSet) }}" method="POST" class="inline-block w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" onclick="return confirm('Tem certeza que deseja excluir este repertório?')">
                                Excluir Repertório
                            </button>
                        </form>
                        
                        <a href="{{ route('worship-sets.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-neutral-medium dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-neutral-dark dark:text-gray-300 uppercase tracking-widest hover:bg-neutral-dark dark:hover:bg-gray-500 focus:bg-neutral-dark dark:focus:bg-gray-500 active:bg-neutral-dark dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Voltar à Lista
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-white mb-4">Informações do Sistema</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div>
                            <dt class="font-medium text-neutral-medium dark:text-gray-400">ID</dt>
                            <dd class="text-neutral-dark dark:text-white">{{ $worshipSet->id }}</dd>
                        </div>
                        
                        <div>
                            <dt class="font-medium text-neutral-medium dark:text-gray-400">Criado em</dt>
                            <dd class="text-neutral-dark dark:text-white">{{ $worshipSet->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="font-medium text-neutral-medium dark:text-gray-400">Última atualização</dt>
                            <dd class="text-neutral-dark dark:text-white">{{ $worshipSet->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-page-card>
</x-app-layout> 