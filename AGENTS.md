# Contexto do sistema para agentes de IA

Leia este arquivo **antes** de alterar qualquer código. Ele descreve o que o sistema é nesta branch (`develop`), como está organizado e o que não deve ser quebrado.

Documentação complementar (não duplicar; algumas notas de implementação podem estar desatualizadas em relação a este arquivo):

- `README.md` — instalação e ambiente local
- `database/seeders/RolePermissionSeeder.php` — fonte de verdade de roles e permissões
- `PERMISSOES_IMPLEMENTADAS.md` / `ROLES_PERMISSIONS_RESUMO.md` / `docs/permissoes.md` — notas de roles (o mapeamento de `FinancialReportController` nesses arquivos ainda cita `__invoke`; o controller atual usa `index` / `monthly` / `annualDetailed` / `annualSummary`)
- `docs/upload-sistema.md` / `IMPLEMENTACAO_UPLOAD_TRANSACOES.md` — upload de arquivos
- `MELHORIAS_CAMPO_VALOR.md` — máscara e validação de valores monetários (backend FormRequest; UI usa `CurrencyInput` React)

A linha de desenvolvimento ativa é **`develop`**. `main` está congelada em junho/2025 e **não** contém auth nas rotas de domínio, Spatie, dashboards nem relatórios anuais. Não implemente features em `main` assumindo que ela é o produto atual.

Não existe `master`. A branch remota `origin/feature/select-component` ficou como ponteiro histórico.

---

# Visão geral do sistema

**Sistema Igreja** é uma aplicação web monolítica Laravel para gestão operacional de uma igreja.

Usuários do sistema (`User`) fazem login e têm **roles/permissões Spatie**. Os **membros da igreja** (`Member`) são cadastros pastorais/administrativos, **não** contas de acesso. Não há FK entre `User` e `Member`. Não “logar como membro” nem exigir e-mail de membro igual ao de usuário.

Módulos atuais:

| Módulo | Para que serve |
|---|---|
| Dashboard | Semana da igreja: aniversariantes, visitantes recentes, saldo, próximo culto, atalhos |
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

Não é um site público da igreja. O dashboard autenticado é `DashboardController` + página React `Dashboard.tsx`.

---

# Stack tecnológica

| Tecnologia | Uso no projeto |
|---|---|
| PHP **8.5** (`composer.json`: `^8.5`) | Runtime |
| Laravel **13.29** | Framework (`composer.lock`) |
| Laravel Breeze 2.4 | Auth (login, reset de senha, perfil). **Registro público está comentado** em `routes/auth.php` |
| Inertia.js 3 + React 19 + TypeScript | Interface (SPA-like com sessão Laravel) |
| Ziggy | Rotas nomeadas no frontend (`route('members.index')`) |
| Spatie Laravel Permission 8.3 | Roles e permissões (`spatie/laravel-permission`) |
| Sentry Laravel 4.27 | Exceções via `Sentry\Laravel\Integration::handles()` em `bootstrap/app.php` |
| MySQL 8.0 | Banco (Docker na porta **3311**) |
| Tailwind CSS 3 | Estilos (tokens `primary`, `canvas`, `surface`, `ink`, `line`; dark mode `class`) |
| Lucide React | Ícones da navegação e da UI |
| ApexCharts 5 + react-apexcharts | Gráfico do dashboard financeiro |
| react-number-format | Máscara de moeda BR na UI |
| SweetAlert2 | Confirmação de exclusão |
| Vite 6.4 | Build (`resources/css/app.css`, `resources/js/app.tsx`). Não migrado para Vite 8 / Tailwind 4 (escopo de toolchain, não de produto) |
| Intervention Image 3.11 (driver GD) | Redimensionamento de imagens. Image 4 não foi adotada (API diferente; o `FileService` permanece na API v3) |
| Pest 5 + PHPUnit 13 | Testes |
| Laravel Pail / PAO | Logs no `composer dev`; PAO só altera output quando detecta agente de IA |
| Docker Compose | **Somente MySQL 8.0**; a app roda no host com PHP 8.5. `laravel/sail` foi removido (não era usado) |

Não usado neste projeto, apesar de aparecer no `.env.example`: Redis, AWS S3, Postmark, Slack, SES. Clarity e Sentry **estão no código**, mas só disparam se `CLARITY_ID` / `SENTRY_LARAVEL_DSN` (ou `SENTRY_DSN`) estiverem definidos.

Fila: `QUEUE_CONNECTION=database`, mas **não existem Jobs** em `app/Jobs`. Cache e sessão também usam o banco.

