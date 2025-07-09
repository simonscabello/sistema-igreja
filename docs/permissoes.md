# Sistema de Roles e Permissions - Documentação

## Visão Geral

O sistema de gestão da igreja utiliza o pacote **Spatie Laravel Permission** para controlar o acesso às funcionalidades baseado em roles (funções) e permissions (permissões) dos usuários.

## Estrutura do Sistema

### Roles (Funções) Disponíveis

| Role | Nome de Exibição | Descrição |
|------|------------------|-----------|
| `administrador` | Administrador | Acesso total ao sistema |
| `pastor` | Pastor | Gestão pastoral completa |
| `tesoureiro` | Tesoureiro | Gestão financeira |
| `lider_louvor` | Líder de Louvor | Gestão do ministério de música |
| `secretario` | Secretário | Gestão de membros e visitantes |
| `lider_departamento` | Líder de Departamento | Gestão departamental específica |
| `visualizador` | Visualizador | Apenas visualização |

### Permissions (Permissões) do Sistema

#### Gestão de Usuários e Sistema
- `gerenciar_roles` - Gerenciar Roles do Sistema
- `gerenciar_permissoes` - Gerenciar Permissões do Sistema
- `gerenciar_usuarios` - Gerenciar Usuários do Sistema

#### Gestão de Membros
- `visualizar_membros` - Visualizar Membros
- `criar_membros` - Criar Novos Membros
- `editar_membros` - Editar Dados dos Membros
- `excluir_membros` - Excluir Membros

#### Gestão de Visitantes
- `visualizar_visitantes` - Visualizar Visitantes
- `criar_visitantes` - Criar Novos Visitantes
- `editar_visitantes` - Editar Dados dos Visitantes
- `excluir_visitantes` - Excluir Visitantes

#### Gestão Financeira
- `visualizar_financeiro` - Visualizar Dados Financeiros
- `criar_transacoes` - Criar Transações Financeiras
- `editar_transacoes` - Editar Transações Financeiras
- `excluir_transacoes` - Excluir Transações Financeiras
- `gerenciar_categorias_financeiras` - Gerenciar Categorias Financeiras
- `gerenciar_campanhas` - Gerenciar Campanhas Financeiras
- `exportar_relatorios_financeiros` - Exportar Relatórios Financeiros

#### Gestão de Louvor
- `visualizar_musicas` - Visualizar Repertório Musical
- `gerenciar_musicas` - Gerenciar Repertório Musical
- `visualizar_escalas_louvor` - Visualizar Escalas de Louvor
- `gerenciar_escalas_louvor` - Gerenciar Escalas de Louvor

#### Gestão de Departamentos
- `visualizar_departamentos` - Visualizar Departamentos
- `gerenciar_departamentos` - Gerenciar Departamentos

#### Relatórios
- `visualizar_relatorios` - Visualizar Relatórios do Sistema
- `exportar_relatorios` - Exportar Relatórios do Sistema

## Como Usar o Sistema

### 1. Verificação de Permissões nas Views (Blade)

```blade
{{-- Verificar se o usuário tem uma permissão específica --}}
@can('criar_membros')
    <a href="{{ route('members.create') }}" class="btn btn-primary">
        Cadastrar Membro
    </a>
@endcan

{{-- Verificar múltiplas permissões --}}
@canany(['editar_membros', 'excluir_membros'])
    <div class="actions">
        @can('editar_membros')
            <a href="{{ route('members.edit', $member) }}">Editar</a>
        @endcan

        @can('excluir_membros')
            <form action="{{ route('members.destroy', $member) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit">Excluir</button>
            </form>
        @endcan
    </div>
@endcanany

{{-- Verificar role específica --}}
@if(auth()->user()->hasRole('administrador'))
    <a href="{{ route('users.index') }}">Gerenciar Usuários</a>
@endif
```

### 2. Verificação de Permissões nos Controllers

```php
<?php

class MemberController extends Controller
{
    public function index()
    {
        // Verificar permissão usando Gate
        if (!Gate::allows('visualizar_membros')) {
            abort(403, 'Acesso negado');
        }

        // Ou usar o middleware nas rotas
        // Route::get('/members')->middleware('permission:visualizar_membros');

        return view('members.index');
    }

    public function create()
    {
        // Verificar permissão
        $this->authorize('criar_membros');

        return view('members.create');
    }

    public function store(Request $request)
    {
        // Verificar permissão usando método can()
        if (!auth()->user()->can('criar_membros')) {
            return redirect()->back()->with('error', 'Sem permissão para criar membros');
        }

        // Lógica para criar membro
    }
}
```

### 3. Middleware nas Rotas

```php
// routes/web.php

// Proteger rota individual
Route::get('/members', [MemberController::class, 'index'])
    ->middleware(['auth', 'permission:visualizar_membros']);

// Proteger grupo de rotas
Route::middleware(['auth', 'permission:gerenciar_financeiro'])->group(function () {
    Route::resource('financial-transactions', FinancialTransactionController::class);
    Route::resource('campaigns', CampaignController::class);
});

// Proteger por role
Route::middleware(['auth', 'role:administrador'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
});

// Múltiplas permissões (qualquer uma)
Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'permission:visualizar_membros|visualizar_financeiro']);
```

### 4. Verificação Programática

```php
// Verificar se usuário tem permissão
if (auth()->user()->can('criar_membros')) {
    // Usuário pode criar membros
}

// Verificar se usuário tem role
if (auth()->user()->hasRole('pastor')) {
    // Usuário é pastor
}

// Verificar múltiplas permissões
if (auth()->user()->hasAnyPermission(['criar_membros', 'editar_membros'])) {
    // Usuário pode criar OU editar membros
}

// Verificar se usuário tem todas as permissões
if (auth()->user()->hasAllPermissions(['visualizar_financeiro', 'criar_transacoes'])) {
    // Usuário pode visualizar E criar transações
}

// Verificar múltiplas roles
if (auth()->user()->hasAnyRole(['pastor', 'administrador'])) {
    // Usuário é pastor OU administrador
}
```

