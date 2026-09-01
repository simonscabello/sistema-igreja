# Contexto do sistema para agentes de IA

Leia este arquivo **antes** de alterar qualquer código. Ele descreve o que o sistema é nesta branch (`develop`), como está organizado e o que não deve ser quebrado.

Documentação complementar (não duplicar; algumas notas de implementação podem estar desatualizadas em relação a este arquivo):

- `README.md` — instalação e ambiente local
- `database/seeders/RolePermissionSeeder.php` — fonte de verdade de roles e permissões
- `PERMISSOES_IMPLEMENTADAS.md` / `ROLES_PERMISSIONS_RESUMO.md` / `docs/permissoes.md` — notas de roles (o mapeamento de `FinancialReportController` nesses arquivos ainda cita `__invoke`; o controller atual usa `index` / `monthly` / `annualDetailed` / `annualSummary`)
- `docs/upload-sistema.md` / `IMPLEMENTACAO_UPLOAD_TRANSACOES.md` — upload de arquivos
- `MELHORIAS_CAMPO_VALOR.md` — máscara e validação de valores monetários

A linha de desenvolvimento ativa é **`develop`**. `main` está congelada em junho/2025 e **não** contém auth nas rotas de domínio, Spatie, dashboards nem relatórios anuais. Não implemente features em `main` assumindo que ela é o produto atual.

Não existe `master`. A branch remota `origin/feature/select-component` tem 1 commit à frente de `develop` (select avançado + páginas `/examples/select-*`); **esse código não está nesta branch**. O `x-select` atual é um `<select>` nativo Blade.

---

# Visão geral do sistema

**Sistema Igreja** é uma aplicação web monolítica Laravel para gestão operacional de uma igreja.

Usuários do sistema (`User`) fazem login e têm **roles/permissões Spatie**. Os **membros da igreja** (`Member`) são cadastros pastorais/administrativos, **não** contas de acesso. Não há FK entre `User` e `Member`. Não “logar como membro” nem exigir e-mail de membro igual ao de usuário.

Módulos atuais:

| Módulo | Para que serve |
|---|---|
| Dashboard | Totais (membros, visitantes, departamentos, saldo), aniversariantes do mês, últimos visitantes, atalhos |
| Membros | Cadastro de pessoas, endereço, datas eclesiásticas, foto |
| Visitantes | Registro de visitas, faixa etária, interesse em contato |
| Departamentos | Ministérios, com líderes e membros (dois papéis distintos) |
| Finanças | Categorias → subcategorias → transações (entrada/saída), anexos |
| Dashboard financeiro | Gráfico ApexCharts de entradas/saídas por período |
| Campanhas | Metas de arrecadação vinculadas a transações de entrada |
| Relatórios financeiros | Mensal (balancete), anual detalhado e anual simplificado |
| Músicas | Catálogo de louvor com tags e links (YouTube, Spotify, cifra, letra) |
| Repertórios | Setlist por culto (data, período manhã/noite, cantor, ministro) |
| Usuários / roles / permissões | Administração (role `administrador`) |

Não é um site público da igreja. O dashboard autenticado é `DashboardController` + `resources/views/dashboard.blade.php` (não o placeholder “Você está logado!” de `main`).

---

# Stack tecnológica

| Tecnologia | Uso no projeto |
|---|---|
| PHP 8.2+ (README recomenda 8.4) | Runtime |
| Laravel 12.19 | Framework (`composer.lock`) |
| Laravel Breeze 2.3 | Auth (login, reset de senha, perfil). **Registro público está comentado** em `routes/auth.php` |
| Spatie Laravel Permission 6.20 | Roles e permissões (`spatie/laravel-permission`) |
| Sentry Laravel 4.15 | Exceções via `Sentry\Laravel\Integration::handles()` em `bootstrap/app.php` |
| MySQL 8.0 | Banco (Docker na porta **3311**) |
| Blade | Frontend server-rendered |
| Alpine.js 3 + `@alpinejs/mask` | Interatividade (tema, sidebar, selects encadeados, preview de foto, formulário de transação) |
| Tailwind CSS 3 + Flowbite 3 | UI, datepicker, componentes |
| Choices.js | Multiselect de departamentos e tags |
| ApexCharts 3.46 | Gráfico do dashboard financeiro (`resources/js/financial-dashboard.js`) |
| Vite 6 | Assets (`resources/css/app.css`, `financial-dashboard.css`, `app.js`, `financial-dashboard.js`) |
| Intervention Image 3 (driver GD) | Redimensionamento de imagens |
| Pest 3 | Testes |
| Docker Compose | **Somente MySQL**; a app roda no host |

