<x-app-layout>
    <x-page-card title="Nova Transação Financeira">
        @if($errors->any())
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
            </x-alert>
        @endif

        <form action="{{ route('financial-transactions.store') }}" method="POST" x-data="{
            selectedCategory: '{{ old('financial_category_id') }}',
            categories: {{ $categories->toJson() }},
            get subcategories() {
                if (!this.selectedCategory) return [];
                const category = this.categories.find(c => c.id == this.selectedCategory);
                return category ? category.subcategories : [];
            }
        }">
            @csrf

            <div class="max-w-2xl">
                <div class="space-y-6">
                    <x-select label="Categoria"
                              name="financial_category_id"
                              :options="$categories->pluck('name', 'id')->toArray()"
                              :selected="old('financial_category_id')"
                              x-model="selectedCategory"
                              required />

                    <div>
                        <x-input-label for="financial_subcategory_id" value="Subcategoria" />
                        <select id="financial_subcategory_id"
                                name="financial_subcategory_id"
                                required
                                class="mt-1 block w-full border-neutral-medium dark:border-gray-600 rounded-md shadow-sm focus:border-primary focus:ring-primary bg-white dark:bg-gray-700 text-neutral-dark dark:text-white">
                            <option value="">Selecione uma subcategoria</option>
                            <template x-for="subcategory in subcategories" :key="subcategory.id">
                                <option :value="subcategory.id" x-text="subcategory.name"></option>
                            </template>
                        </select>
                        <x-input-error :messages="$errors->get('financial_subcategory_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="campaign_id" value="Campanha (Opcional)" />
                        <select id="campaign_id"
                                name="campaign_id"
                                class="mt-1 block w-full border-neutral-medium dark:border-gray-600 rounded-md shadow-sm focus:border-primary focus:ring-primary bg-white dark:bg-gray-700 text-neutral-dark dark:text-white">
                            <option value="">Nenhuma campanha</option>
                            @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" {{ old('campaign_id', request('campaign_id')) == $campaign->id ? 'selected' : '' }}>
                                    {{ $campaign->name }} (R$ {{ number_format($campaign->goal_amount, 2, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('campaign_id')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-md font-bold text-gray-700 dark:text-gray-300 ">Tipo</label>
                        <div class="flex gap-4">
                            <label class="relative cursor-pointer">
                                <input
                                    type="radio"
                                    name="type"
                                    value="entrada"
                                    class="sr-only peer"
                                    {{ old('type') === 'entrada' ? 'checked' : '' }}
                                />
                                <div class="px-6 py-2 rounded-lg border transition-all duration-200 text-md font-medium
                                    peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500
                                    text-green-600 border-green-400 hover:bg-green-50
                                    flex flex-row items-center justify-center gap-2 min-w-[120px]">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m0 0l-5-5m5 5l5-5" />
                                    </svg>
                                    <span>Entrada</span>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input
                                    type="radio"
                                    name="type"
                                    value="saida"
                                    class="sr-only peer"
                                    {{ old('type') === 'saida' ? 'checked' : '' }}
                                />
                                <div class="px-6 py-2 rounded-lg border transition-all duration-200 text-md font-medium
                                    peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500
                                    text-red-600 border-red-400 hover:bg-red-50
                                    flex flex-row items-center justify-center gap-2 min-w-[120px]">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 0l-5 5m5-5l5 5" />
                                    </svg>
                                    <span>Saída</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <x-input-currency label="Valor" name="amount" prefix="R$" required />

                    <x-input-date label="Data da Ação" name="action_date" required />

                    <x-textarea label="Descrição" name="description" />

                </div>
            </div>
            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex space-x-3">
                    <a href="{{ route('financial-transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancelar
                    </a>
                    <x-primary-button type="submit">
                        Salvar
                    </x-primary-button>
                </div>
            </div>

        </form>
    </x-page-card>
</x-app-layout>
