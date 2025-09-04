# CV Sender

## 📋 Sobre o Projeto

CV Sender é uma plataforma moderna para gerenciamento e envio de currículos, desenvolvida para otimizar processos de recrutamento. A aplicação permite que candidatos submetam seus currículos através de formulários personalizados, enquanto administradores podem gerenciar e processar essas submissões de forma eficiente.

## 🛠️ Tecnologias Utilizadas

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Vue.js 3 + TypeScript
- **Bridge**: Inertia.js
- **Styling**: Tailwind CSS + shadcn-vue
- **Database**: PostgreSQL/MySQL/SQLite
- **Build Tool**: Vite
- **Testing**: PHPUnit, Pest

## ✨ Funcionalidades

### Para Candidatos

- ✅ Cadastro e login de usuários
- ✅ Submissão de formulários de candidatura
- ✅ Upload de arquivos CV (PDF, DOC, DOCX)
- ✅ Visualização e edição de formulários próprios
- ✅ Sistema de notificações
- ✅ Interface responsiva e intuitiva

### Para Administradores

- ✅ Dashboard administrativo com métricas
- ✅ Gerenciamento completo de formulários
- ✅ Sistema de filtros avançados
- ✅ Exportação de dados para CSV
- ✅ Download de arquivos CV
- ✅ Controle de acesso baseado em roles

### Campos do Formulário

- **Informações Pessoais**: Nome, email, telefone
- **Profissionais**: Cargo pretendido, formação acadêmica (select)
- **Adicionais**: Observações, upload de CV
- **Validações**: Validação robusta de dados e arquivos

## 📦 Pré-requisitos

Antes de começar, certifique-se de ter instalado:

- **PHP** >= 8.2
- **Composer** (gerenciador de dependências PHP)
- **Node.js** >= 18.x
- **NPM** ou **Yarn**
- **PostgreSQL**, **MySQL** ou **SQLite**
- **Git**

### Extensões PHP Necessárias

```bash
php-curl
php-dom
php-fileinfo
php-filter
php-hash
php-mbstring
php-openssl
php-pcre
php-pdo
php-session
php-tokenizer
php-xml
```

## 🚀 Instalação e Configuração

### 1. Clone o Repositório

```bash
git clone https://github.com/leuhx/cv-sender.git
cd cv-sender
```

### 2. Instale as Dependências PHP

```bash
composer install
```

### 3. Instale as Dependências JavaScript

```bash
npm install
# ou
yarn install
```

### 4. Configuração do Ambiente

```bash
# Copie o arquivo de exemplo de configuração
cp .env.example .env

# Gere a chave de aplicação
php artisan key:generate
```

### 5. Configure o Banco de Dados

Edite o arquivo `.env` com as credenciais do seu banco:

```env
# Configurações da aplicação
APP_NAME="CV Sender"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Configurações do banco de dados
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cv_sender
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

# Para SQLite (alternativa simples)
# DB_CONNECTION=sqlite
# DB_DATABASE=database/database.sqlite
```

### 6. Execute as Migrações

```bash
# Execute as migrações para criar as tabelas
php artisan migrate

# (Opcional) Execute os seeders para dados de teste
php artisan db:seed
```

### 7. Configure o Storage

```bash
# Crie o link simbólico para o storage público
php artisan storage:link
```

### 8. Compile os Assets

```bash
# Para desenvolvimento
npm run dev

# Para produção
npm run build
```

## 🏃‍♂️ Executando a Aplicação

### Ambiente de Desenvolvimento

1. **Inicie o servidor Laravel:**

```bash
php artisan serve
```

A aplicação estará disponível em: `http://localhost:8000`

2. **Inicie o Vite (em outro terminal):**

```bash
npm run dev
```

### Ambiente de Produção

1. **Compile os assets:**

```bash
npm run build
```

2. **Configure o servidor web** (Nginx/Apache) para servir a aplicação
3. **Configure as variáveis de ambiente** para produção no `.env`
4. **Execute as otimizações:**

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 👥 Usuários de Teste

Após executar `php artisan db:seed`, você terá acesso aos seguintes usuários:

### Administrador

- **Email**: admin@example.com
- **Senha**: password
- **Acesso**: Dashboard administrativo completo

### Candidato

- **Email**: user@example.com
- **Senha**: password
- **Acesso**: Formulários de candidatura

## 🧪 Executando os Testes

```bash
# Execute todos os testes
php artisan test

# Execute testes específicos
php artisan test --filter=FormControllerTest

# Execute testes com cobertura
php artisan test --coverage
```

## 📁 Estrutura do Projeto

```
cv-sender/
├── app/
│   ├── Http/Controllers/         # Controllers da aplicação
│   ├── Models/                   # Models Eloquent
│   └── ...
├── database/
│   ├── factories/                # Factories para testes
│   ├── migrations/               # Migrações do banco
│   └── seeders/                  # Seeders de dados
├── resources/
│   ├── js/                       # Código Vue.js/TypeScript
│   │   ├── components/           # Componentes Vue
│   │   ├── pages/                # Páginas da aplicação
│   │   ├── layouts/              # Layouts base
│   │   └── types/                # Definições TypeScript
│   └── views/                    # Templates Blade
├── routes/                       # Definição de rotas
├── storage/                      # Storage de arquivos
├── tests/                        # Testes automatizados
└── public/                       # Arquivos públicos
```

## 🔧 Comandos Úteis

### Desenvolvimento

```bash
# Instalar nova dependência PHP
composer require package-name

# Instalar nova dependência JavaScript
npm install package-name

# Criar nova migração
php artisan make:migration create_table_name

# Criar novo controller
php artisan make:controller ControllerName

# Criar novo model
php artisan make:model ModelName
```

### Manutenção

```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recriar banco de dados
php artisan migrate:fresh --seed
```

## 🐛 Solução de Problemas

### Problemas Comuns

1. **Erro de permissão no storage:**

```bash
chmod -R 775 storage bootstrap/cache
```

2. **Erro de dependências JavaScript:**

```bash
rm -rf node_modules package-lock.json
npm install
```

3. **Erro de migração:**

```bash
php artisan migrate:fresh --seed
```

4. **Assets não carregam:**

```bash
npm run dev
# Em outro terminal:
php artisan serve
```

### Logs de Debug

- **Laravel**: `storage/logs/laravel.log`
- **Navegador**: Console do desenvolvedor (F12)

## 📝 Contribuindo

1. Faça fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📧 Suporte

Para suporte ou dúvidas:

- **Issues**: [GitHub Issues](https://github.com/leuhx/cv-sender/issues)
- **Documentação Laravel**: [https://laravel.com/docs](https://laravel.com/docs)
- **Documentação Vue**: [https://vuejs.org/guide/](https://vuejs.org/guide/)

## 📄 Licença

Este projeto está licenciado sob a Licença MIT. Veja o arquivo `LICENSE` para detalhes.

---

Desenvolvido com ❤️ para otimizar processos de recrutamento
