<x-app-layout>
    <x-page-card title="Editar Música">
        <form method="POST" action="{{ route('songs.update', $song) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <x-input-label for="name" value="Nome da Música" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $song->name)" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="key" value="Tonalidade" />
                    <x-text-input id="key" name="key" type="text" class="mt-1 block w-full" :value="old('key', $song->key)" maxlength="10" placeholder="Ex: C, Dm, F#m" />
                    <x-input-error :messages="$errors->get('key')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="youtube_link" value="Link do YouTube" />
                    <x-text-input id="youtube_link" name="youtube_link" type="url" class="mt-1 block w-full" :value="old('youtube_link', $song->youtube_link)" placeholder="https://youtube.com/watch?v=..." />
                    <x-input-error :messages="$errors->get('youtube_link')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="spotify_link" value="Link do Spotify" />
                    <x-text-input id="spotify_link" name="spotify_link" type="url" class="mt-1 block w-full" :value="old('spotify_link', $song->spotify_link)" placeholder="https://open.spotify.com/track/..." />
                    <x-input-error :messages="$errors->get('spotify_link')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="lyrics_link" value="Link da Letra" />
                    <x-text-input id="lyrics_link" name="lyrics_link" type="url" class="mt-1 block w-full" :value="old('lyrics_link', $song->lyrics_link)" placeholder="https://..." />
                    <x-input-error :messages="$errors->get('lyrics_link')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="chords_link" value="Link da Cifra" />
                    <x-text-input id="chords_link" name="chords_link" type="url" class="mt-1 block w-full" :value="old('chords_link', $song->chords_link)" placeholder="https://..." />
                    <x-input-error :messages="$errors->get('chords_link')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="tags" value="Tags" />
                    <div class="mt-1">
                        <select id="tags" name="tags[]" multiple class="border-neutral-medium dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary rounded-md shadow-sm w-full" size="6">
                            @foreach($tags as $tag)
                                <option value="{{ $tag->name }}" {{ in_array($tag->name, old('tags', $song->tags->pluck('name')->toArray())) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-neutral-medium dark:text-gray-400">
                            Pressione Ctrl (ou Cmd no Mac) para selecionar múltiplas tags. Você também pode digitar novas tags no campo abaixo.
                        </p>
                    </div>
                    <div class="mt-2">
                        <x-text-input id="new_tags" type="text" class="block w-full" placeholder="Digite novas tags separadas por vírgula" />
                        <p class="mt-1 text-sm text-neutral-medium dark:text-gray-400">
                            Ex: adoração, louvor, comunhão
                        </p>
                    </div>
                    <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>Atualizar Música</x-primary-button>
                <a href="{{ route('songs.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-medium dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-neutral-dark dark:text-gray-300 uppercase tracking-widest hover:bg-neutral-dark dark:hover:bg-gray-500 focus:bg-neutral-dark dark:focus:bg-gray-500 active:bg-neutral-dark dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Cancelar
                </a>
            </div>
        </form>
    </x-page-card>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newTagsInput = document.getElementById('new_tags');
            const tagsSelect = document.getElementById('tags');
            
            newTagsInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const newTags = this.value.split(',').map(tag => tag.trim()).filter(tag => tag);
                    
                    newTags.forEach(tag => {
                        const option = document.createElement('option');
                        option.value = tag;
                        option.textContent = tag;
                        option.selected = true;
                        tagsSelect.appendChild(option);
                    });
                    
                    this.value = '';
                }
            });
        });
    </script>
</x-app-layout> 