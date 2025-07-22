# Componente de Select Avançado

Este componente oferece um select avançado com funcionalidades de multiselect, busca, suporte a imagens e compatibilidade com dark mode.

## Características

- ✅ **Multiselect**: Suporte a seleção múltipla controlado por configuração
- ✅ **Busca**: Campo de busca para filtrar itens da lista
- ✅ **Limite de itens visíveis**: Configuração `max-visible` para controlar quantos itens aparecem antes do scroll
- ✅ **Suporte a imagens**: Exibição de imagens ao lado dos nomes dos itens
- ✅ **Itens selecionados destacados**: Nome em negrito e ícone de check
- ✅ **Dark mode**: Compatibilidade total com tema escuro do Tailwind
- ✅ **Reutilizável**: Componente Blade com `@props` para configuração

## Uso Básico

```blade
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
```

## Uso com Multiselect

```blade
<x-select
    name="usuarios"
    :options="[
        1 => 'João Silva',
        2 => 'Maria Santos',
        3 => 'Pedro Costa'
    ]"
    :selected="[1, 3]"
    :multiple="true"
    placeholder="Selecione usuários"
    label="Usuários"
/>
```

## Uso com Imagens

```blade
<x-select
    name="membros"
    :options="[
        1 => [
            'name' => 'João Silva',
            'image' => '/images/avatar.jpg'
        ],
        2 => [
            'name' => 'Maria Santos',
            'image' => '/images/avatar2.jpg'
        ]
    ]"
    :selected="[1, 2]"
    :multiple="true"
    image-field="image"
    placeholder="Selecione membros"
    label="Membros"
/>
```

## Uso com Limite de Itens Visíveis

```blade
<x-select
    name="departamentos"
    :options="$departamentos"
    :selected="[1, 3, 5]"
    :multiple="true"
    :max-visible="5"
    placeholder="Selecione departamentos"
    label="Departamentos"
/>
```

## Propriedades Disponíveis

| Propriedade | Tipo | Padrão | Descrição |
|-------------|------|--------|-----------|
| `name` | string | **obrigatório** | Nome do campo para envio do formulário |
| `options` | array | `[]` | Array de opções (id => nome ou id => array) |
| `selected` | array/int | `[]` | IDs dos itens selecionados |
| `multiple` | boolean | `false` | Habilita seleção múltipla |
| `imageField` | string | `null` | Nome do campo que contém a URL da imagem |
| `maxVisible` | int | `5` | Quantidade máxima de itens visíveis antes do scroll |
| `placeholder` | string | `'Selecione...'` | Texto de placeholder |
| `label` | string | `null` | Label do campo |
| `required` | boolean | `false` | Marca o campo como obrigatório |
| `error` | string | `null` | Mensagem de erro |

## Estrutura de Dados

### Opções Simples
```php
$options = [
    1 => 'Nome do Item',
    2 => 'Outro Item',
    3 => 'Mais um Item'
];
```

### Opções com Imagens
```php
$options = [
    1 => [
        'name' => 'Nome do Item',
        'image' => '/caminho/para/imagem.jpg'
    ],
    2 => [
        'name' => 'Outro Item',
        'image' => '/caminho/para/outra-imagem.jpg'
    ]
];
```

## Valores Selecionados

### Select Simples
```php
$selected = 2; // ID único
```

### Select Múltiplo
```php
$selected = [1, 3, 5]; // Array de IDs
```

## Funcionalidades JavaScript

O componente inclui as seguintes funcionalidades:

- **Busca em tempo real**: Filtra opções conforme o usuário digita
- **Navegação por teclado**: Suporte a Enter para selecionar primeira opção
- **Fechamento automático**: Dropdown fecha ao clicar fora
- **Animações**: Transições suaves para melhor UX
- **Responsivo**: Funciona bem em dispositivos móveis

## Arquivos do Componente

- **Blade**: `resources/views/components/select.blade.php`
- **JavaScript**: `resources/js/components/select.js`
- **Importação**: Adicionado em `resources/js/app.js`

## Exemplo de Demonstração

Acesse `/examples/select-demo` para ver todos os exemplos de uso em funcionamento.

## Compatibilidade

- Laravel 12
- Blade
- Tailwind CSS
- Dark mode
- Alpine.js (para outras funcionalidades do sistema) 