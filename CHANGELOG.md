# 📋 Changelog - BASE250

Todas as mudanças notáveis do projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

---

## [Não Lançado]

### Planejado
- Geração automática de contratos em PDF
- Sistema de notificações por e-mail
- Relatórios financeiros com gráficos
- Integração com Gov.br
- Área do inquilino (portal próprio)

---

## [2.0.0] - 2025-02-09

### 🎉 Modernização Completa do Sistema de Apartamentos

Esta é uma atualização major que moderniza completamente o sistema de listagem de apartamentos, integrando banco de dados, área administrativa e site público dinâmico.

### ✨ Adicionado - Backend & API

- **Sistema de Banco de Dados MySQL**
  - Schema completo com 14 tabelas
  - Suporte para apartamentos, usuários, contratos, pagamentos, etc.
  - Campos específicos para listagem pública: tipo, metragem, quartos, banheiros, preco, descricao, features, galeria_fotos
  - Seed com 14 apartamentos completos (101-109, 201-205) incluindo todas fotos e características

- **Backend PHP Moderno**
  - Configuração de banco de dados com PDO
  - Sistema de autenticação seguro com bcrypt
  - Proteção contra SQL Injection (prepared statements)
  - Proteção contra XSS (sanitização de inputs)
  - Gestão de sessões segura

- **API RESTful**
  - `GET /backend/api/apartamentos.php` - Lista todos apartamentos com dados completos
  - `POST /backend/api/update_apartamento.php` - Atualiza informações de apartamento (autenticado)
  - `POST /backend/api/update_status.php` - Alterna status disponível/alugado (autenticado)
  - Respostas padronizadas em JSON
  - Validação de dados em todos endpoints

### ✨ Adicionado - Área Administrativa

- **Sistema de Login**
  - Página de login moderna e responsiva
  - Credenciais padrão: admin@base250.com / admin123
  - Mensagens de erro amigáveis
  - Proteção de rotas autenticadas

- **Dashboard Administrativo**
  - Estatísticas em tempo real (total, disponíveis, alugados)
  - Cards modernos com fotos dos apartamentos
  - Badges de status coloridos (verde/vermelho)
  - Toggle rápido de status com confirmação
  - Link direto para edição
  - Design responsivo e animado

- **Tela de Edição de Apartamentos**
  - Formulário completo para todas informações
  - Edição de informações básicas (número, tipo, metragem, quartos, banheiros, preço)
  - Gerenciamento dinâmico de características/features
  - Gerenciamento dinâmico de galeria de fotos (URLs)
  - Preview de fotos carregadas
  - Validação de dados no frontend e backend
  - Mensagens de sucesso/erro

### ✨ Adicionado - Site Público

- **Página Pública Modernizada (index.php)**
  - Carregamento dinâmico de apartamentos do banco de dados
  - Mantém 100% da funcionalidade de galeria original:
    - Navegação prev/next entre fotos
    - Indicadores visuais (bolinhas)
    - Contador de fotos (ex: 1/5)
    - Navegação por clique nos indicadores
  - Apartamentos alugados aparecem mas sem preço e botão
  - Visual "desabilitado" para alugados (opacidade reduzida)
  - Design moderno com cards e animações suaves
  - Totalmente responsivo (mobile, tablet, desktop)
  - Gradiente de fundo mantido
  - Seção "Como Alugar" preservada
  - Footer com contatos

### ✨ Adicionado - Documentação

- **SETUP.md** - Guia completo de instalação
  - Pré-requisitos detalhados
  - Passo a passo de configuração do banco
  - Configuração do servidor web (Apache/Nginx)
  - Instruções de acesso
  - Troubleshooting

- **API.md** - Documentação completa da API
  - Descrição de todos endpoints
  - Exemplos de requisições e respostas
  - Códigos de erro e suas causas
  - Exemplos em JavaScript, PHP, cURL
  - Guia de autenticação

- **ADMIN_GUIDE.md** - Manual do administrador
  - Fluxo de trabalho típico
  - Como alterar status de apartamentos
  - Como editar informações
  - Como gerenciar fotos e características
  - Boas práticas de segurança
  - Dicas úteis

- **verify-install.sh** - Script de verificação
  - Verifica versão do PHP
  - Verifica extensões necessárias
  - Verifica MySQL
  - Verifica estrutura de arquivos
  - Lista próximos passos

- **README.md atualizado**
  - Visão geral das funcionalidades
  - Instalação rápida
  - Links para documentação detalhada
  - Informações de segurança

### 🔧 Alterado

