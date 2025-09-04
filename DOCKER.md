# CV Sender - Guia Rápido Docker

## 🐳 Executar com Docker

### Pré-requisitos
- Docker
- Docker Compose

### Comandos Rápidos

#### 1. Primeira Execução (Setup Completo)
```bash
# Copiar arquivo de ambiente
cp .env.example .env

# Editar configurações do banco no .env:
# DB_CONNECTION=pgsql
# DB_HOST=database
# DB_PORT=5432
# DB_DATABASE=cv_sender
# DB_USERNAME=cv_sender
# DB_PASSWORD=secret

# REDIS_HOST=redis

# Construir e iniciar containers
docker-compose up --build -d

# Gerar chave da aplicação
docker-compose exec app php artisan key:generate

# Executar migrações
docker-compose exec app php artisan migrate

# Executar seeders (opcional)
docker-compose exec app php artisan db:seed

# Criar link para storage
docker-compose exec app php artisan storage:link
```

#### 2. Uso Diário
```bash
# Iniciar aplicação
docker-compose up -d

# Parar aplicação
docker-compose down

# Ver logs
docker-compose logs -f

# Acessar container da aplicação
docker-compose exec app bash
```

#### 3. Desenvolvimento
```bash
# Iniciar com Node.js para desenvolvimento
docker-compose --profile development up -d

# Reinstalar dependências
docker-compose exec app composer install
docker-compose exec node npm install

# Executar testes
docker-compose exec app php artisan test
```

### Acesso
- **Aplicação**: http://localhost:8000
- **PostgreSQL**: localhost:5432
- **Redis**: localhost:6379

### Troubleshooting
```bash
# Reconstruir containers
docker-compose down
docker-compose up --build -d

# Limpar volumes
docker-compose down -v
docker-compose up --build -d
```
