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