Não usado neste projeto, apesar de aparecer no `.env.example`: Redis, AWS S3, Postmark, Slack, SES. Clarity e Sentry **estão no código**, mas só disparam se `CLARITY_ID` / `SENTRY_LARAVEL_DSN` (ou `SENTRY_DSN`) estiverem definidos.

Fila: `QUEUE_CONNECTION=database`, mas **não existem Jobs** em `app/Jobs`. Cache e sessão também usam o banco.

---

# Arquitetura

MVC clássico Laravel, **sem camada de repositório, DTO, Event/Listener de domínio ou API REST**. Existe **uma** Policy (`app/Policies/AdminPolicy.php`), mas os controllers **não a usam** — autorização real é `$this->authorize('nome_da_permissao')` (Spatie registra cada permissão como Gate).

```
Browser (Blade + Alpine)
 → routes/web.php (middleware auth + password.changed)
 → FormRequest (validação + normalização; authorize() === true)
 → Controller ($this->authorize('permissao') + CRUD)
 → Eloquent Model
 → MySQL
 → view Blade
```

O único service de aplicação é `app/Services/FileService.php` (upload/delete e processamento de imagem). **Regras de negócio ficam no controller, no FormRequest ou em accessors do model.** Não invente hexagonal/service-repository para uma feature isolada.

Não existe `routes/api.php`. JSON pontual no mesmo controller web:

- relatório financeiro se `ajax()` / `wantsJson()` (`FinancialReportController`)
- dados do gráfico: `GET financial.dashboard.data` (`FinancialDashboardController::getData`)
- subcategorias: `GET financial.categories.subcategories` (`FinancialCategoryController::getSubcategories`) — ver pontas sensíveis

Binding: implicit route model binding. Departamentos usam `{department}` / `Department $department` (rotas `departments.*`, não `departamentos.*`). Recursos financeiros usam prefixo `/financial` e names `financial.*`.

---

# Estrutura importante

```
app/
  Console/Commands/SeedTransactionReport.php  comando avulso `seed:transaction-report`
  Http/Controllers/  um controller por recurso + Auth/ (Breeze) + Dashboard + User/Role/Permission
  Http/Middleware/EnsurePasswordChanged.php   alias `password.changed`
  Http/Requests/     Store/Update por recurso
  Models/            Eloquent + Traits/HasFiles.php
  Policies/          só AdminPolicy (não usado pelos controllers)
  Services/          só FileService
  View/Components/   AppLayout, GuestLayout, SidebarDropdown
bootstrap/app.php    rotas, aliases Spatie + password.changed, Sentry
config/              defaults Laravel + permission.php + sentry.php; Clarity em config/services.php
database/migrations/ fonte de verdade do schema
database/seeders/    DatabaseSeeder bloqueia production
database/factories/  User, Member, Department
resources/views/     Blade por recurso + components/ + layouts/
resources/js/        Alpine, SweetAlert, departamentos, financial-dashboard
public/js/financial-transactions.js  Alpine helper de categoria/subcategoria (não passa pelo Vite)
routes/web.php       rotas de domínio (grupo auth)
routes/auth.php      Breeze; registro comentado
tests/               Pest; Auth Breeze + Feature/Unit de Member
deploy.sh            deploy em servidor (composer2, nvm, migrate --seed)
docker-compose.yml   MySQL 8.0 em 3311
```

Ausentes (não criar por “boas práticas” genéricas): `app/Jobs`, `app/Events`, `app/Listeners`, `app/Enums`, `app/Repositories`, `app/DTOs`, `routes/api.php`. Não criar Policies novas se o padrão do projeto é Spatie Gate pelo nome da permissão.

---

# Domínio e regras de negócio

## User vs Member

Entidades **independentes**. `User` autentica. `Member` é cadastro de pessoa. Sem FK.

## User (`User`)

- Trait `HasRoles` (Spatie).
- `must_change_password` (boolean): usuários criados em `UserController::store` nascem `true` com senha gerada por `User::generateTemporaryPassword()`.
- Middleware `password.changed` redireciona para `password.force-change` enquanto a flag for true.
- Registro público (`/register`) está **comentado**. Novos usuários só via CRUD `users.*` (role `administrador`).
- Seeder cria `admin@igreja.com` / `admin123` com role `administrador` (só fora de production).