Pacotes **removidos** no upgrade para Laravel 13: `laravel/sail` (Compose próprio, só MySQL), `alpinejs` / `@alpinejs/mask` (UI é React), `@tailwindcss/vite` (Tailwind 3 via PostCSS). **Não migrados de propósito:** Vite 8, Tailwind 4, Intervention Image 4, ApexCharts 7, Flowbite 4 (breaking changes de frontend/API sem ganho imediato para este app).

Sessão: Laravel 13 permite serialização `json`; este projeto permanece em `php` para não derrubar sessões ativas no deploy. Prefixos de cache/cookie foram pinados no `.env.example` no formato antigo (`sistema_igreja_cache_`, `sistema_igreja_session`).

PHP 8.5: `config/database.php` usa `Pdo\Mysql::ATTR_SSL_CA` no lugar da constante deprecada `PDO::MYSQL_ATTR_SSL_CA`.

---

# Arquitetura

MVC clássico Laravel, **sem camada de repositório, DTO, Event/Listener de domínio ou API REST**. Existe **uma** Policy (`app/Policies/AdminPolicy.php`), mas os controllers **não a usam** — autorização real é `$this->authorize('nome_da_permissao')` (Spatie registra cada permissão como Gate).

```
Browser (React + Inertia)
 → routes/web.php (middleware auth + password.changed)
 → HandleInertiaRequests (auth.user, flash, ziggy)
 → FormRequest (validação + normalização; authorize() === true)
 → Controller ($this->authorize('permissao') + Inertia::render / redirect)
 → Eloquent Model
 → MySQL
 → props JSON → página React em resources/js/pages/
```

O único service de aplicação é `app/Services/FileService.php` (upload/delete e processamento de imagem). **Regras de negócio ficam no controller, no FormRequest ou em accessors do model.** Não invente hexagonal/service-repository para uma feature isolada.

Não existe `routes/api.php`. JSON pontual no mesmo controller web:

- relatório financeiro se `ajax()` / `wantsJson()` **e não for visita Inertia** (`X-Inertia`); visita Inertia deve renderizar a página React
- dados do gráfico: `GET financial.dashboard.data` (`FinancialDashboardController::getData`)
- subcategorias: `GET financial.categories.subcategories` (`FinancialCategoryController::getSubcategories`) — ver pontas sensíveis

**Blade restante:** apenas `resources/views/app.blade.php` (shell Inertia: Vite, CSRF, Ziggy, Clarity). Templates de e-mail do framework permanecem em `vendor/`.

Binding: implicit route model binding. Departamentos usam `{department}` / `Department $department` (rotas `departments.*`, não `departamentos.*`). Recursos financeiros usam prefixo `/financial` e names `financial.*`.

---

# Estrutura importante

