<x-app-layout>
    <x-page-card title="Editar Repertório">
        <form method="POST" action="{{ route('worship-sets.update', $worshipSet) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-text-input id="singer" name="singer" type="text" class="mt-1 block w-full" :value="old('singer', $worshipSet->singer)" required autofocus label="Cantor" required="true" />
                    <x-input-error :messages="$errors->get('singer')" class="mt-2" />
                </div>

                <div>
                    <x-text-input id="preacher" name="preacher" type="text" class="mt-1 block w-full" :value="old('preacher', $worshipSet->preacher)" required label="Ministro" required="true" />
                    <x-input-error :messages="$errors->get('preacher')" class="mt-2" />
                </div>

                <div>
                    <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date', $worshipSet->date->format('Y-m-d'))" required label="Data" required="true" />
                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                </div>

                <div>
                    <x-select id="period" name="period" class="mt-1 block w-full" required label="Período" required="true" :options="['manha' => 'Manhã', 'noite' => 'Noite']" :selected="old('period', $worshipSet->period)">
                    </x-select>
                    <x-input-error :messages="$errors->get('period')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="songs" value="Músicas" required="true" />
                <div class="mt-1">
                    <select id="songs" name="songs[]" multiple class="border-neutral-medium dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary rounded-md shadow-sm w-full" size="8" required>
                        @foreach($songs as $song)
                            <option value="{{ $song->id }}" {{ in_array($song->id, old('songs', $worshipSet->songs->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $song->name }} @if($song->key)({{ $song->key }})@endif
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-neutral-medium dark:text-gray-400">
                        Pressione Ctrl (ou Cmd no Mac) para selecionar múltiplas músicas. A ordem de seleção será a ordem de execução.
                    </p>
                </div>
                <x-input-error :messages="$errors->get('songs')" class="mt-2" />
            </div>

            <div id="selected-songs-container" class="space-y-2">
                @foreach($worshipSet->songs as $index => $song)
                    <div class="flex items-center gap-2 p-2 bg-gray-50 dark:bg-gray-700 rounded">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $index + 1 }}.</span>
                        <span class="flex-1 text-sm">{{ $song->name }}</span>
                        <input type="text" name="song_keys[{{ $song->id }}]" placeholder="Tom" class="w-16 text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded" value="{{ old("song_keys.{$song->id}", $song->pivot->key_used) }}">
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-textarea id="order_notes" name="order_notes" class="mt-1 block w-full" rows="4" placeholder="Observações sobre a ordem das músicas..." label="Ordem das Músicas / Observações">{{ old('order_notes', $worshipSet->order_notes) }}</x-textarea>
                    <x-input-error :messages="$errors->get('order_notes')" class="mt-2" />
                </div>

                <div>
                    <x-textarea id="observations" name="observations" class="mt-1 block w-full" rows="4" placeholder="Observações gerais sobre o culto..." label="Observações Gerais">{{ old('observations', $worshipSet->observations) }}</x-textarea>
                    <x-input-error :messages="$errors->get('observations')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <x-secondary-button type="button" onclick="window.history.back()">
                    Cancelar
                </x-secondary-button>
                <x-primary-button type="submit">
                    Atualizar
                </x-primary-button>
            </div>
        </form>
    </x-page-card>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const songsSelect = document.getElementById('songs');
            const container = document.getElementById('selected-songs-container');

            function updateSelectedSongs() {
                container.innerHTML = '';
                const selectedOptions = Array.from(songsSelect.selectedOptions);
                
                selectedOptions.forEach((option, index) => {
                    const songId = option.value;
                    const songName = option.textContent;
                    
                    const div = document.createElement('div');
                    div.className = 'flex items-center gap-2 p-2 bg-gray-50 dark:bg-gray-700 rounded';
                    div.innerHTML = `
                        <span class=\"text-sm font-medium text-gray-600 dark:text-gray-300\">${index + 1}.</span>
                        <span class=\"flex-1 text-sm\">${songName}</span>
                        <input type=\"text\" name=\"song_keys[${songId}]\" placeholder=\"Tom\" class=\"w-16 text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded\">
                    `;
                    container.appendChild(div);
                });
            }

            songsSelect.addEventListener('change', updateSelectedSongs);
        });
    </script>
</x-app-layout> 