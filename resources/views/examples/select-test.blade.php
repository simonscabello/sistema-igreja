<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-dark dark:text-gray-200 leading-tight">
            {{ __('Teste do Select Avançado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-dark dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">Teste Simples</h3>
                    
                    <form method="POST" action="#" class="space-y-6">
                        @csrf
                        
                        <!-- Teste básico -->
                        <div>
                            <x-select
                                name="teste_basico"
                                :options="[
                                    1 => 'Opção 1',
                                    2 => 'Opção 2',
                                    3 => 'Opção 3'
                                ]"
                                :selected="2"
                                placeholder="Escolha uma opção"
                                label="Teste Básico"
                            />
                        </div>
                        
                        <!-- Teste múltiplo -->
                        <div>
                            <x-select
                                name="teste_multiplo"
                                :options="[
                                    1 => 'Item A',
                                    2 => 'Item B',
                                    3 => 'Item C',
                                    4 => 'Item D'
                                ]"
                                :selected="[1, 3]"
                                :multiple="true"
                                placeholder="Selecione itens"
                                label="Teste Múltiplo"
                            />
                        </div>
                        
                        <div class="flex justify-end">
                            <x-primary-button type="submit">
                                Testar
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 