## Permissões e roles

Fonte: `RolePermissionSeeder`. Nomes de role em snake_case português: `administrador`, `pastor`, `tesoureiro`, `lider_louvor`, `secretario`, `lider_departamento`, `visualizador`.

Permissões são strings em português (`visualizar_membros`, `criar_transacoes`, …). Controllers chamam `$this->authorize('visualizar_membros')` etc. Sidebar e dashboard usam `@can('...')`.

Rotas `roles.*`, `permissions.*`, `users.*` têm **também** `middleware(['role:administrador'])`.

`AdminPolicy` checa `hasRole('admin')` — **não coincide** com a role real `administrador`. Trate como código morto/incorreto; não copie esse nome.

Permissões `exportar_relatorios` e `exportar_relatorios_financeiros` existem no seeder; **não há** ação de exportação nos controllers.

## Membro (`Member`)

Obrigatórios (migration + FormRequest): `full_name`, `mobile`, `gender`, `birth_date`, `zip_code`.

Enums no banco (valores **exatos**, com capitalização):

- `gender`: `Masculino`, `Feminino`, `Outro`
- `marital_status`: `Solteiro`, `Casado`, `Divorciado`, `Viúvo` (nullable)

Datas eclesiásticas opcionais. FormRequest converte `d/m/Y` → `Y-m-d` em `prepareForValidation`.

Foto: collection polimórfica `foto_perfil` via `HasFiles` + `FileService`. No update, a foto anterior é apagada do disco e do banco antes da nova (`MemberController::update`).

`destroy` de membro **não** limpa arquivos no storage.

## Visitante (`Visitor`)

Cadastro mais leve. Enums **diferentes** dos de Member:

- `gender`: `feminino`, `masculino` (minúsculo; sem “Outro”)
- `age_group`: `crianca_adolescente`, `jovem`, `adulto`, `idoso`
- `wants_contact`: boolean (`$this->boolean()`)

Não há fluxo automático visitante → membro. Visitante **não tem** `birth_date`; o dashboard só lista aniversariantes de `Member` (há comentário no `DashboardController` sobre isso).

## Departamento (`Department`)

Dois N:N **disjuntos**:

- `department_responsible_member` — líderes
- `department_member` — membros

Um `Member` **não pode** estar nas duas listas (`StoreDepartmentRequest` / `UpdateDepartmentRequest` `withValidator`). JS em `resources/js/departments/` reforça na UI. O banco **não** impede sobreposição entre as duas tabelas — só unique por par dentro de cada uma.

`is_active` boolean. Filtro de listagem: `status=active|inactive`.

Route names: `departments.*`. Views em `resources/views/departments/`.

## Finanças — hierarquia

```
FinancialCategory (active)
 └── FinancialSubcategory (active, financial_category_id)
 └── FinancialTransaction (type, amount, action_date, campaign_id?)
      └── files collection `comprovantes`
```

Regras:

1. Transação **sempre** aponta para subcategoria, não para categoria. Categoria é derivada (`subcategory.financialCategory` ou `FinancialTransaction::category()`).
2. `type` só `entrada` ou `saida` (sem acento).
3. `amount` é `decimal(10,2)`, **mínimo 0.01**. UI envia formato BR; FormRequest de transação normaliza em `prepareForValidation`. Campanha usa outro replace (`str_replace(['.', ','], ['', '.'], ...)`).
4. Datas de transação/campanha/membro/visitante na UI financeira usam datepicker `dd/mm/yyyy` e são convertidas no FormRequest. **Repertórios usam `<input type="date">` nativo (`Y-m-d`)** — não misturar no mesmo FormRequest.
5. Formulários de transação listam categorias `active = true` e campanhas `status = 'ativo'`. Editar transação ligada a categoria inativa/campanha encerrada pode perder a opção no select.
6. Subcategoria com transações **não pode ser excluída** (`FinancialSubcategoryController::destroy`). FK `onDelete('restrict')`. A index de subcategoria **só mostra** `session('success')`, não `error`.
7. Campanha com transações **não pode ser excluída** (só no controller). FK `campaign_id` é `onDelete('set null')`. Index de campanha também **não** renderiza `session('error')`.
8. Categoria: `onDelete('cascade')` nas subcategorias. `FinancialCategoryController::destroy` **não** verifica transações — apagar categoria com subcategorias que tenham transações estoura FK.
9. Relatório, dashboard financeiro e progresso de campanha usam o campo `type`; não há estorno nem conciliação bancária.
10. Anexo opcional na transação: `attachment` (pdf/jpg/jpeg/png, 10MB), collection `comprovantes`. Update substitui o arquivo anterior. Destroy da transação apaga os arquivos.