```
app/
  Http/Controllers/       um controller por recurso; GET → Inertia::render
  Http/Middleware/        EnsurePasswordChanged + HandleInertiaRequests
  Http/Requests/          Store/Update por recurso
  Models/                 Eloquent + Traits/HasFiles.php
  Services/               só FileService
bootstrap/app.php         rotas, aliases Spatie + password.changed, Sentry, Inertia middleware
resources/views/          só app.blade.php (root Inertia)
resources/js/
  app.tsx                 entry Vite + createInertiaApp
  pages/                  páginas React (espelham rotas: Members/Index, Auth/Login, …)
  layouts/                AppLayout, GuestLayout
  components/ui/          Button, Input, PageCard, AdvancedSelect, …
  components/layout/      Sidebar, Can, FlashMessages
  hooks/                  useCan, useTheme, useViaCep, useDisjointSelection
  utils/                  route (Ziggy), formatDateBr, formatCurrency
  types/                  PageProps, Paginated, User
resources/css/app.css     Tailwind + tokens
routes/web.php            rotas de domínio (grupo auth)
routes/auth.php           Breeze; registro comentado
tests/                    Pest; assertInertia nos Feature tests de domínio
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

Um `Member` **não pode** estar nas duas listas (`StoreDepartmentRequest` / `UpdateDepartmentRequest` `withValidator`). O `x-select` avançado em `resources/js/departments/department-member-selects.js` reforça na UI. O banco **não** impede sobreposição entre as duas tabelas — só unique por par dentro de cada uma.

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

Formulário de transação: componentes React em `resources/js/pages/Financial/components/TransactionFields.tsx` (categoria filtra subcategorias ativas). Só `financial_subcategory_id` é persistido. Campanha e anexo opcionais. Rotas: `financial.transactions.*`.

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

Form escolhe categoria (React filtra subcategorias ativas de `$categories`). Só `financial_subcategory_id` é persistido. Campanha e anexo opcionais. Rotas: `financial.transactions.*`.

## Relatório / dashboard financeiro

- HTML: `GET /financial/reports/monthly|annual/detailed|annual/summary`
- JSON: mesmo endpoint com AJAX/`wantsJson()` **sem** header `X-Inertia` (Inertia Link/router não pode cair no JSON)
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
| ViaCEP | `useViaCep` em `AddressFields` | Sem chave. Falha silencia e limpa rua/bairro/cidade/UF. |
| Microsoft Clarity | `layouts/app.blade.php` e `guest.blade.php` se `config('services.clarity.id')` (`CLARITY_ID`) | Sem ID, o script não é emitido. Não commitar ID de produção no repo. |
| Sentry | `bootstrap/app.php` + `config/sentry.php` | DSN via `SENTRY_LARAVEL_DSN` ou `SENTRY_DSN`. Sem DSN, SDK não envia. |
| YouTube / Spotify / cifra / letra | URLs em `songs` | Sem API; `target="_blank"`. Validação `url`. |
| CDN jsDelivr | SweetAlert2, datepicker pt-BR no layout | Layout quebra offline. |
| fonts.bunny.net | Figtree (única família da UI) |
| Intervention/GD | `FileService::processImage` | Precisa da extensão `gd`. |
| Mail | `MAIL_MAILER=log` | Reset de senha grava em `storage/logs`. |

Não há webhooks, OAuth, gateways de pagamento nem S3 configurado de fato.

Nunca documente ou commite tokens reais. AWS/Redis no `.env.example` são placeholders. Credenciais Docker/`admin123` são só desenvolvimento.

---

# Convenções do projeto

Observadas no código — não inventar as que não existem.

**Controllers:** resource methods padrão. GET retorna `Inertia\Response` via `Inertia::render('Modulo/Pagina', $props)`. Store/update/destroy retornam `RedirectResponse`. Invokable só `RootRedirectController`.

**FormRequests:** `StoreXRequest` / `UpdateXRequest`. Sempre `authorize(): true`. Mensagens e `attributes()` em português. Datas BR e dinheiro BR em `prepareForValidation` — a UI React envia nesses formatos.

**Autorização:** `$this->authorize('snake_case_portugues')` no controller (obrigatório) + `<Can permission="...">` na UI React + item na sidebar (`components/layout/Sidebar.tsx`). Nova capacidade = nova string no `RolePermissionSeeder`.

**Models:** `$fillable` + `$casts`. Accessors `getFooAttribute`. Sem observers.

**Frontend React:** páginas em `resources/js/pages/`, layout via `Page.layout = (page) => <AppLayout>{page}</AppLayout>`. Formulários com `useForm` do `@inertiajs/react`. Uploads: `forceFormData: true`. Listagens: `PageCard` (shell da página: título, descrição, ação primária) + `SearchForm` + `TableShell`/`Pagination`. Não embrulhar a página inteira em um card extra.

**Rotas:** domínio em `web.php` dentro do grupo autenticado. Prefixo `financial/`; `worship/` para louvor. Names: `financial.transactions.*`, `departments.*`, `songs.*`, `worship-sets.*`.

**Feedback:** `->with('success', '...')` / `->with('error', '...')` — lidos via `flash` em `HandleInertiaRequests` e exibidos por `<FlashMessages />`.

**Testes:** Pest + `RefreshDatabase`. Feature tests de domínio usam `userWithPermissions([...])` e `assertInertia`. RegistrationTest skipped (rotas comentadas).

**Padrão para feature nova:** rota → controller (`authorize` + `Inertia::render`) → FormRequest → model/migration → página TSX → link na sidebar → permissão no seeder se capacidade nova → teste Feature com permissões.

---

# Frontend React (Inertia)

## Comunicação Laravel ↔ React

1. Request HTTP normal (sessão + CSRF).
2. Controller autoriza e retorna `Inertia::render('Members/Index', ['members' => $members])`.
3. `HandleInertiaRequests` mescla props compartilhadas: `auth.user` (com `permissions[]`), `flash`, `app`, `ziggy`.
4. React renderiza a página resolvida em `resources/js/pages/Members/Index.tsx`.
5. Formulários POST/PUT/DELETE via `useForm` → redirect Laravel → flash na próxima visita.

**Exceções JSON** (fetch axios no React): `financial.dashboard.data`. Relatórios só devolvem JSON se a request for AJAX/`wantsJson()` **sem** `X-Inertia`.

## Estrutura de diretórios

```
resources/js/
  app.tsx
  pages/           # uma pasta por módulo (Members/, Financial/, Auth/, …)
  layouts/         # AppLayout (autenticado), GuestLayout (login)
  components/ui/   # Button, Input, PageCard, Badge, EmptyState, DataTable, FormSection, …
  components/layout/  # Sidebar (nav por trabalho), Can, FlashMessages
  hooks/           # useCan, useTheme, useViaCep, useDisjointSelection
  utils/           # route(), formatDateBr, formatCurrency
  types/           # PageProps, Paginated, User
