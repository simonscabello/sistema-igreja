<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-dark dark:text-gray-200 leading-tight">
            {{ __('Demonstração do Select Avançado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-dark dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">Exemplos de Uso</h3>
                    
                    <form method="POST" action="#" class="space-y-6">
                        @csrf
                        
                        <!-- Select Simples -->
                        <div>
                            <h4 class="font-medium mb-3">Select Simples</h4>
                            <x-select
                                name="categoria"
                                :options="[
                                    1 => 'Categoria A',
                                    2 => 'Categoria B', 
                                    3 => 'Categoria C'
                                ]"
                                :selected="2"
                                placeholder="Escolha uma categoria"
                                label="Categoria"
                            />
                        </div>
                        
                        <!-- Select Múltiplo -->
                        <div>
                            <h4 class="font-medium mb-3">Select Múltiplo</h4>
                            <x-select
                                name="usuarios"
                                :options="[
                                    1 => 'João Silva',
                                    2 => 'Maria Santos',
                                    3 => 'Pedro Costa',
                                    4 => 'Ana Oliveira',
                                    5 => 'Carlos Lima'
                                ]"
                                :selected="[1, 3]"
                                :multiple="true"
                                placeholder="Selecione usuários"
                                label="Usuários"
                            />
                        </div>
                        
                        <!-- Select com Imagens -->
                        <div>
                            <h4 class="font-medium mb-3">Select com Imagens</h4>
                            <x-select
                                name="membros"
                                :options="[
                                    1 => [
                                        'name' => 'João Silva',
                                        'image' => '/images/avatar-placeholder.png'
                                    ],
                                    2 => [
                                        'name' => 'Maria Santos',
                                        'image' => '/images/avatar-placeholder.png'
                                    ],
                                    3 => [
                                        'name' => 'Pedro Costa',
                                        'image' => '/images/avatar-placeholder.png'
                                    ]
                                ]"
                                :selected="[1, 2]"
                                :multiple="true"
                                image-field="image"
                                placeholder="Selecione membros"
                                label="Membros"
                            />
                        </div>
                        
                        <!-- Select com Limite de Itens Visíveis -->
                        <div>
                            <h4 class="font-medium mb-3">Select com Limite de Itens (3 visíveis)</h4>
                            <x-select
                                name="departamentos"
                                :options="[
                                    1 => 'Administração',
                                    2 => 'Financeiro',
                                    3 => 'Marketing',
                                    4 => 'Recursos Humanos',
                                    5 => 'Tecnologia',
                                    6 => 'Vendas',
                                    7 => 'Suporte'
                                ]"
                                :selected="[1, 3, 5]"
                                :multiple="true"
                                :max-visible="3"
                                placeholder="Selecione departamentos"
                                label="Departamentos"
                            />
                        </div>
                        
                        <!-- Select com Busca -->
                        <div>
                            <h4 class="font-medium mb-3">Select com Busca (Muitas Opções)</h4>
                            <x-select
                                name="cidades"
                                :options="collect(range(1, 50))->mapWithKeys(function($i) {
                                    return [$i => 'Cidade ' . $i];
                                })->toArray()"
                                :selected="[5, 15, 25]"
                                :multiple="true"
                                :max-visible="8"
                                placeholder="Digite para buscar cidades"
                                label="Cidades"
                            />
                        </div>
                        
                        <div class="flex justify-end">
                            <x-primary-button type="submit">
                                Enviar Formulário
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 