Formulário de transação: Alpine `financialTransactionForm` em `public/js/financial-transactions.js`. Create/edit passam `@json($categories)` e no `@change` chamam `updateSubcategoriesLocal()` (filtra `active`). O mesmo arquivo ainda tem `fetchSubcategories()` apontando para `/categories/subcategories` (URL **errada** após o prefixo `/financial/`); esse caminho só entra se `categories` vier vazio.

## Campanha (`Campaign`)

Status exatos: `ativo`, `encerrado`, `cancelada` (gênero inconsistente no enum — não “corrigir” sem migration).

Progresso (`getProgressAttribute`): soma de `amount` com `type = 'entrada'`. Saídas vinculadas **não** entram. `progress_percentage` cap 100; meta ≤ 0 retorna 0%.

`prepareForValidation` de datas de campanha chama `Carbon::createFromFormat('d/m/Y', ...)` **sem** checar string vazia (datas são nullable — risco de `InvalidFormatException`).

## Relatórios e dashboard financeiro

`FinancialReportController`:

- `index` — hub
- `monthly` — filtra `action_date` por mês/ano, agrupa por nome de categoria/subcategoria, `saldo_mensal = entradas - saídas`. View inclui seção de balancete.
- `annualDetailed` — 12 meses + transações individuais, filtros opcionais `type` e `category`
- `annualSummary` — totais mensais sem detalhe por categoria

`month` aceita 1–12 **ou** nome em português (`janeiro`…`dezembro`). Inválido cai no mês atual.

JSON mensal (chaves usadas no código; o antigo `RELATORIOS_FINANCEIROS.md` foi removido nesta branch):

- `entradas`, `saidas` (agrupados por categoria → `total_categoria` + `subcategorias`)
- `total_entradas`, `total_saidas`, `saldo_mensal`
- `periodo.mes`, `periodo.ano`, `periodo.mes_nome`

JSON do gráfico (`FinancialDashboardController::getData`): `entradas` e `saidas` como mapa data → total, `total_entradas`, `total_saidas`, `saldo`, `periodo` (número de dias, default 30). Query: `DATE(action_date)` agrupado. Autorização: `visualizar_financeiro`.

Dashboard operacional (`DashboardController`): saldo = soma entradas − soma saídas **de todas** as transações (sem filtro de período). Cards e atalhos gated por `@can`.

## Música e repertório

`Tag` não tem CRUD. Tags nascem com `Tag::firstOrCreate(['name' => trim($tagName)])` em `SongController`. Nome unique.

`WorshipSet.period`: `manha` | `noite`. Label: accessor `period_label`.

Pivot `song_worship_set`: `order` (1-based pela ordem do array `songs[]`), `key_used` (tonalidade naquele culto, distinta de `Song.key`). Unique `(song_id, worship_set_id)` — a **mesma música não pode aparecer duas vezes** no mesmo repertório.

Store/update exigem **pelo menos uma música**.

`WorshipSetController::clone` é GET: **não persiste**. Replica o set, zera cantor/ministro/notas, data = amanhã, reabre o form de create. Persistência só no `store`. Atribui `$clonedSet->songs = $worshipSet->songs` em model não persistido (funciona na view; frágil).

Coluna `used_keys` em `worship_sets` foi removida. Tonalidades ficam só no pivot.

URIs atuais: `/worship/songs`, `/worship/sets` (não `/louvor/...`).

## Arquivos polimórficos

Tabelas `files` + `fileables` (morph + `collection`). Trait `HasFiles::files($collection)`.

Collections observadas:

- `foto_perfil` — foto do membro
- `comprovantes` — anexo da transação
- `default` — uploads genéricos em `FileController`

`FileController` lista só a collection `default`. Não há link na UI de membros para `members.files.*`. A view `members/files/index.blade.php` usa `@extends('layouts.app')` / `@section('content')`, incompatível com `AppLayout` (`$slot`).

`FILESYSTEM_DISK` no `.env.example` é `local`. `FileService::generateUrlRaw` só gera URL para `public` e `s3`; no disco `local` grava `url = ''`. Dashboard de aniversariantes monta URL com `asset('storage/' . $foto->path)` — depende de `storage:link` e disco `public`.

