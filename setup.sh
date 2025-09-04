#!/bin/bash

# CV Sender - Script de Configuração Rápida
# Execute este script para configurar o projeto automaticamente

echo "🚀 Iniciando configuração do CV Sender..."

# Verificar se o PHP está instalado
if ! command -v php &> /dev/null; then
    echo "❌ PHP não encontrado. Por favor, instale o PHP 8.2+ antes de continuar."
    exit 1
fi

# Verificar se o Composer está instalado
if ! command -v composer &> /dev/null; then
    echo "❌ Composer não encontrado. Por favor, instale o Composer antes de continuar."
    exit 1
fi

# Verificar se o Node.js está instalado
if ! command -v node &> /dev/null; then
    echo "❌ Node.js não encontrado. Por favor, instale o Node.js 18+ antes de continuar."
    exit 1
fi

# Verificar se o NPM está instalado
if ! command -v npm &> /dev/null; then
    echo "❌ NPM não encontrado. Por favor, instale o NPM antes de continuar."
    exit 1
fi

echo "✅ Pré-requisitos verificados com sucesso!"

# 1. Instalar dependências PHP
echo "📦 Instalando dependências PHP..."
composer install --no-interaction --prefer-dist --optimize-autoloader

if [ $? -ne 0 ]; then
    echo "❌ Erro ao instalar dependências PHP"
    exit 1
fi

# 2. Instalar dependências JavaScript
echo "📦 Instalando dependências JavaScript..."
npm install

if [ $? -ne 0 ]; then
    echo "❌ Erro ao instalar dependências JavaScript"
    exit 1
fi

# 3. Configurar arquivo .env
if [ ! -f .env ]; then
    echo "⚙️ Criando arquivo .env..."
    cp .env.example .env
    
    # Gerar chave da aplicação
    echo "🔑 Gerando chave da aplicação..."
    php artisan key:generate --no-interaction
    
    echo "✅ Arquivo .env criado com sucesso!"
    echo "ℹ️  Por favor, configure as credenciais do banco de dados no arquivo .env"
else
    echo "ℹ️  Arquivo .env já existe. Pulando configuração..."
fi

# 4. Criar banco SQLite (se não existir configuração de BD)
if grep -q "DB_CONNECTION=sqlite" .env; then
    echo "💾 Configurando banco SQLite..."
    touch database/database.sqlite
    echo "✅ Banco SQLite criado!"
fi

# 5. Executar migrações
echo "🗃️  Executando migrações do banco de dados..."
php artisan migrate --no-interaction

if [ $? -ne 0 ]; then
    echo "⚠️  Erro ao executar migrações. Verifique as configurações do banco de dados no .env"
    echo "   Você pode executar 'php artisan migrate' manualmente após configurar o banco"
else
    echo "✅ Migrações executadas com sucesso!"
    
    # 6. Executar seeders (opcional)
    read -p "🌱 Deseja criar dados de teste? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo "🌱 Executando seeders..."
        php artisan db:seed --no-interaction
        echo "✅ Dados de teste criados!"
        echo ""
        echo "👥 Usuários de teste criados:"
        echo "   Admin: admin@example.com / password"
        echo "   Usuário: user@example.com / password"
    fi
fi

# 7. Criar link simbólico para storage
echo "🔗 Criando link simbólico para storage..."
php artisan storage:link --no-interaction

# 8. Compilar assets
echo "🎨 Compilando assets..."
npm run build

if [ $? -ne 0 ]; then
    echo "⚠️  Erro ao compilar assets. Execute 'npm run dev' manualmente"
else
    echo "✅ Assets compilados com sucesso!"
fi

# 9. Configurar permissões (Linux/Mac)
if [[ "$OSTYPE" == "linux-gnu"* ]] || [[ "$OSTYPE" == "darwin"* ]]; then
    echo "🔐 Configurando permissões..."
    chmod -R 775 storage bootstrap/cache
    echo "✅ Permissões configuradas!"
fi

echo ""
echo "🎉 Configuração concluída com sucesso!"
echo ""
echo "📋 Próximos passos:"
echo "1. Configure as credenciais do banco de dados no arquivo .env (se não usando SQLite)"
echo "2. Execute 'php artisan serve' para iniciar o servidor"
echo "3. Execute 'npm run dev' em outro terminal para development"
echo "4. Acesse http://localhost:8000 para ver a aplicação"
echo ""
echo "📖 Para mais informações, consulte o README.md"
echo ""
echo "🚀 Pronto para usar o CV Sender!"
