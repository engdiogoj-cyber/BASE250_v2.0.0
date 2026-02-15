# BASE250 – Sistema de Gestão de Apartamentos

Sistema completo para gerenciamento de apartamentos com área administrativa e site público dinâmico.

> **Modernização v2.0** - Sistema atualizado com backend PHP, banco de dados MySQL e área administrativa completa.

> Domínio: `base250.com`  
> E-mail oficial: `contato@base250.br`  
> Hosting: Hostinger (hpanel) ou servidor PHP  
> Ferramentas: ChatGPT, Claude, GitHub Copilot

## 🎯 Principais Funcionalidades

### Site Público
- ✅ Listagem dinâmica de apartamentos
- ✅ Galeria de fotos interativa (navegação, indicadores, contador)
- ✅ Design moderno e responsivo
- ✅ Apartamentos alugados aparecem mas sem preço/botão
- ✅ Integração com banco de dados MySQL

### Área Administrativa
- ✅ Sistema de login seguro
- ✅ Dashboard com estatísticas
- ✅ CRUD completo de apartamentos
- ✅ Toggle de status (disponível/alugado)
- ✅ Gerenciamento de galeria de fotos
- ✅ Gerenciamento de características

## 📦 Tecnologias

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.4+ com PDO
- **Banco de Dados**: MySQL 5.7+ / MariaDB 10.3+
- **Autenticação**: Session-based com bcrypt
- **API**: RESTful JSON

## 🚀 Instalação Rápida

### 1. Verificar Requisitos

```bash
./verify-install.sh
```

### 2. Configurar Banco de Dados

```bash
# Criar banco
mysql -u root -p -e "CREATE DATABASE base250 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Criar tabelas
mysql -u root -p base250 < database/schema.sql

# Popular dados iniciais (14 apartamentos)
mysql -u root -p base250 < database/seed.sql
```

### 3. Configurar Conexão

Edite `backend/config/database.php` com suas credenciais:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'base250');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
```

### 4. Acessar Sistema

- **Site Público**: `http://localhost/frontend/site_publico/index.php`
- **Admin**: `http://localhost/backend/admin/login.php`

**Credenciais padrão:**
- Email: `admin@base250.com`
- Senha: `admin123`

⚠️ **Altere a senha após primeiro acesso!**

## 📚 Documentação Completa

- [Setup e Instalação](backend/SETUP.md) - Guia detalhado de instalação
- [Documentação da API](backend/API.md) - Endpoints e exemplos de uso

## Regras de privacidade (importante)
- Tudo que é sensível fica em:
  - `docs_private/` (não subir para GitHub)
  - `.env` (não subir para GitHub)
  - `backend/config/database.php` (configurar localmente)
- No GitHub ficarão apenas arquivos com placeholders.

## Estrutura do Projeto

```
BASE250_v2.0.0/
├─ README.md
├─ LICENSE
├─ CONTRIBUTING.md
├─ .gitignore
├─ verify-install.sh          # Script de verificação
├─ docs/
├─ docs_private/              # (IGNORADO - dados sensíveis)
├─ assets/images/
├─ frontend/
│  ├─ site_publico/
│  │  ├─ index.php           # Página pública modernizada
│  │  └─ index.html.backup   # Backup do HTML original
│  └─ painel/
├─ backend/
│  ├─ SETUP.md               # Guia de instalação
│  ├─ API.md                 # Documentação da API
│  ├─ config/
│  │  ├─ database.php        # Configuração do banco
│  │  └─ .env.example.php    # Exemplo de configuração
│  ├─ api/
│  │  ├─ apartamentos.php        # GET: Listar apartamentos
│  │  ├─ update_apartamento.php  # POST: Atualizar apartamento
│  │  └─ update_status.php       # POST: Alterar status
│  ├─ admin/
│  │  ├─ login.php           # Página de login
│  │  ├─ logout.php          # Logout
│  │  ├─ index.php           # Dashboard
│  │  └─ edit.php            # Editar apartamento
│  └─ includes/
│     └─ auth.php            # Funções de autenticação
├─ database/
│  ├─ schema.sql             # Estrutura do banco (14 tabelas)
│  └─ seed.sql               # Dados iniciais (14 apartamentos)
└─ scripts/
```

## 🔒 Segurança

- ✅ Prepared statements (proteção contra SQL Injection)
- ✅ Sanitização de inputs (proteção contra XSS)
- ✅ Senhas criptografadas com bcrypt
- ✅ Sessões configuradas com segurança
- ✅ Validação de dados no backend

**Recomendações para Produção:**
1. Use HTTPS
2. Altere senhas padrão
3. Configure permissões de arquivo adequadas
4. Desative `display_errors`
5. Configure backups automáticos

## 🐛 Suporte

Para dúvidas ou problemas:
- Email: floripamoso@gmail.com
- WhatsApp: (48) 99935-2627

## 📄 Licença

Este projeto é propriedade de BASE250. Todos os direitos reservados.