Imagens: resize no máximo 500×500 (não é crop/cover). JPEG quality 80.

`File::fileables()` aponta para `morphToMany(Model::class, ...)` e **não é usado**. O relacionamento real está no trait.

---

# Fluxos principais

## Requisição web típica (CRUD)

1. Grupo `auth` + `password.changed` em `routes/web.php` (dashboard também tem `verified`)
2. FormRequest (`authorize(): true` em todos — a permissão é no controller)
3. `$this->authorize('...')` no método
4. Controller persiste via `$request->validated()` + sync/attach
5. Redirect para `*.index` com `session('success')` (ou `error` em destroys bloqueados)
6. View de index mostra `x-alert` se `session('success')`

Sem try/catch de domínio. Erros de validação voltam ao form (`old()` + `x-input-error`).

## Auth e senha temporária

1. `/` → `RootRedirectController`: logado → `dashboard`; senão → `login`
2. Login: `LoginRequest::authenticate()` (throttle 5) → `dashboard`, salvo `must_change_password`
3. Se `must_change_password`, `EnsurePasswordChanged` manda para `password.force-change`
4. Logout → `/`
5. `User` **não** implementa `MustVerifyEmail`; middleware `verified` no dashboard na prática não bloqueia

## Foto de membro

`store`/`update` em `MemberController` → se `foto_perfil` presente → `FileService::uploadFile(..., 'foto_perfil')`. Views: `x-avatar`.

## Transação financeira

Form escolhe categoria (Alpine filtra subcategorias ativas de `$categories`). Só `financial_subcategory_id` é persistido. Campanha e anexo opcionais. Rotas: `financial.transactions.*`.

## Relatório / dashboard financeiro

- HTML: `GET /financial/reports/monthly|annual/detailed|annual/summary`
- JSON: mesmo endpoint com AJAX/`wantsJson()`
- Gráfico: view `financial-dashboard.index` + `GET /financial/dashboard/data?period=`

## CEP

Somente no browser, em `layouts/app.blade.php`: blur em `#zip_code` → ViaCEP. Preenche `street`, `neighborhood`, `city`, `state`. Sem backend.

## Exclusão na UI

Botões `.btn-delete` → SweetAlert (`resources/js/delete-action-alert.js`) → submit DELETE. Tela de arquivos do membro usa `confirm()` nativo.

## O que não existe no runtime

Scheduler (`routes/console.php` só tem `inspire`). Jobs. Webhooks. Fila de domínio. Notificações custom. `Log::` não é usado em `app/`.

---

# Banco de dados

MySQL 8.0, charset `utf8mb4_unicode_ci`. Tabelas de framework: `users`, `password_reset_tokens`, `sessions`, `cache`, `jobs`, `job_batches`, `failed_jobs`. Spatie: `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions` (+ `display_name` em roles e permissions).

## Entidades de domínio

```
users  login + must_change_password
members
visitors
departments
  department_responsible_member (department_id, member_id) unique
  department_member (department_id, member_id) unique
financial_categories
financial_subcategories  FK category cascade
financial_transactions   FK subcategory restrict; campaign set null
campaigns
songs
tags  name unique
song_tag  unique (song_id, tag_id)
worship_sets
song_worship_set  unique (song_id, worship_set_id); order, key_used
files
fileables  morphs fileable + collection; unique composta
```

Não há `softDeletes` em nenhum model de domínio.

Histórico: transações originalmente tinham `financial_category_id`; migrations `2025_06_19_030943` + `2025_06_19_040000` moveram para subcategoria. Não reintroduzir categoria direta na transação.

Migrations de louvor nesta branch usam prefixo `2025_01_01_00000*` para `songs` / pivot / `tags` (ordem: worship_sets → songs → song_worship_set → tags → song_tag).

---

# Autenticação e autorização

- Guard `web`, session, provider Eloquent `App\Models\User`.
- Senha hashed. Login e-mail + senha, remember opcional.
- Spatie: aliases `role`, `permission`, `role_or_permission` em `bootstrap/app.php`.
- Controllers estendem `Controller` com `AuthorizesRequests` (`MemberController` e `CampaignController` repetem o trait).
- FormRequests **não** checam permissão (`authorize(): true`).

**Ordem de defesa:** middleware `auth` → `password.changed` → (rotas admin) `role:administrador` → `$this->authorize('permissao')` → `@can` na view.

