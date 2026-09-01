# Componente `x-select`

Select Blade reutilizável. Por padrão permanece um `<select>` nativo, compatível com os formulários existentes (nome, `old()`, `$errors`).

O modo avançado (busca, chips, imagens) entra somente com `:multiple="true"` e/ou `:searchable="true"`. Nesse modo o valor continua sendo enviado por um `<select>` real (`name` ou `name[]`), não como JSON.

## Uso nativo

```blade
<x-select
    name="gender"
    label="Gênero"
    :options="['Masculino' => 'Masculino', 'Feminino' => 'Feminino']"
    :selected="old('gender')"
    required="true"
/>
```

## Uso avançado (múltiplo / pesquisável)

```blade
<x-select
    name="members"
    label="Membros"
    :options="$memberOptions"
    :selected="old('members', [])"
    :multiple="true"
    :searchable="true"
    image-field="image"
    placeholder="Buscar membros..."
/>
```

Opções com imagem:

```php
[
    1 => ['name' => 'Ana Silva', 'image' => '/storage/foto.jpg'],
]
```

## Propriedades

| Propriedade | Padrão | Descrição |
|---|---|---|
| `name` | obrigatório | Nome do campo (`name[]` é adicionado automaticamente se `multiple`) |
| `options` | `[]` | `id => rótulo` ou `id => ['name' => ..., 'image' => ...]` |
| `selected` | `null` | Valor único ou array de IDs |
| `multiple` | `false` | Seleção múltipla |
| `searchable` | `false` | Campo de busca (também ativa o modo avançado) |
| `imageField` | `null` | Chave da imagem nas opções compostas |
| `maxVisible` | `8` | Itens visíveis no dropdown antes do scroll |
| `placeholder` | `Selecione...` | Placeholder |
| `label` | `null` | Label do campo |
| `required` | `false` | Obrigatório |
| `id` | igual a `name` | ID do `<select>` |

## Integração atual

- Departamentos: líderes e membros (listas disjuntas + foto)
- Músicas: tags, com campo extra para criar tags novas
- Repertórios: músicas, ordem de seleção = ordem de execução

API JS pontual: `window.AdvancedSelect.get('idDoSelect')` (`getSelected`, `selectValue`, `addOption`, `setExcludedIds`, `onChange`).
