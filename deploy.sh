#!/bin/bash
set -euo pipefail

# Script de deploy para Laravel 12
# Uso: ./deploy.sh

echo "🚀 Iniciando deploy da aplicação Laravel..."

# Verificar se estamos no diretório raiz do projeto Laravel
if [ ! -f "artisan" ]; then
    echo "❌ Erro: Execute este script no diretório raiz do projeto Laravel"
    exit 1
fi

# Verificar comandos essenciais
echo "🔍 Verificando dependências do sistema..."

check_command() {
    if ! command -v "$1" &> /dev/null; then
        echo "❌ Erro: Comando '$1' não encontrado no PATH"
        exit 1
    fi
}

check_command "php"
check_command "composer2"

# Verificar e instalar NVM se necessário
echo "📦 Verificando NVM..."
if [ ! -d "$HOME/.nvm" ]; then
    echo "📦 Instalando NVM..."
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash

    # Carregar NVM no shell atual
    export NVM_DIR="$HOME/.nvm"
    [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
    [ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"
else
    echo "✅ NVM já está instalado"
    # Carregar NVM
    export NVM_DIR="$HOME/.nvm"
    [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
    [ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"
fi

# Instalar Node.js LTS
echo "📦 Instalando Node.js LTS..."
# Desabilitar temporariamente set -u para evitar erro com NVM
set +u
nvm install --lts
nvm use --lts
set -u

# Verificar npm após instalação do Node.js
check_command "npm"

echo "✅ Todas as dependências estão disponíveis"

# Instalar dependências do Composer
echo "📦 Instalando dependências do Composer..."
composer2 install --no-interaction --prefer-dist --optimize-autoloader

# Instalar dependências do Node.js
echo "📦 Instalando dependências do Node.js..."
npm ci

# Compilar assets
echo "🔨 Compilando assets..."
npm run build

# Limpar node_modules após build
echo "🧹 Removendo node_modules (não necessário em produção)..."
rm -rf node_modules

# Executar migrações
echo "🗄️ Executando migrações do banco de dados..."
php artisan migrate --force

# Limpar caches
echo "🧹 Limpando caches..."
php artisan optimize:clear

# Cache de configuração
echo "⚙️ Cacheando configurações..."
php artisan config:cache

# Cache de eventos
echo "🎯 Cacheando eventos..."
php artisan event:cache

# Cache de rotas
echo "🛣️ Cacheando rotas..."
php artisan route:cache

# Cache de views
echo "👁️ Cacheando views..."
php artisan view:cache

echo "✅ Deploy finalizado com sucesso!"
