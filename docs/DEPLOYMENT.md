# 🚀 Guia de Deploy - BASE250

## Pré-requisitos

- **Servidor**: Hostinger, DigitalOcean, AWS, etc.
- **PHP**: 8.0 ou superior
- **MySQL**: 8.0 ou superior
- **Acesso**: SSH (recomendado) ou FTP/SFTP

---

## Deploy na Hostinger

### 1. Preparar Ambiente Local

**Testar aplicação localmente:**
```bash
cd /caminho/para/BASE250_v2.0.0
php -S localhost:8000 -t public/
```

Acesse: `http://localhost:8000`

### 2. Configurar Banco de Dados

**No painel hpanel da Hostinger:**

1. Acessar **Bancos de Dados MySQL**
2. Criar novo banco:
   - Nome: `u123456789_base250`
   - Usuário: `u123456789_base250`
   - Senha: Gerar senha forte
   - Anotar credenciais

3. Importar schema (quando disponível):
   - phpMyAdmin → Importar → `database/schema.sql`

### 3. Upload dos Arquivos

#### Opção A: Via FTP/SFTP (FileZilla)

**Estrutura no servidor:**
```
public_html/
└── BASE250/          # ou diretamente em public_html/
    ├── config/
    ├── src/
    ├── templates/
    └── public/
        └── .htaccess
```

**Passos:**
1. Conectar via FTP (hostname: ftp.seudominio.com)
2. Enviar todos os arquivos EXCETO `docs_private/`
3. Definir permissões:
   - Diretórios: `755`
   - Arquivos: `644`
   - `config/database.php`: `600` (somente leitura)

#### Opção B: Via SSH (Recomendado)

```bash
# Conectar ao servidor
ssh u123456789@seudominio.com

# Navegar para public_html
cd ~/public_html

# Clonar repositório
git clone https://github.com/engdiogoj-cyber/BASE250_v2.0.0.git BASE250

# Entrar no diretório
cd BASE250
```

### 4. Configurar Banco de Dados

**Editar `config/database.php`:**
```bash
nano config/database.php
```

**Atualizar credenciais:**
```php
private const HOST = 'localhost';
private const DB = 'u123456789_base250';
private const USER = 'u123456789_base250';
private const PASS = 'SUA_SENHA_SEGURA_AQUI';
```

### 5. Configurar Apache (.htaccess)

**Verificar `public/.htaccess`:**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /BASE250/public/
    
    # Redirecionar para HTTPS em produção
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    # Não reescrever para arquivos existentes
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    # Reescrever para index.php
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
```

**Se instalado na raiz (`public_html/`):**
```apache
RewriteBase /
```

### 6. Configurar Permissões

```bash
# Permissões gerais
chmod -R 755 ~/public_html/BASE250
find ~/public_html/BASE250 -type f -exec chmod 644 {} \;

# Proteger arquivo de configuração
chmod 600 ~/public_html/BASE250/config/database.php

# Criar diretório de logs (quando implementado)
mkdir ~/public_html/BASE250/storage/logs
chmod 775 ~/public_html/BASE250/storage/logs
```

### 7. Configurar PHP (php.ini)

**No painel Hostinger:**
1. Acessar **Gerenciador de Arquivos**
2. Editar `.htaccess` ou criar `php.ini` em `public_html/`

```ini
# Aumentar limites
memory_limit = 256M
upload_max_filesize = 20M
post_max_size = 25M
max_execution_time = 300

# Segurança
display_errors = Off
log_errors = On
error_log = /home/usuario/error_log

# Sessão
session.cookie_secure = On
session.cookie_httponly = On
session.cookie_samesite = Strict
```

### 8. Testar Aplicação

**Acessar:**
```
https://seudominio.com/BASE250/public/
```

**Ou se na raiz:**
```
https://seudominio.com/
```

**Verificar:**
- ✅ Dashboard carrega corretamente
- ✅ CSS e JavaScript funcionam
- ✅ Menu accordion abre/fecha
- ✅ Responsividade mobile

---

## Deploy em Servidor VPS (DigitalOcean, AWS, Linode)

### 1. Configurar Servidor

**Instalar LAMP Stack:**
```bash
# Atualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar Apache
sudo apt install apache2 -y

# Instalar PHP 8.0+
sudo apt install php8.1 php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl -y

# Instalar MySQL
sudo apt install mysql-server -y

# Verificar instalação
php -v
mysql --version
```

### 2. Configurar MySQL

```bash
# Configuração segura
sudo mysql_secure_installation

# Criar banco e usuário
sudo mysql -u root -p

CREATE DATABASE base250_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'base250_user'@'localhost' IDENTIFIED BY 'SENHA_FORTE_AQUI';
GRANT ALL PRIVILEGES ON base250_db.* TO 'base250_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Configurar Apache

**Criar Virtual Host:**
```bash
sudo nano /etc/apache2/sites-available/base250.conf
```

