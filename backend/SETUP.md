# BASE250 - Sistema de Gestão de Apartamentos

Sistema completo para gerenciamento de apartamentos com área administrativa e site público dinâmico.

## 🏗️ Arquitetura

```
BASE250_v2.0.0/
├── backend/
│   ├── config/
│   │   └── database.php         # Configuração do banco de dados
│   ├── api/
│   │   ├── apartamentos.php     # GET: Lista apartamentos
│   │   ├── update_apartamento.php # POST: Atualiza apartamento
│   │   └── update_status.php    # POST: Altera status
│   ├── admin/
│   │   ├── login.php            # Página de login
│   │   ├── logout.php           # Logout
│   │   ├── index.php            # Dashboard
│   │   └── edit.php             # Editar apartamento
│   └── includes/
│       └── auth.php             # Funções de autenticação
├── frontend/
│   └── site_publico/
│       └── index.php            # Página pública dinâmica
└── database/
    ├── schema.sql               # Estrutura do banco
    └── seed.sql                 # Dados iniciais (14 apartamentos)
```

## 🚀 Instalação

### Pré-requisitos

- PHP 7.4+
- MySQL 5.7+ ou MariaDB 10.3+
- Servidor web (Apache/Nginx)

### Passo 1: Configurar Banco de Dados

1. Crie um banco de dados MySQL:
```sql
CREATE DATABASE base250 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Execute o schema:
```bash
mysql -u seu_usuario -p base250 < database/schema.sql
```

3. Execute o seed (dados iniciais):
```bash
mysql -u seu_usuario -p base250 < database/seed.sql
```

### Passo 2: Configurar Conexão

Configure as variáveis de ambiente ou edite diretamente em `backend/config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'base250');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
```

### Passo 3: Configurar Servidor Web

#### Apache

Certifique-se de que o módulo `mod_rewrite` está ativo e configure o DocumentRoot para o diretório raiz do projeto.

#### Nginx

Exemplo de configuração:

```nginx
server {
    listen 80;
    server_name base250.local;
    root /path/to/BASE250_v2.0.0;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Passo 4: Acessar o Sistema

- **Site Público**: `http://seu-dominio/frontend/site_publico/index.php`
- **Admin**: `http://seu-dominio/backend/admin/login.php`

**Credenciais padrão:**
- Email: `admin@base250.com`
- Senha: `admin123`

⚠️ **IMPORTANTE**: Altere a senha após o primeiro acesso!

## 📚 API Endpoints

### GET /backend/api/apartamentos.php

Lista todos os apartamentos.

**Resposta:**
```json
{
  "success": true,
  "message": "Apartamentos carregados com sucesso",
  "data": [
    {
      "id": 1,
      "numero": "101",
      "tipo": "Studio",
      "metragem": 18.00,
      "quartos": 1,
      "banheiros": 1,
      "preco": 1600.00,
      "status": "disponivel",
      "descricao": "Studio completo...",
      "features": ["Geladeira", "Ar condicionado", ...],
      "galeria_fotos": ["https://...", ...]
    }
  ]
}
```

### POST /backend/api/update_status.php

Altera o status de um apartamento (requer autenticação).

**Request:**
```json
{
  "id": 1,
  "status": "alugado"
}
```

**Resposta:**
```json
{
  "success": true,
  "message": "Status atualizado com sucesso",
  "data": {
    "id": 1,
    "numero": "101",
    "status": "alugado"
  }
}
```

### POST /backend/api/update_apartamento.php

Atualiza informações completas de um apartamento (requer autenticação).

**Request:**
```json
{
  "id": 1,
  "numero": "101",
  "tipo": "Studio",
  "metragem": 18,
  "quartos": 1,
  "banheiros": 1,
  "preco": 1650,
  "descricao": "Studio renovado",
  "features": ["Geladeira", "Fogão", "Ar condicionado"],
  "galeria_fotos": ["https://...", "https://..."]
}
```

## 🔐 Segurança

### Implementado

- ✅ Prepared statements (proteção contra SQL Injection)
- ✅ Sanitização de inputs (proteção contra XSS)
- ✅ Senhas criptografadas com bcrypt
- ✅ Sessões com configurações seguras
- ✅ Validação de dados no backend

### Recomendações para Produção

1. **HTTPS**: Use sempre HTTPS em produção
2. **Senhas**: Altere as credenciais padrão imediatamente
3. **Permissões**: Configure permissões corretas de arquivos (644 para arquivos, 755 para diretórios)
4. **Backups**: Configure backups automáticos do banco de dados
5. **Error Reporting**: Desative `display_errors` em produção

## 🎨 Funcionalidades

### Site Público

- ✅ Listagem dinâmica de apartamentos do banco de dados
- ✅ Galeria de fotos com navegação (prev/next)
- ✅ Indicadores visuais de galeria
- ✅ Contador de fotos
- ✅ Apartamentos alugados aparecem mas sem preço/botão
- ✅ Design moderno e responsivo
- ✅ Cards com animações suaves

### Área Administrativa

- ✅ Sistema de login seguro
- ✅ Dashboard com estatísticas
- ✅ Listagem de todos apartamentos
- ✅ Edição completa de apartamentos
- ✅ Toggle rápido de status (disponível/alugado)
- ✅ Gerenciamento de fotos da galeria
- ✅ Gerenciamento de características/features

## 📱 Responsividade

O sistema é totalmente responsivo e funciona perfeitamente em:
- 📱 Smartphones (320px+)
- 📱 Tablets (768px+)
- 💻 Desktops (1024px+)
- 🖥️ Telas grandes (1440px+)

## 🐛 Troubleshooting

### Erro de conexão com banco de dados

Verifique:
1. Credenciais em `backend/config/database.php`
2. Se o MySQL está rodando
3. Se o banco de dados foi criado
4. Se as tabelas foram criadas (schema.sql)

### Apartamentos não aparecem

1. Verifique se executou o `seed.sql`
2. Verifique os logs de erro do PHP
3. Teste o endpoint da API diretamente: `/backend/api/apartamentos.php`

### Não consegue fazer login

1. Verifique se executou o `seed.sql` (cria usuário admin)
2. Credenciais: `admin@base250.com` / `admin123`
3. Verifique se as sessões PHP estão funcionando

## 📄 Licença

Este projeto é propriedade de BASE250. Todos os direitos reservados.

## 👨‍💻 Suporte

Para suporte, entre em contato:
- Email: floripamoso@gmail.com
- WhatsApp: (48) 99935-2627