Seed de dev (após `migrate --seed`): `admin@igreja.com` / `admin123`. Documentado no README; não é secret de produção. `DatabaseSeeder` recusa `production`.

---

# Integrações externas

| Integração | Onde | Cuidado |
|---|---|---|
| ViaCEP | JS inline em `resources/views/layouts/app.blade.php` | Depende de `#zip_code` e ids de endereço. Sem chave. Falha silencia e limpa campos. |
| Microsoft Clarity | `layouts/app.blade.php` e `guest.blade.php` se `config('services.clarity.id')` (`CLARITY_ID`) | Sem ID, o script não é emitido. Não commitar ID de produção no repo. |
| Sentry | `bootstrap/app.php` + `config/sentry.php` | DSN via `SENTRY_LARAVEL_DSN` ou `SENTRY_DSN`. Sem DSN, SDK não envia. |
| YouTube / Spotify / cifra / letra | URLs em `songs` | Sem API; `target="_blank"`. Validação `url`. |
| CDN jsDelivr | Choices.js, SweetAlert2, datepicker pt-BR no layout | Layout quebra offline. Choices também está no `package.json`. |
| fonts.bunny.net | Figtree no layout; Tailwind usa Fira Sans | Inconsistência visual menor. |
| Intervention/GD | `FileService::processImage` | Precisa da extensão `gd`. |
| Mail | `MAIL_MAILER=log` | Reset de senha grava em `storage/logs`. |

Não há webhooks, OAuth, gateways de pagamento nem S3 configurado de fato.

Nunca documente ou commite tokens reais. AWS/Redis no `.env.example` são placeholders. Credenciais Docker/`admin123` são só desenvolvimento.

---

# Convenções do projeto

Observadas no código — não inventar as que não existem.

**Controllers:** resource methods padrão. Invokable só `RootRedirectController`. `FinancialReportController` e `FinancialDashboardController` são classes com métodos nomeados. Attach/sync no próprio controller. Tipos de retorno `View` / `RedirectResponse` / `JsonResponse` na maior parte.

**FormRequests:** `StoreXRequest` / `UpdateXRequest`. Sempre `authorize(): true`. Mensagens e `attributes()` em português. Datas BR e dinheiro BR em `prepareForValidation` **quando o form usa datepicker/currency**.

**Autorização:** `$this->authorize('snake_case_portugues')` + `@can` na Blade + item na sidebar (desktop **e** mobile). Nova capacidade = nova string no `RolePermissionSeeder` e sync nas roles que devem tê-la.

**Models:** `$fillable` + `$casts`. Accessors `getFooAttribute` (não `Attribute::get`). Sem observers.

**Views:** `<x-app-layout>` + `<x-page-card>`. Listagens com busca GET, `paginate(10)`, alerta de sucesso. **Não** copiar `members/files/index.blade.php`.

**Rotas:** domínio em `web.php` dentro do grupo autenticado. Prefixo `financial/` para o módulo financeiro; `worship/` para músicas/repertórios. Names: `financial.transactions.*`, `departments.*`, `songs.*`, `worship-sets.*`.

**Feedback:** `->with('success', '...')`. Erros de regra usam `->with('error', ...)`. Index de campanha/subcategoria **não** mostram `error`.

**JS:** Alpine no Blade; globais em `resources/js/app.js`. Delete: classe `btn-delete`. Dashboard financeiro: entry Vite separado. Transações: script em `public/js/` (não Vite).

**Testes:** Pest + `RefreshDatabase` só em `tests/Feature` (`tests/Pest.php`). Factories: `User`, `Member`, `Department`. Testes de domínio quase só Member — e o Feature de Member autentica `User::factory()` **sem** permissão Spatie.

**Não há:** PHP enums, DTOs, repositories, API Resource, `Log::`, filas de domínio.

**Padrão para feature nova:** rota no grupo auth → controller com `authorize` → FormRequest → model/migration → views no estilo existente → link na sidebar (dois blocos) → permissão no seeder se for capacidade nova.

---

# Como implementar novas funcionalidades