```

## Design system e UX

Identidade: secretaria da SIB — teal `#2F8A7E`, fundo mint-stone (`canvas`), superfície branca (`surface`), texto `ink`. Dark mode é o mesmo sistema, não cinza genérico. Família única: **Figtree**.

Tokens Tailwind: `primary` / `primary-dark` / `primary-light`, `canvas` / `canvas-dark`, `surface` / `surface-dark`, `ink` / `ink-muted` / `ink-inverse`, `line` / `line-dark`, `entrada` / `saida`, `accent`. Preferir esses nomes a `gray-*` em telas novas.

Componentes de página:

- `PageCard` — shell: `Head` title, breadcrumbs, título, descrição, ação primária. Sem card externo.
- `PageHeader` — só o cabeçalho, se a página não usar `PageCard`.
- `TableShell` + `Table`/`Th`/`Td` — listagem desktop; `MobileList`/`MobileCard` no celular.
- `EmptyState` — vazio com título, explicação e CTA opcional.
- `FormPanel` + `FormSection` + `FormActions` — formulários longos (seções + barra de salvar).
- `Badge` — status; não usar cor sozinha (texto no badge).
- `Button` — `primary` (uma por tela), `secondary`, `danger`, `ghost`, `link`. Sem caixa-alta.
- `FlashMessages` — toast no topo; sucesso some em 4s.

Navegação (sidebar, agrupada por trabalho):

- Início
- Pessoas: Membros, Visitantes, Departamentos
- Finanças: Caixa, Transações, Campanhas, Relatórios, Categorias, Subcategorias
- Louvor: Músicas, Cultos
- Sistema: Usuários, Papéis, Permissões

Rótulos em português. “Roles” na UI é **Papéis**. Catálogo de louvor é **Músicas**; setlist é **Cultos**. Dashboard financeiro é **Caixa**.

Convenções de UX:

- Uma ação primária por tela (botão teal no cabeçalho). Ver/Editar na linha são links.
- Copy curta e humana. Erro diz o que corrigir. Vazio diz o próximo passo.
- Alvos de toque ≥ 44px (`min-h-touch`). Paginação visível no mobile.
- Respeitar `prefers-reduced-motion`. Foco visível (`:focus-visible`).
- Não empilhar cards só para “preencher”. Card = agrupamento real.

## Nova página (checklist para IA)

1. Rota em `routes/web.php` (grupo `auth` + `password.changed`).
2. Permissão em `RolePermissionSeeder` se for capacidade nova.
3. Controller: `$this->authorize('...')` + `return Inertia::render('Modulo/Acao', $props)`.
4. Criar `resources/js/pages/Modulo/Acao.tsx` com layout `AppLayout` ou `GuestLayout`.
5. `PageCard` com título, descrição e ação primária; conteúdo no canvas.
6. Listagem: `SearchForm` + `TableShell` + `MobileList` + `EmptyState` + `Pagination`.
7. Formulário: `FormPanel` / `FormSection`; datas `dd/mm/aaaa`; moeda `CurrencyInput`; upload `forceFormData: true`.
8. Permissões com `<Can>`. Link no `Sidebar.tsx` no grupo certo, com ícone Lucide.
9. Teste Feature: `userWithPermissions([...])` + `assertInertia(fn ($page) => $page->component('Modulo/Acao'))`.

## Build e desenvolvimento

```bash
npm run dev      # Vite HMR (usar com php artisan serve ou composer dev)
npm run build    # produção
composer dev     # serve + queue + pail + vite
```

Entry Vite: `resources/js/app.tsx` + `resources/css/app.css`. Root Blade: `resources/views/app.blade.php`.

---

# Como implementar novas funcionalidades