- **database/schema.sql**
  - Adicionado campos: tipo, metragem, quartos, banheiros, preco, descricao, features, galeria_fotos
  - Modificado enum de status para incluir 'alugado'
  - Adicionado índice para tipo

- **database/seed.sql**
  - Substituído apartamentos genéricos por 14 apartamentos reais
  - Incluído todas URLs de fotos do site original
  - Incluído todas características de cada apartamento
  - Incluído preços reais

- **.gitignore**
  - Adicionado index.html.backup para não versionar backup

### 📚 Estrutura de Arquivos Adicionada

```
backend/
├── config/
│   ├── database.php              # Novo
│   └── .env.example.php          # Novo
├── api/
│   ├── apartamentos.php          # Novo
│   ├── update_apartamento.php    # Novo
│   └── update_status.php         # Novo
├── admin/
│   ├── login.php                 # Novo
│   ├── logout.php                # Novo
│   ├── index.php                 # Novo
│   └── edit.php                  # Novo
├── includes/
│   └── auth.php                  # Novo
├── SETUP.md                      # Novo
├── API.md                        # Novo
└── ADMIN_GUIDE.md                # Novo

frontend/site_publico/
├── index.php                     # Novo (dinâmico)
└── index.html.backup             # Novo (backup do original)

verify-install.sh                 # Novo
```

### 🔒 Segurança

- Implementado prepared statements em todas queries
- Sanitização de todos inputs do usuário
- Senhas armazenadas com bcrypt
- Configurações de sessão seguras (httponly, secure em produção)
- Validação de dados no backend
- Proteção contra CSRF (recomendado adicionar tokens para produção)

### 🎨 Design

- Paleta de cores moderna: `--azul: #16697A`, `--cinza-bg: #f4f6f8`, `--verde: #16a34a`, `--vermelho: #dc2626`
- Cards com sombras suaves e animações
- Grid responsivo com auto-fit
- Badges coloridos para status
- Botões com gradientes e efeitos hover
- Formulários limpos e bem espaçados

### 📊 Dados

Total de 14 apartamentos migrados:
- Studio: 101, 102, 103, 109, 202, 203, 204
- Loft: 104, 105, 106
- Apartamento: 107, 108, 201, 205

Todos com:
- Fotos completas (3-7 por apartamento)
- Características detalhadas
- Preços reais
- Metragem e especificações

### ⚠️ Breaking Changes

- Site público agora é `index.php` em vez de `index.html`
- Requer PHP 7.4+ e MySQL 5.7+
- Requer configuração de banco de dados
- URL do admin mudou para `/backend/admin/`

### 📝 Notas de Migração

Para migrar de versão anterior:
1. Execute `database/schema.sql` no banco
2. Execute `database/seed.sql` para popular dados
3. Configure `backend/config/database.php`
4. Acesse `/backend/admin/login.php` com admin@base250.com / admin123
5. Altere a senha padrão imediatamente

---

## [1.0.0] - 2025-02-05

### ✨ Adicionado
- **Sistema de Login**
  - Autenticação com email e senha
  - Controle de sessão com timeout
  - Logout seguro

- **Dashboard Principal**
  - Cards de estatísticas (disponíveis, ocupados, manutenção)
  - Lista de todos os apartamentos
  - Botões de ação rápida (Novo Contrato, Inquilinos, WhatsApp)
  - Design responsivo para mobile

- **Gestão de Apartamentos**
  - Visualização por status (disponível, ocupado, manutenção)
  - Informações do inquilino atual
  - Valor do aluguel
  - Link direto para WhatsApp

- **PWA (Progressive Web App)**
  - Manifest.json configurado
  - Service Worker para cache
  - Instalável no celular

- **Banco de Dados**
  - Estrutura completa das tabelas
  - Relacionamentos entre entidades
  - Dados de exemplo

### 🔧 Técnico
- PHP 8.x com PDO para banco de dados
- MySQL/MariaDB como SGBD
- CSS responsivo (mobile-first)
- Hospedagem na Hostinger

---

## [0.1.0] - 2025-01-XX

### 🎨 Adicionado
- Protótipo do Design System
- Layout base com sidebar e header
- Componentes visuais (cards, badges, botões, tabelas)
- Cores e tipografia definidas

---

## Tipos de Mudanças

- `✨ Adicionado` - novas funcionalidades
- `🔄 Alterado` - mudanças em funcionalidades existentes
- `🗑️ Removido` - funcionalidades removidas
- `🐛 Corrigido` - correção de bugs
- `🔒 Segurança` - correções de vulnerabilidades
- `🔧 Técnico` - mudanças internas/infraestrutura