| O quê | Onde |
|---|---|
| Rota web | `routes/web.php` no grupo `auth` + `password.changed` (auth Breeze só em `routes/auth.php`) |
| Permissão | `RolePermissionSeeder` + `$this->authorize` + `@can` na sidebar/view |
| Controller | `app/Http/Controllers/NomeController.php` |
| Validação | `StoreNomeRequest` / `UpdateNomeRequest` |
| Regra de negócio | Controller, ou accessor/método no model se for derivado (`Campaign::progress`). `FileService` só para arquivo |
| Schema | `database/migrations/` |
| Model | `app/Models/` com `$fillable`, `$casts`, ambos os lados da relação |
| UI | `resources/views/<recurso>/` + componente em `components/` se reutilizável |
| Menu | `resources/views/layouts/sidebar.blade.php` (duplicar nos dois blocos) |
| Assets JS pontuais | `resources/js/` e import em `app.js` se for global; Vite `input` se for CSS/JS de página (como o dashboard financeiro) |
| Testes | `tests/Feature/` com Pest; usuário de teste precisa da permissão Spatie se o controller autoriza |
| Comando artisan | `app/Console/Commands/` (scheduler só se passar a existir tarefa real) |
| JSON | No mesmo controller web, como relatórios/dashboard. Não criar `routes/api.php` só por costume |

Valores de enum: copiar os strings já persistidos; não introduzir PHP `enum` sem migrar o banco e os `in:` dos FormRequests.

---

# Pontos sensíveis

1. **Branch `develop` vs `main`.** O produto autenticado com papéis está em `develop`. `main` é outro snapshot.
2. **Finanças.** `type`, formato de `amount` (mínimo 0.01), agrupamento do relatório, progresso de campanha só com `entrada`, FK restrict transação↔subcategoria, collection `comprovantes`.
3. **Contrato JSON** dos relatórios e do `financial.dashboard.data`.
4. **Dois formatos de data** (datepicker BR vs `type="date"`). FormRequests de campanha/transação usam `Carbon::createFromFormat('d/m/Y', ...)` sem try/catch; campanha nem checa data opcional vazia.
5. **Departamentos:** papéis disjuntos líder vs membro; binding `$department`.
6. **Enums divergentes** Member vs Visitor (gênero).
7. **Upload:** disco vs URL; collections `foto_perfil` / `comprovantes` / `default`; exclusão de membro sem limpar storage.
8. **Unique do repertório** impede música repetida.
9. **Spatie:** nomes de permissão e role são o contrato. Não usar `admin` no lugar de `administrador`.
10. **Registro fechado** — não reabrir `/register` sem decisão explícita. `RegistrationTest` ainda espera as rotas ligadas.
11. **Migrations históricas** de transação (categoria → subcategoria) e `used_keys`. Não recriar colunas dropadas.
12. **Rota `GET /financial/categories/subcategories`** está **depois** do `Route::resource('categories')`; o show do resource pode capturar `subcategories` como `{financialCategory}`. O JS legado ainda chama `/categories/subcategories` sem o prefixo `/financial`.
13. **Sentry/Clarity** dependem de env; não hardcodar DSN/ID.

---

# O que NÃO fazer

- Não criar `app/Repositories`, Events ou Jobs para uma feature que o resto resolve no controller.
- Não criar Policy por model se o padrão é `$this->authorize('permissao_spatie')`.
- Não ligar transação direto em `financial_category_id` (coluna removida).
- Não usar os valores de gênero/estado civil de Member em Visitor (ou vice-versa).
- Não colocar o mesmo membro como responsável e membro de departamento.
- Não apagar subcategoria/campanha com transações sem a checagem já existente (e não “confiar” só no cascade da categoria).
- Não alterar chaves JSON dos relatórios/dashboard financeiro sem atualizar consumidores e este arquivo.
- Não calcular progresso de campanha somando `saida`.
- Não assumir que `Member` autentica, nem que há multi-igreja/tenant.
- Não adicionar Redis/S3/queue workers “porque o .env menciona”.
- Não proteger só o dashboard: o CRUD de domínio já está no grupo `auth` nesta branch; não tirar esse grupo “para simplificar”.
- Não escrever views novas com `@extends('layouts.app')` / `@yield`.
- Não introduzir API Sanctum/token sem decisão explícita: o app é sessão + Blade.
- Não commitar secrets. `admin@igreja.com` / `admin123` e senha Docker são só desenvolvimento.
- Não mergear `feature/select-component` substituindo `x-select` globalmente sem adaptar todos os forms (valores/erros).
- Não trabalhar em `main` como se fosse o estado atual do produto.

---

# Ambiente de desenvolvimento

Resumo; detalhes no `README.md`.

