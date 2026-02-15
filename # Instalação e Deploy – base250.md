# Instalação e Deploy – base250.com

Este documento descreve a estrutura que será instalada no servidor do domínio **base250.com**, bem como o processo completo de deploy.  
Nenhuma credencial ou dado sensível é incluído aqui.  
Todas as informações privadas devem ser configuradas via variáveis de ambiente ou GitHub Secrets.

---

# 1. Estrutura do Projeto

A instalação seguirá a seguinte organização:



# 1. Estrutura do Projeto

A instalação seguirá a seguinte organização:

/public_html
/assets
/css
/js
/img
/src
/components
/controllers
/models
/views
/database
schema.sql
/migrations
/seeds
/vendor
index.php
.htaccess

Código

### Descrição dos diretórios

- **public_html/**  
  Diretório raiz público do site.

- **assets/**  
  Arquivos estáticos (CSS, JS, imagens).

- **src/**  
  Código-fonte da aplicação:
  - components → módulos reutilizáveis  
  - controllers → lógica de controle  
  - models → comunicação com banco  
  - views → templates  

- **database/**  
  Estrutura do banco de dados (sem credenciais).

- **vendor/**  
  Dependências instaladas via Composer.

- **index.php**  
  Ponto de entrada da aplicação.

- **.htaccess**  
  Regras de URL, segurança e roteamento.

---

# 2. Banco de Dados (estrutura sem credenciais)

/database
schema.sql
/migrations
/seeds

Código

- **schema.sql** → estrutura inicial  
- **migrations/** → atualizações incrementais  
- **seeds/** → dados iniciais  

---

# 3. Serviço de E-mail

- Conta institucional: `contato@base250.com`
- Integração SMTP configurada via variáveis de ambiente
- Formulários do site usam SMTP

Nenhuma senha é armazenada no repositório.

---

# 4. Variáveis de Ambiente

O projeto utiliza um arquivo `.env` (não incluído no GitHub):

APP_ENV=
APP_URL=

DB_HOST=
DB_NAME=
DB_USER=
DB_PASS=

SMTP_HOST=
SMTP_PORT=
SMTP_USER=
SMTP_PASS=

Código

No GitHub, usar **GitHub Secrets**.

---

# 5. Requisitos do Servidor

- PHP (versão conforme o projeto)
- MySQL
- Apache ou Nginx
- Suporte a `.htaccess`
- Cron jobs (se necessário)
- Composer (opcional)

---

# 6. Processo Completo de Deploy

## 6.1 Preparação do Servidor

1. Acessar o painel da hospedagem  
2. Criar o diretório principal (`public_html`)  
3. Criar o banco de dados  
4. Criar o usuário do banco e associar permissões  
5. Criar conta de e-mail institucional (se necessário)

---

## 6.2 Envio dos Arquivos

### Opção A — Upload via painel  
- Enviar o ZIP do projeto  
- Extrair dentro de `public_html`

### Opção B — FTP  
- Conectar ao servidor  
- Enviar todos os arquivos para `public_html`

---

## 6.3 Instalação das Dependências

Se o projeto usa Composer:

composer install

Código

Isso criará a pasta `/vendor`.

---

## 6.4 Configuração do Ambiente

Criar o arquivo `.env` no servidor:

cp .env.example .env

Código

Preencher com:

- dados do banco  
- dados do SMTP  
- URL do site  

**Nunca enviar `.env` para o GitHub.**

---

## 6.5 Configuração do Banco de Dados

1. Importar `database/schema.sql`  
2. Executar migrations (se houver)  
3. Executar seeds (opcional)

---

## 6.6 Ajuste de Permissões

Garantir que o servidor possa escrever em:

/public_html
/storage (se existir)

Código
