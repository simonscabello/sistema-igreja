# Sistema Igreja

Aplicação Laravel para gestão de igreja (membros, finanças, departamentos, louvor, visitantes, usuários e permissões).

A linha de desenvolvimento ativa é a branch **`develop`**. Contexto para agentes de IA: `AGENTS.md`.

## Requisitos

- PHP 8.2+ (recomendado **8.4**; o projeto usa Laravel 12.19)
- Extensões PHP: `pdo_mysql`, `mbstring`, `openssl`, `gd`, `xml`, `curl`, `zip`, `bcmath`, `intl`
- Composer 2
- Node.js 18+ e npm
- Docker e Docker Compose

## Ambiente local

O banco MySQL 8.0 sobe em Docker na porta **3311** (para não conflitar com outros MySQL na 3306). A aplicação roda no host.

### 1. Subir o banco

```bash
docker compose up -d
```

### 2. Configurar o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

O `.env.example` já aponta para o container:

| Variável | Valor |
|---|---|
| `DB_HOST` | `127.0.0.1` |
| `DB_PORT` | `3311` |
| `DB_DATABASE` | `sistema_igreja` |
| `DB_USERNAME` | `sistema_igreja` |
| `DB_PASSWORD` | `password` |

Sentry (`SENTRY_LARAVEL_DSN`) e Microsoft Clarity (`CLARITY_ID`) são opcionais e ficam vazios no exemplo.

### 3. Instalar dependências

```bash
composer install
npm install
npm run build
```

Se o PHP padrão da máquina for 8.5, use 8.4 no Composer e no Artisan:

```bash
php8.4 /usr/local/bin/composer install
php8.4 artisan key:generate
```

### 4. Banco, storage e permissões

```bash
chmod -R ug+rwx storage bootstrap/cache
php artisan storage:link
php artisan migrate --seed
```

O seeder **não** roda em `APP_ENV=production`.

### 5. Iniciar a aplicação

Opção simples (servidor + assets já compilados):

```bash
php artisan serve
```

Ou o script do projeto (servidor, fila, logs e Vite):

```bash
composer dev
```

Acesse [http://127.0.0.1:8000](http://127.0.0.1:8000).

### Usuário de desenvolvimento

Após o seeder (`RolePermissionSeeder`):

- **E-mail:** `admin@igreja.com`
- **Senha:** `admin123`
- **Role:** `administrador`

Registro público (`/register`) está desligado; novos usuários nascem pelo CRUD de usuários.

## Frontend (React + Inertia)

A interface autenticada é **React 19 + TypeScript** via **Inertia.js**. O Laravel continua responsável por rotas, sessão, validação e autorização.

```bash
npm run dev      # Vite com HMR (desenvolvimento)
npm run build    # build de produção
composer dev     # servidor Laravel + Vite + fila + logs
```

Código frontend em `resources/js/`. Única view Blade da aplicação: `resources/views/app.blade.php` (shell Inertia).

Detalhes para agentes de IA: seção **Frontend React (Inertia)** em `AGENTS.md`.

## Serviços

- **MySQL 8.0** — único container necessário (cache, sessão e fila usam o banco)
- **Redis** — não é usado neste projeto
- **Fila** — `QUEUE_CONNECTION=database`. Não há jobs na aplicação; `php artisan queue:listen` só é necessário se você usar `composer dev`
- **Scheduler** — não há tarefas agendadas
- **E-mail** — `MAIL_MAILER=log` (grava em `storage/logs`)
- **Upload de arquivos** — disco `local` no exemplo; URLs de foto tendem a exigir disco `public` e `php artisan storage:link`
- **Sentry / Clarity** — só enviam dados se as variáveis de ambiente estiverem preenchidas

## Comandos úteis

```bash
docker compose ps
php artisan migrate:status
php artisan about
php artisan test
```

Para parar o banco: `docker compose stop`. Para remover o container mantendo os dados: `docker compose down`.
