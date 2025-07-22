<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-dark dark:text-gray-200 leading-tight">
            {{ __('Exemplo de Uso do Select Avançado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-dark dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">Exemplo Prático: Formulário de Membro</h3>
                    
                    <form method="POST" action="#" class="space-y-6">
                        @csrf
                        
                        <!-- Informações Básicas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="nome" value="Nome Completo" required />
                                <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" required />
                            </div>
                            
                            <div>
                                <x-input-label for="email" value="E-mail" required />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required />
                            </div>
                        </div>
                        
                        <!-- Select Simples para Departamento -->
                        <div>
                            <x-select
                                name="departamento_id"
                                :options="[
                                    1 => 'Administração',
                                    2 => 'Financeiro',
                                    3 => 'Marketing',
                                    4 => 'Recursos Humanos',
                                    5 => 'Tecnologia',
                                    6 => 'Vendas',
                                    7 => 'Suporte'
                                ]"
                                :selected="3"
                                placeholder="Escolha um departamento"
                                label="Departamento"
                                required
                            />
                        </div>
                        
                        <!-- Select Múltiplo para Habilidades -->
                        <div>
                            <x-select
                                name="habilidades"
                                :options="[
                                    1 => 'Liderança',
                                    2 => 'Comunicação',
                                    3 => 'Organização',
                                    4 => 'Criatividade',
                                    5 => 'Trabalho em Equipe',
                                    6 => 'Resolução de Problemas',
                                    7 => 'Gestão de Tempo',
                                    8 => 'Negociação',
                                    9 => 'Análise de Dados',
                                    10 => 'Design'
                                ]"
                                :selected="[1, 3, 5]"
                                :multiple="true"
                                :max-visible="6"
                                placeholder="Selecione as habilidades"
                                label="Habilidades"
                            />
                        </div>
                        
                        <!-- Select com Imagens para Membros Relacionados -->
                        <div>
                            <x-select
                                name="membros_relacionados"
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
                                    ],
                                    4 => [
                                        'name' => 'Ana Oliveira',
                                        'image' => '/images/avatar-placeholder.png'
                                    ]
                                ]"
                                :selected="[1, 2]"
                                :multiple="true"
                                image-field="image"
                                placeholder="Selecione membros relacionados"
                                label="Membros Relacionados"
                            />
                        </div>
                        
                        <!-- Select para Categorias Financeiras -->
                        <div>
                            <x-select
                                name="categorias_financeiras"
                                :options="[
                                    1 => 'Dízimos e Ofertas',
                                    2 => 'Manutenção',
                                    3 => 'Eventos',
                                    4 => 'Missions',
                                    5 => 'Equipamentos',
                                    6 => 'Marketing',
                                    7 => 'Administrativo',
                                    8 => 'Educação'
                                ]"
                                :selected="[1, 4]"
                                :multiple="true"
                                placeholder="Selecione categorias de interesse"
                                label="Categorias Financeiras de Interesse"
                            />
                        </div>
                        
                        <!-- Informações Adicionais -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="telefone" value="Telefone" />
                                <x-text-input id="telefone" name="telefone" type="tel" class="mt-1 block w-full" />
                            </div>
                            
                            <div>
                                <x-input-label for="cidade" value="Cidade" />
                                <x-text-input id="cidade" name="cidade" type="text" class="mt-1 block w-full" />
                            </div>
                        </div>
                        
                        <div>
                            <x-input-label for="observacoes" value="Observações" />
                            <x-textarea id="observacoes" name="observacoes" class="mt-1 block w-full" rows="4" />
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <x-secondary-button type="button">
                                Cancelar
                            </x-secondary-button>
                            <x-primary-button type="submit">
                                Salvar Membro
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 