## Adicionando Novas Funcionalidades

### 1. Definir Novas Permissões

Quando criar uma nova funcionalidade, adicione as permissões correspondentes no seeder:

```php
// database/seeders/RolePermissionSeeder.php

private function createPermissions(): array
{
    $permissions = [
        // ... permissões existentes ...

        // Nova funcionalidade: Eventos
        'visualizar_eventos' => 'Visualizar Eventos',
        'criar_eventos' => 'Criar Novos Eventos',
        'editar_eventos' => 'Editar Eventos',
        'excluir_eventos' => 'Excluir Eventos',
        'gerenciar_inscricoes' => 'Gerenciar Inscrições em Eventos',
    ];

    // ... resto do código ...
}
```

### 2. Atribuir Permissões às Roles

```php
// No método createRoles() do seeder

// Exemplo: Dar permissões de eventos ao Pastor
$pastorRole->syncPermissions([
    // ... permissões existentes ...
    'visualizar_eventos', 'criar_eventos', 'editar_eventos', 'gerenciar_inscricoes'
]);
```

### 3. Proteger as Novas Rotas

```php
// routes/web.php

Route::middleware(['auth', 'permission:visualizar_eventos'])->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
});

Route::middleware(['auth', 'permission:criar_eventos'])->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
});
```

### 4. Proteger as Views

```blade
{{-- resources/views/events/index.blade.php --}}

@can('criar_eventos')
    <a href="{{ route('events.create') }}" class="btn btn-primary">
        Criar Evento
    </a>
@endcan

@foreach($events as $event)
    <div class="event-card">
        <h3>{{ $event->title }}</h3>

        <div class="actions">
            @can('editar_eventos')
                <a href="{{ route('events.edit', $event) }}">Editar</a>
            @endcan

            @can('excluir_eventos')
                <form action="{{ route('events.destroy', $event) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit">Excluir</button>
                </form>
            @endcan
        </div>
    </div>
@endforeach
```

### 5. Implementar no Controller

```php
<?php

class EventController extends Controller
{
    public function index()
    {
        $this->authorize('visualizar_eventos');

        $events = Event::paginate(10);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        $this->authorize('criar_eventos');

        return view('events.create');
    }

    public function store(Request $request)
    {
        $this->authorize('criar_eventos');

        // Validação e criação do evento
    }

    public function edit(Event $event)
    {
        $this->authorize('editar_eventos');

        return view('events.edit', compact('event'));
    }

    public function destroy(Event $event)
    {
        $this->authorize('excluir_eventos');

        $event->delete();
        return redirect()->route('events.index');
    }
}
```

## Comandos Úteis

### Executar o Seeder

```bash
# Recriar todo o sistema de roles e permissions
php artisan db:seed --class=RolePermissionSeeder

# Ou incluir no seeder principal
php artisan db:seed
```

### Limpar Cache de Permissões

```bash
# Limpar cache das permissões (necessário após mudanças)
php artisan permission:cache-reset
```

### Atribuir Role a Usuário

```bash
# Via Tinker
php artisan tinker

# Dentro do Tinker:
$user = User::find(1);
$user->assignRole('pastor');

# Ou múltiplas roles
$user->assignRole(['pastor', 'lider_departamento']);
```

## Boas Práticas

1. **Granularidade**: Crie permissões específicas para cada ação (visualizar, criar, editar, excluir)

2. **Nomenclatura**: Use nomes descritivos em português com underscores (ex: `criar_membros`)

3. **Middleware**: Sempre use middleware nas rotas para proteger endpoints

4. **Views**: Use `@can` nas views para mostrar/ocultar elementos baseado em permissões

5. **Controllers**: Use `$this->authorize()` nos métodos dos controllers

6. **Cache**: Lembre-se de limpar o cache após mudanças nas permissões

7. **Testes**: Sempre teste as permissões após implementar novas funcionalidades

## Exemplo Completo: Nova Funcionalidade de Biblioteca

### 1. Adicionar Permissões ao Seeder

```php
// Gestão de Biblioteca
'visualizar_biblioteca' => 'Visualizar Acervo da Biblioteca',
'gerenciar_livros' => 'Gerenciar Livros da Biblioteca',
'gerenciar_emprestimos' => 'Gerenciar Empréstimos',
'visualizar_emprestimos' => 'Visualizar Empréstimos',
```

### 2. Atribuir às Roles

```php
// Secretário pode gerenciar biblioteca
$secretarioRole->syncPermissions([
    // ... permissões existentes ...
    'visualizar_biblioteca', 'gerenciar_livros', 'gerenciar_emprestimos'
]);

// Visualizador só pode ver
$visualizadorRole->syncPermissions([
    // ... permissões existentes ...
    'visualizar_biblioteca', 'visualizar_emprestimos'
]);
```

### 3. Criar Rotas Protegidas

```php
Route::middleware(['auth', 'permission:visualizar_biblioteca'])->group(function () {
    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
    Route::get('/library/books/{book}', [LibraryController::class, 'show'])->name('library.show');
});

Route::middleware(['auth', 'permission:gerenciar_livros'])->group(function () {
    Route::resource('library.books', BookController::class)->except(['index', 'show']);
});
```

Este sistema garante que cada usuário tenha acesso apenas às funcionalidades apropriadas para sua função na igreja, mantendo a segurança e organização do sistema.
