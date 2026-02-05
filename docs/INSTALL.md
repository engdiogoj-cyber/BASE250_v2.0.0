# 📦 Guia de Instalação - BASE250

## Requisitos

- **PHP:** 8.0 ou superior
- **MySQL:** 5.7+ ou MariaDB 10.3+
- **Extensões PHP:** PDO, pdo_mysql, mbstring, json
- **Servidor:** Apache ou Nginx

---

## Instalação na Hostinger

### 1. Preparar Banco de Dados

1. Acesse o **hPanel** da Hostinger
2. Vá em **Bancos de Dados → MySQL**
3. Crie um novo banco (ex: `u483505869_base250`)
4. Crie um usuário e atribua ao banco
5. Anote as credenciais

### 2. Importar Schema

1. Acesse o **phpMyAdmin** (hPanel → Bancos de Dados)
2. Selecione seu banco de dados
3. Clique em **Importar**
4. Selecione o arquivo `database/schema.sql`
5. Clique em **Executar**
6. Repita para `database/seed.sql` (dados iniciais)

### 3. Upload dos Arquivos

**Opção A: Gerenciador de Arquivos**
1. hPanel → Gerenciador de Arquivos
2. Navegue até `public_html/`
3. Faça upload de todos os arquivos da pasta `public/`
4. Crie a pasta `includes/` e faça upload do `config.php`

**Opção B: FTP**
```
Host: ftp.base250.com
Usuário: (fornecido pela Hostinger)
Porta: 21
```

### 4. Configurar config.php

1. Renomeie `config.example.php` para `config.php`
2. Edite com suas credenciais:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'u483505869_base250');
define('DB_USER', 'u483505869_diogo');
define('DB_PASS', 'SUA_SENHA_REAL');
define('SITE_URL', 'https://base250.com');
```

### 5. Configurar SSL

1. hPanel → SSL/TLS
2. Ative o SSL gratuito (Let's Encrypt)
3. Ative "Forçar HTTPS"

### 6. Configurar Permissões

```
Pastas: 755
Arquivos: 644
uploads/: 755 (com escrita)
```

### 7. Testar

1. Acesse `https://base250.com`
2. Login: `admin@base250.com`
3. Senha: `Admin@250`
4. **ALTERE A SENHA IMEDIATAMENTE!**

---

## Estrutura de Pastas na Hostinger

```
public_html/
├── index.php
├── dashboard.php
├── logout.php
├── manifest.json
├── sw.js
├── .htaccess
│
├── includes/
│   └── config.php      ← Suas credenciais (NÃO compartilhar!)
│
├── assets/
│   ├── css/
│   ├── js/
│   └── icons/
│
└── uploads/
    └── .gitkeep
```

---

## Arquivo .htaccess

Crie na raiz do `public_html/`:

```apache
# Forçar HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Proteção contra acesso direto a arquivos PHP incluídos
<FilesMatch "^(config|functions)\.php$">
    Order deny,allow
    Deny from all
</FilesMatch>

# Proteção da pasta includes
<Directory "includes">
    Order deny,allow
    Deny from all
</Directory>

# Cache de arquivos estáticos
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType text/css "access plus 1 week"
    ExpiresByType application/javascript "access plus 1 week"
</IfModule>

# Compressão GZIP
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/css application/json application/javascript
</IfModule>
```

---

## Solução de Problemas

### Erro de conexão com banco
- Verifique as credenciais no `config.php`
- Confirme que o banco foi criado
- Verifique se o usuário tem permissões

### Página em branco
- Ative `DEBUG_MODE` no `config.php`
- Verifique os logs de erro do PHP
- hPanel → Configurações Avançadas → Logs

### 500 Internal Server Error
- Verifique o `.htaccess`
- Confirme permissões dos arquivos
- Verifique a versão do PHP (8.0+)

### PWA não instala
- Confirme que o SSL está ativo
- Verifique o `manifest.json`
- Teste no Chrome DevTools → Application

---

## Backup

### Banco de Dados
hPanel → Backups → Banco de dados MySQL

### Arquivos
hPanel → Backups → Arquivos do site

### Automático
Configure backups semanais no hPanel

---

## Atualizações

Para atualizar o sistema:

1. Faça backup do banco e arquivos
2. Baixe a nova versão do GitHub
3. Substitua os arquivos (exceto `config.php`)
4. Execute migrações SQL se houver
5. Teste todas as funcionalidades

---

## Suporte

Em caso de dúvidas ou problemas:
- **E-mail:** contato@base250.com
- **Documentação:** Este repositório

---

*Última atualização: Fevereiro 2025*