```bash
docker compose up -d
cp .env.example .env
php artisan key:generate
composer install && npm install && npm run build
chmod -R ug+rwx storage bootstrap/cache
php artisan storage:link
php artisan migrate --seed
php artisan serve   # ou: composer dev
```

`.env.example`: `DB_HOST=127.0.0.1`, `DB_PORT=3311`, `DB_DATABASE=sistema_igreja`, usuário/senha `sistema_igreja` / `password`. App: `http://127.0.0.1:8000`.

Após seed: `admin@igreja.com` / `admin123` (role `administrador`).

Navbar mostra “Este é um ambiente de teste.” quando `APP_ENV !== production`.

`composer dev` sobe serve + `queue:listen` + Pail + Vite. A fila não processa jobs da aplicação.

Locale: `.env.example` tem `APP_LOCALE=en`, mas a UI e as mensagens de validação estão em português.

---

# Testes e validação

```bash
php artisan test   # ou: composer test
# se o PHP padrão da máquina for 8.5: php8.4 artisan test
```

Pest, SQLite `:memory:`, `RefreshDatabase` em Feature.

O que existe:

- `tests/Feature/Auth/*` — Breeze (login, registro, senha, verificação de e-mail)
- `tests/Feature/Profile/ProfileTest.php`
- `tests/Feature/Member/MemberControllerTest.php` e `MemberModelTest.php`
- `tests/Unit/Member/*` — model e FormRequests de membro

Não há testes de finanças, departamentos, louvor, dashboard, roles, anexos ou relatórios.

Pendências concretas nos testes atuais (verificado com `php8.4 artisan test`: 15 falhas, 64 passando):

- Feature de Member: `actingAs(User::factory()->create())` **sem** permissão Spatie → **403** nos fluxos de CRUD (validações de FormRequest ainda passam porque o `authorize()` do request é `true`).
- `RegistrationTest` espera `GET/POST /register` 200; as rotas estão comentadas → **404**.
- `EmailVerificationTest` **passa** mesmo sem `MustVerifyEmail` no model (as rotas Breeze de verificação ainda existem).

Após alterar domínio, o mínimo honesto é um Feature test do fluxo feliz + da regra tocada, com usuário que tenha a permissão certa.

Não há CI (`.github/workflows`) nesta branch (foi adicionado e removido no histórico).

---

# Dívidas técnicas e observações

Registradas para contexto; **não refatorar só porque estão nesta lista.**

- `main` desatualizada em relação a `develop` (58 commits).
- `origin/feature/select-component` não mergeada; docs daquela branch pedem integração nos forms reais.
- `AdminPolicy` usa role `admin`; seeder usa `administrador`. Policy aparentemente não é chamada.
- Permissões de exportar relatórios sem implementação.
- Testes de Member/Auth desalinhados com permissões e registro desligado.
- `session('error')` não é exibido nas listagens de campanha/subcategoria.
- Tela `members/files` órfã e com layout Blade incompatível.
- Disco `local` vs URL vazia nas fotos; dashboard monta `storage/` na mão.
- Resource `financial.categories` inclui `show`, mas o método não existe no controller; destroy de categoria sem guarda de transações.
- `getSubcategories`: ordem da rota vs resource; JS legado com URL sem prefixo `/financial`.
- `File::fileables()` incorreto/não usado.
- Clone de repertório em model não persistido.
- Campanha `prepareForValidation` em datas vazias pode lançar exception.
- Progresso de campanha dispara query extra por accessor.
- `page-card` hardcoda texto do botão se a URL contém `worship-sets`.
- Sidebar desktop/mobile duplicada.
- `UserController::index` (e possivelmente outros) usa `orWhere` da busca fora de closure.
- `TransactionReportSeeder` ainda é chamado no seed padrão (dados de jul/2024, sobretudo saídas) **além** de `FinancialTransactionSeeder`.
- `composer.json` ainda descreve o skeleton Laravel.
- `deploy.sh` roda `migrate --seed` em deploy — perigoso se o ambiente for tratado como production (`DatabaseSeeder` aborta em production, mas o script precisa do `APP_ENV` certo).
- Não determinado: se transações de `saida` em campanha deveriam reduzir o progresso.
- Não determinado: se categorias/subcategorias inativas devem continuar selecionáveis na edição de transações antigas.
- Não determinado: se o registro público deve permanecer desligado em produção.
- Não determinado: se `FILESYSTEM_DISK` em produção é `public`.