| O quê | Onde |
|---|---|
| Rota web | `routes/web.php` no grupo `auth` + `password.changed` |
| Permissão | `RolePermissionSeeder` + `$this->authorize` + `<Can>` + sidebar |
| Controller GET | `Inertia::render('Modulo/Pagina', $props)` |
| Validação | `StoreNomeRequest` / `UpdateNomeRequest` |
| Regra de negócio | Controller, accessor ou model. `FileService` só para arquivo |
| Schema | `database/migrations/` |
| Model | `app/Models/` |
| UI | `resources/js/pages/Modulo/` + componentes reutilizáveis em `components/ui/` |
| Menu | `resources/js/components/layout/Sidebar.tsx` |
| Testes | `tests/Feature/` com `userWithPermissions` + `assertInertia` |
| JSON pontual | No mesmo controller web (dashboard financeiro, relatórios) |

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
php artisan serve   # ou: composer dev (`php artisan dev`: serve + fila + Pail + Vite)
```

`.env.example`: `DB_HOST=127.0.0.1`, `DB_PORT=3311`, `DB_DATABASE=sistema_igreja`, usuário/senha `sistema_igreja` / `password`. App: `http://127.0.0.1:8000`.

Após seed: `admin@igreja.com` / `admin123` (role `administrador`).

Navbar mostra “Este é um ambiente de teste.” quando `APP_ENV !== production`.

`composer dev` chama `php artisan dev` (Laravel 13): servidor, `queue:listen`, Pail e Vite. A fila não processa jobs da aplicação.

Locale: `.env.example` tem `APP_LOCALE=en`, mas a UI e as mensagens de validação estão em português.

Prefixes de cache/sessão estão pinados (`CACHE_PREFIX=sistema_igreja_cache_`, `SESSION_COOKIE=sistema_igreja_session`) para não invalidar sessões no upgrade Laravel 12 → 13. Serialização de sessão permanece `php` (`SESSION_SERIALIZATION`).

---

# Testes e validação

```bash
php artisan test   # ou: composer test
```

Pest 5, SQLite `:memory:`, `RefreshDatabase` em Feature. PHP da máquina de desenvolvimento: **8.5**.

O que existe:

- `tests/Feature/Auth/*` — Breeze (login, registro, senha, verificação de e-mail)
- `tests/Feature/Profile/ProfileTest.php`
- `tests/Feature/Dashboard/DashboardControllerTest.php`
- `tests/Feature/Member/MemberControllerTest.php` e `MemberModelTest.php`
- `tests/Feature/Department/DepartmentControllerTest.php`
- `tests/Feature/Financial/FinancialReportControllerTest.php`
- `tests/Feature/Song/SongControllerTest.php`
- `tests/Feature/WorshipSet/WorshipSetControllerTest.php`
- `tests/Unit/Member/*` — model e FormRequests de membro

Após o upgrade PHP 8.5 + Laravel 13 (set/2026): **93 passando, 2 skipped** (`RegistrationTest` — rotas `/register` comentadas).

Pendências conhecidas nos testes (não introduzidas pelo upgrade):

- Feature de Member: alguns fluxos ainda usam `actingAs(User::factory()->create())` **sem** permissão Spatie → **403** nos CRUDs que exigem Gate (as validações de FormRequest ainda passam porque `authorize()` do request é `true`).
- `RegistrationTest` está skipped: espera `GET/POST /register` 200; as rotas estão comentadas.
- `EmailVerificationTest` **passa** mesmo sem `MustVerifyEmail` no model (as rotas Breeze de verificação ainda existem).

Após alterar domínio, o mínimo honesto é um Feature test do fluxo feliz + da regra tocada, com usuário que tenha a permissão certa.

Não há CI (`.github/workflows`) nesta branch (foi adicionado e removido no histórico).

---

# Dívidas técnicas e observações

Registradas para contexto; **não refatorar só porque estão nesta lista.**

