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
