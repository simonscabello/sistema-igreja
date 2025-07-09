# Permissões Implementadas nas Controllers

Este documento lista todas as permissões implementadas nas controllers do sistema de gestão da igreja.

## ⚙️ Configuração Técnica

### Trait AuthorizesRequests

Para que o método `authorize()` funcione corretamente, foi adicionada a trait `AuthorizesRequests` na classe base `Controller`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use AuthorizesRequests;
}
```

Esta trait permite que todas as controllers que estendem `Controller` utilizem o método `$this->authorize('permissao')`.

## Controllers de Membros e Visitantes

### MemberController

-   **index/show**: `visualizar_membros`
-   **create/store**: `criar_membros`
-   **edit/update**: `editar_membros`
-   **destroy**: `excluir_membros`

### VisitorController

-   **index/show**: `visualizar_visitantes`
-   **create/store**: `criar_visitantes`
-   **edit/update**: `editar_visitantes`
-   **destroy**: `excluir_visitantes`

## Controllers Financeiras

### FinancialTransactionController

-   **index/show**: `visualizar_financeiro`
-   **create/store**: `criar_transacoes`
-   **edit/update**: `editar_transacoes`
-   **destroy**: `excluir_transacoes`

### FinancialCategoryController

-   **todos os métodos**: `gerenciar_categorias_financeiras`

### FinancialSubcategoryController

-   **todos os métodos**: `gerenciar_categorias_financeiras`

### CampaignController

-   **index/show**: `visualizar_financeiro`
-   **create/store/edit/update/destroy**: `gerenciar_campanhas`

### FinancialReportController

-   **\_\_invoke**: `visualizar_financeiro`

## Controllers de Louvor

### SongController

-   **index/show**: `visualizar_musicas`
-   **create/store/edit/update/destroy**: `gerenciar_musicas`

### WorshipSetController

-   **index/show**: `visualizar_escalas_louvor`
-   **create/store/edit/update/destroy/clone**: `gerenciar_escalas_louvor`

## Controllers de Departamentos

### DepartmentController

-   **index/show**: `visualizar_departamentos`
-   **create/store/edit/update/destroy**: `gerenciar_departamentos`

## Controllers de Sistema

### RoleController

-   **todos os métodos**: `gerenciar_roles`

### PermissionController

-   **todos os métodos**: `gerenciar_permissoes`

### UserController

-   **todos os métodos**: `gerenciar_usuarios`

## Mapeamento de Permissões por Funcionalidade

### Gestão de Pessoas

-   `visualizar_membros`
-   `criar_membros`
-   `editar_membros`
-   `excluir_membros`
-   `visualizar_visitantes`
-   `criar_visitantes`
-   `editar_visitantes`
-   `excluir_visitantes`

### Gestão Financeira

-   `visualizar_financeiro`
-   `criar_transacoes`
-   `editar_transacoes`
-   `excluir_transacoes`
-   `gerenciar_categorias_financeiras`
-   `gerenciar_campanhas`

### Gestão de Louvor

-   `visualizar_musicas`
-   `gerenciar_musicas`
-   `visualizar_escalas_louvor`
-   `gerenciar_escalas_louvor`

### Gestão Organizacional

-   `visualizar_departamentos`
-   `gerenciar_departamentos`

### Administração do Sistema

-   `gerenciar_roles`
-   `gerenciar_permissoes`
-   `gerenciar_usuarios`

## Como Funciona

### Implementação nas Controllers

Todas as controllers implementam verificação de permissões usando o método `$this->authorize('nome_da_permissao')` no início de cada método que precisa de autorização.

```php
public function index(Request $request): View
{
    $this->authorize('visualizar_membros');

    // ... resto do código
}
```

### Tratamento de Erros

Quando um usuário não possui a permissão necessária:

-   Uma exceção `AuthorizationException` é lançada
-   O usuário é redirecionado para uma página de erro 403 (Forbidden)
-   Uma mensagem informativa é exibida

### Requisitos Técnicos

-   Trait `AuthorizesRequests` na classe base `Controller`
-   Pacote `spatie/laravel-permission` instalado e configurado
-   Sistema de autenticação do Laravel funcionando

## Estrutura Hierárquica

O sistema suporta hierarquia de permissões através dos roles:

-   **Super Admin**: Acesso total ao sistema
-   **Pastor**: Acesso amplo exceto administração
-   **Secretário**: Acesso a membros, visitantes e departamentos
-   **Tesoureiro**: Acesso ao sistema financeiro
-   **Músico**: Acesso ao sistema de louvor
-   **Membro**: Acesso limitado apenas para visualização

## ✅ Status da Implementação

-   ✅ Trait `AuthorizesRequests` configurada na classe base
-   ✅ 13 controllers com verificações de permissões implementadas
-   ✅ 20+ permissões específicas mapeadas
-   ✅ Sistema de roles hierárquico funcionando
-   ✅ Documentação completa criada
