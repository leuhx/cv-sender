# CV Sender - Script de Configuração Rápida (Windows PowerShell)
# Execute este script para configurar o projeto automaticamente

Write-Host "🚀 Iniciando configuração do CV Sender..." -ForegroundColor Green

# Verificar se o PHP está instalado
if (!(Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "❌ PHP não encontrado. Por favor, instale o PHP 8.2+ antes de continuar." -ForegroundColor Red
    exit 1
}

# Verificar se o Composer está instalado
if (!(Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Composer não encontrado. Por favor, instale o Composer antes de continuar." -ForegroundColor Red
    exit 1
}

# Verificar se o Node.js está instalado
if (!(Get-Command node -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Node.js não encontrado. Por favor, instale o Node.js 18+ antes de continuar." -ForegroundColor Red
    exit 1
}

# Verificar se o NPM está instalado
if (!(Get-Command npm -ErrorAction SilentlyContinue)) {
    Write-Host "❌ NPM não encontrado. Por favor, instale o NPM antes de continuar." -ForegroundColor Red
    exit 1
}

Write-Host "✅ Pré-requisitos verificados com sucesso!" -ForegroundColor Green

# 1. Instalar dependências PHP
Write-Host "📦 Instalando dependências PHP..." -ForegroundColor Yellow
& composer install --no-interaction --prefer-dist --optimize-autoloader

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Erro ao instalar dependências PHP" -ForegroundColor Red
    exit 1
}

# 2. Instalar dependências JavaScript
Write-Host "📦 Instalando dependências JavaScript..." -ForegroundColor Yellow
& npm install

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Erro ao instalar dependências JavaScript" -ForegroundColor Red
    exit 1
}

# 3. Configurar arquivo .env
if (!(Test-Path ".env")) {
    Write-Host "⚙️ Criando arquivo .env..." -ForegroundColor Yellow
    Copy-Item ".env.example" ".env"
    
    # Gerar chave da aplicação
    Write-Host "🔑 Gerando chave da aplicação..." -ForegroundColor Yellow
    & php artisan key:generate --no-interaction
    
    Write-Host "✅ Arquivo .env criado com sucesso!" -ForegroundColor Green
    Write-Host "ℹ️  Por favor, configure as credenciais do banco de dados no arquivo .env" -ForegroundColor Cyan
} else {
    Write-Host "ℹ️  Arquivo .env já existe. Pulando configuração..." -ForegroundColor Cyan
}

# 4. Criar banco SQLite (se não existir configuração de BD)
$envContent = Get-Content ".env" -Raw
if ($envContent -match "DB_CONNECTION=sqlite") {
    Write-Host "💾 Configurando banco SQLite..." -ForegroundColor Yellow
    if (!(Test-Path "database\database.sqlite")) {
        New-Item -ItemType File -Path "database\database.sqlite" -Force | Out-Null
    }
    Write-Host "✅ Banco SQLite configurado!" -ForegroundColor Green
}

# 5. Executar migrações
Write-Host "🗃️  Executando migrações do banco de dados..." -ForegroundColor Yellow
& php artisan migrate --no-interaction

if ($LASTEXITCODE -ne 0) {
    Write-Host "⚠️  Erro ao executar migrações. Verifique as configurações do banco de dados no .env" -ForegroundColor Yellow
    Write-Host "   Você pode executar 'php artisan migrate' manualmente após configurar o banco" -ForegroundColor Yellow
} else {
    Write-Host "✅ Migrações executadas com sucesso!" -ForegroundColor Green
    
    # 6. Executar seeders (opcional)
    $seedChoice = Read-Host "🌱 Deseja criar dados de teste? (y/n)"
    if ($seedChoice -eq "y" -or $seedChoice -eq "Y") {
        Write-Host "🌱 Executando seeders..." -ForegroundColor Yellow
        & php artisan db:seed --no-interaction
        Write-Host "✅ Dados de teste criados!" -ForegroundColor Green
        Write-Host ""
        Write-Host "👥 Usuários de teste criados:" -ForegroundColor Cyan
        Write-Host "   Admin: admin@example.com / password" -ForegroundColor Cyan
        Write-Host "   Usuário: user@example.com / password" -ForegroundColor Cyan
    }
}

# 7. Criar link simbólico para storage
Write-Host "🔗 Criando link simbólico para storage..." -ForegroundColor Yellow
& php artisan storage:link --no-interaction

# 8. Compilar assets
Write-Host "🎨 Compilando assets..." -ForegroundColor Yellow
& npm run build

if ($LASTEXITCODE -ne 0) {
    Write-Host "⚠️  Erro ao compilar assets. Execute 'npm run dev' manualmente" -ForegroundColor Yellow
} else {
    Write-Host "✅ Assets compilados com sucesso!" -ForegroundColor Green
}

Write-Host ""
Write-Host "🎉 Configuração concluída com sucesso!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Próximos passos:" -ForegroundColor Cyan
Write-Host "1. Configure as credenciais do banco de dados no arquivo .env (se não usando SQLite)" -ForegroundColor White
Write-Host "2. Execute 'php artisan serve' para iniciar o servidor" -ForegroundColor White
Write-Host "3. Execute 'npm run dev' em outro terminal para development" -ForegroundColor White
Write-Host "4. Acesse http://localhost:8000 para ver a aplicação" -ForegroundColor White
Write-Host ""
Write-Host "📖 Para mais informações, consulte o README.md" -ForegroundColor Cyan
Write-Host ""
Write-Host "🚀 Pronto para usar o CV Sender!" -ForegroundColor Green