**Conteúdo:**
```apache
<VirtualHost *:80>
    ServerName seudominio.com
    ServerAdmin contato@base250.com
    
    DocumentRoot /var/www/base250/public
    
    <Directory /var/www/base250/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/base250_error.log
    CustomLog ${APACHE_LOG_DIR}/base250_access.log combined
</VirtualHost>
```

**Ativar site:**
```bash
sudo a2ensite base250
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 4. Deploy do Código

```bash
# Clonar repositório
cd /var/www
sudo git clone https://github.com/engdiogoj-cyber/BASE250_v2.0.0.git base250
cd base250

# Configurar permissões
sudo chown -R www-data:www-data /var/www/base250
sudo chmod -R 755 /var/www/base250
sudo chmod 600 /var/www/base250/config/database.php
```

### 5. Configurar SSL (Let's Encrypt)

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-apache -y

# Obter certificado
sudo certbot --apache -d seudominio.com -d www.seudominio.com

# Auto-renovação (já configurado)
sudo systemctl status certbot.timer
```

---

## Configurações Adicionais

### Cron Jobs (Tarefas Agendadas)

**No painel da Hostinger ou via SSH:**
```bash
crontab -e
```

**Adicionar:**
```cron
# Backup diário às 3h
0 3 * * * /usr/bin/php /home/usuario/public_html/BASE250/scripts/backup.php

# Limpar logs antigos (semanal)
0 2 * * 0 find /home/usuario/public_html/BASE250/storage/logs -type f -mtime +30 -delete

# Enviar notificações de pagamento (diário às 9h)
0 9 * * * /usr/bin/php /home/usuario/public_html/BASE250/scripts/notify_payments.php
```

### Backup Automático

**Script: `scripts/backup.php` (criar quando necessário)**
```php
<?php
// Backup do banco de dados
$backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
exec("mysqldump -u usuario -psenha base250_db > /backups/$backup_file");

// Compactar
exec("gzip /backups/$backup_file");

// Fazer upload para cloud (opcional)
// ... código de upload
```

### Monitoramento

**Ferramentas recomendadas:**
- **Uptime**: UptimeRobot, Pingdom
- **Errors**: Sentry, Rollbar
- **Analytics**: Google Analytics
- **Performance**: New Relic, DataDog

---

## Troubleshooting

### Erro 500 (Internal Server Error)

**Verificar logs:**
```bash
# Hostinger
tail -f ~/error_log

# VPS
sudo tail -f /var/log/apache2/error.log
```

**Causas comuns:**
- Sintaxe PHP incorreta
- Permissões de arquivo incorretas
- `.htaccess` mal configurado
- PHP modules faltando

**Soluções:**
```bash
# Verificar sintaxe PHP
php -l public/index.php

# Verificar módulos PHP
php -m | grep pdo

# Resetar permissões
chmod -R 755 .
find . -type f -exec chmod 644 {} \;
```

### Database Connection Error

**Verificar:**
1. Credenciais em `config/database.php`
2. MySQL está rodando: `sudo systemctl status mysql`
3. Firewall não está bloqueando: `sudo ufw status`
4. Usuário tem permissões: `SHOW GRANTS FOR 'base250_user'@'localhost';`

### CSS/JavaScript não carregam

**Verificar:**
1. Caminho correto em `templates/layout.php`
2. `.htaccess` com regras de rewrite
3. Permissões dos arquivos (644)
4. Console do navegador para erros

**Solução:**
```apache
# Em public/.htaccess
<FilesMatch "\.(css|js|png|jpg|jpeg|gif|svg)$">
    Header set Cache-Control "max-age=31536000, public"
</FilesMatch>
```

---

## Performance em Produção

### 1. Habilitar OPcache

**php.ini:**
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

### 2. Compressão Gzip

**Já configurado em `public/.htaccess`:**
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>
```

### 3. Cache de Assets

**Browser caching:**
```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

### 4. CDN (Opcional)

Usar Cloudflare ou outro CDN para servir assets estáticos.

---

## Rollback

**Em caso de problemas após deploy:**

```bash
# Via Git
git log --oneline  # Ver commits
git revert HEAD    # Desfazer último commit
# OU
git reset --hard COMMIT_HASH  # Voltar para commit específico

# Via Backup
cp -r /backups/base250_backup_2025-02-07 /var/www/base250
mysql -u usuario -p base250_db < /backups/db_backup_2025-02-07.sql
```

---

## Checklist Pós-Deploy

- [ ] Aplicação acessível via HTTPS
- [ ] Dashboard carrega sem erros
- [ ] CSS e JavaScript funcionam
- [ ] Menu e navegação funcionais
- [ ] Banco de dados conectando
- [ ] Logs sendo gerados corretamente
- [ ] Backup automático configurado
- [ ] Monitoramento ativo
- [ ] SSL certificado válido
- [ ] Performance otimizada

---

## Suporte

Para problemas de deploy:
- Email: contato@base250.com
- Issues: https://github.com/engdiogoj-cyber/BASE250_v2.0.0/issues
- Documentação: https://github.com/engdiogoj-cyber/BASE250_v2.0.0/tree/main/docs