- `main` desatualizada em relação a `develop` (58 commits).
- `AdminPolicy` usa role `admin`; seeder usa `administrador`. Policy aparentemente não é chamada.
- Permissões de exportar relatórios sem implementação.
- Testes de Member/Auth desalinhados com permissões e registro desligado.
- `session('error')` não é exibido nas listagens de campanha/subcategoria.
- Tela `members/files` órfã e com layout Blade incompatível.
- Disco `local` vs URL vazia nas fotos; dashboard monta `storage/` na mão.
- Resource `financial.categories` inclui `show`, mas o método não existe no controller; destroy de categoria sem guarda de transações.
- `getSubcategories`: ordem da rota vs resource; URL antiga `/categories/subcategories` sem o prefixo `/financial`.
- `File::fileables()` incorreto/não usado.
- Clone de repertório em model não persistido.
- Campanha `prepareForValidation` em datas vazias pode lançar exception.
- Progresso de campanha dispara query extra por accessor.
- `page-card` hardcoda texto do botão se a URL contém `worship-sets`.
- Sidebar desktop/mobile duplicada.
- `UserController::index` (e possivelmente outros) usa `orWhere` da busca fora de closure.
- `TransactionReportSeeder` ainda é chamado no seed padrão (dados de jul/2024, sobretudo saídas) **além** de `FinancialTransactionSeeder`.
- `deploy.sh` roda `migrate --seed` em deploy — perigoso se o ambiente for tratado como production (`DatabaseSeeder` aborta em production, mas o script precisa do `APP_ENV` certo).
- Não determinado: se transações de `saida` em campanha deveriam reduzir o progresso.
- Não determinado: se categorias/subcategorias inativas devem continuar selecionáveis na edição de transações antigas.
- Não determinado: se o registro público deve permanecer desligado em produção.
- Não determinado: se `FILESYSTEM_DISK` em produção é `public`.

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application running on PHP 8.5. You are an expert with the Laravel ecosystem. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Use `search-docs` before changes that depend on Laravel ecosystem APIs, behavior, configuration, or version-specific syntax. Skip it for copy-only edits and other changes where package documentation is irrelevant. Reuse sufficient results already in context instead of searching again.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists (settled decisions, non-obvious traps, standing constraints). Framework and package guidelines that only apply to specific paths (testing, frontend, components) also live there, under `.ai/rules/boost` — this is not just recorded decisions, it is load-bearing guidance you have not seen inline. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.
- Record durable rules with `record-rule` so the next agent or teammate inherits them instead of working them out again. Pass a `glob` (e.g. `app/Http/Controllers/**`), a short `title`, and a few-line `note`. Always use `record-rule`, never your native memory or notes tool — native memory is personal and session-scoped; only `.ai/rules` is shared with the team and persists in the repo.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== tests rules ===

# Test Enforcement

- Test every code change by adding or updating a test.
- Run the affected tests and ensure they pass.
- Test the changed behavior and its important failure modes, but do not add tests beyond them.
- Read the `testing-best-practices` skill before writing tests.

=== inertia-laravel/core rules ===

# Inertia

- Inertia creates fully client-side rendered SPAs without modern SPA complexity, leveraging existing server-side patterns.
- Components live in `resources/js/pages` (unless specified in `vite.config.js`). Use `Inertia::render()` for server-side routing instead of Blade views.
- ALWAYS use `search-docs` tool for version-specific Inertia documentation and updated code examples.
- IMPORTANT: Activate `inertia-react-development` when working with Inertia client-side patterns.

# Inertia v3

- Use all Inertia features from v1, v2, and v3. Check the documentation before making changes to ensure the correct approach.
- New v3 features: standalone HTTP requests (`useHttp` hook), optimistic updates with automatic rollback, layout props (`useLayoutProps` hook), instant visits, simplified SSR via `@inertiajs/vite` plugin, custom exception handling for error pages.
- Carried over from v2: deferred props, infinite scroll, merging props, polling, prefetching, once props, flash data.
- When using deferred props, add an empty state with a pulsing or animated skeleton.
- Axios has been removed. Use the built-in XHR client with interceptors, or install Axios separately if needed.
- `Inertia::lazy()` / `LazyProp` has been removed. Use `Inertia::optional()` instead.
- Prop types (`Inertia::optional()`, `Inertia::defer()`, `Inertia::merge()`) work inside nested arrays with dot-notation paths.
- SSR works automatically in Vite dev mode with `@inertiajs/vite` - no separate Node.js server needed during development.
- Event renames: `invalid` is now `httpException`, `exception` is now `networkError`.
- `router.cancel()` replaced by `router.cancelAll()`.
- The `future` configuration namespace has been removed - all v2 future options are now always enabled.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

# Pest

- This project uses Pest. Create tests with `php artisan make:test --pest {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.
- Do not delete tests or test files without approval. They are part of the application.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/pest` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.
- After the feature tests pass, ask the user to run the complete suite with `php artisan test --compact`.

=== inertia-react/core rules ===

# Inertia + React

- IMPORTANT: Activate `inertia-react-development` when working with Inertia React client-side patterns.

</laravel-boost-guidelines>
