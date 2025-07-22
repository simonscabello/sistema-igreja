# Componente de Select Avançado para Laravel 12

Um componente de select avançado e reutilizável para Laravel 12 com Blade e Tailwind CSS, oferecendo funcionalidades de multiselect, busca, suporte a imagens e compatibilidade com dark mode.

## 🚀 Características

- ✅ **Multiselect**: Suporte a seleção múltipla controlado por configuração
- ✅ **Busca em tempo real**: Campo de busca para filtrar itens da lista
- ✅ **Limite de itens visíveis**: Configuração `max-visible` para controlar scroll
- ✅ **Suporte a imagens**: Exibição de imagens ao lado dos nomes dos itens
- ✅ **Itens selecionados destacados**: Nome em negrito e ícone de check
- ✅ **Dark mode**: Compatibilidade total com tema escuro do Tailwind
- ✅ **Reutilizável**: Componente Blade com `@props` para configuração
- ✅ **Responsivo**: Funciona perfeitamente em dispositivos móveis
- ✅ **Acessibilidade**: Navegação por teclado e suporte a screen readers

## 📦 Instalação

O componente já está integrado ao projeto. Os arquivos necessários são:

- `resources/views/components/select.blade.php` - Componente Blade
- `resources/js/components/select.js` - Funcionalidades JavaScript
- `resources/js/app.js` - Importação do JavaScript

## 🎯 Uso Básico

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

## 🔧 Uso Avançado

### Select Múltiplo
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

### Select com Imagens
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

### Select com Limite de Itens
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

## 📋 Propriedades Disponíveis

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

## 📊 Estrutura de Dados

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

## 🎨 Funcionalidades JavaScript

- **Busca em tempo real**: Filtra opções conforme o usuário digita
- **Navegação por teclado**: Suporte a Enter para selecionar primeira opção
- **Fechamento automático**: Dropdown fecha ao clicar fora
- **Animações suaves**: Transições para melhor UX
- **Responsivo**: Funciona bem em dispositivos móveis
- **Acessibilidade**: Suporte a navegação por teclado

## 🧪 Exemplos de Demonstração

Acesse as seguintes rotas para ver exemplos em funcionamento:

- `/examples/select-demo` - Demonstração completa com todos os recursos
- `/examples/select-usage` - Exemplo prático em formulário real

## 🔄 Compatibilidade

- ✅ Laravel 12
- ✅ Blade
- ✅ Tailwind CSS
- ✅ Dark mode
- ✅ Alpine.js (para outras funcionalidades do sistema)

## 📝 Exemplo de Uso Real

```php
// No Controller
public function create()
{
    $departamentos = Department::pluck('name', 'id');
    $membros = Member::select('id', 'full_name', 'avatar')
        ->get()
        ->mapWithKeys(function ($member) {
            return [$member->id => [
                'name' => $member->full_name,
                'image' => $member->avatar_url
            ]];
        });
    
    return view('members.create', compact('departamentos', 'membros'));
}
```

```blade
<!-- Na View -->
<x-select
    name="departamento_id"
    :options="$departamentos"
    :selected="$member->departamento_id ?? null"
    placeholder="Escolha um departamento"
    label="Departamento"
    required
/>

<x-select
    name="membros_relacionados"
    :options="$membros"
    :selected="$member->membros_relacionados ?? []"
    :multiple="true"
    image-field="image"
    placeholder="Selecione membros relacionados"
    label="Membros Relacionados"
/>
```

## 🐛 Solução de Problemas

### O componente não aparece
- Verifique se o JavaScript foi carregado corretamente
- Confirme se o arquivo `resources/js/components/select.js` existe
- Verifique se a importação foi adicionada em `resources/js/app.js`

### Busca não funciona
- Certifique-se de que as opções têm o campo `name` correto
- Verifique se não há erros no console do navegador

### Imagens não aparecem
- Confirme se o campo `image-field` está correto
- Verifique se as URLs das imagens são válidas
- Certifique-se de que as imagens são acessíveis

## 📚 Documentação Completa

Para documentação detalhada, consulte: `docs/select-component.md`

## 🤝 Contribuição

Para melhorar o componente:

1. Teste as funcionalidades existentes
2. Adicione novos recursos mantendo a compatibilidade
3. Atualize a documentação
4. Teste em diferentes navegadores e dispositivos

## 📄 Licença

Este componente faz parte do sistema de igreja e segue as mesmas diretrizes do projeto principal. 