# 🏢 BASE250 - Sistema de Gestão Imobiliária

Sistema completo para gestão de imóveis, inquilinos, contratos e pagamentos.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-Proprietary-red)

---

## 📋 Sobre

**BASE250** é uma plataforma de gestão imobiliária desenvolvida para o **Edifício Alto do Itacorubi** em Florianópolis/SC. Interface otimizada para facilidade de uso, incluindo suporte a dispositivos móveis (PWA).

### Funcionalidades
- ✅ Login seguro com controle de sessão
- ✅ Dashboard com visão geral dos apartamentos
- ✅ Cadastro de inquilinos e contratos
- ✅ Controle de pagamentos
- ✅ Integração com WhatsApp
- ✅ PWA (instalável no celular)
- 🔲 Geração de contratos PDF
- 🔲 Notificações automáticas
- 🔲 Relatórios financeiros

---

## 🚀 Instalação Rápida

### Requisitos
- PHP 8.0+
- MySQL 5.7+ ou MariaDB 10.3+
- Servidor web (Apache/Nginx)

### 1. Clone o repositório
```bash
git clone https://github.com/seu-usuario/base250.git
cd base250
```

### 2. Configure o banco de dados
```bash
# Importe o schema
mysql -u seu_usuario -p seu_banco < database/schema.sql

# Importe os dados iniciais (opcional)
mysql -u seu_usuario -p seu_banco < database/seed.sql
```

### 3. Configure as credenciais
```bash
# Copie o arquivo de exemplo
cp config.example.php config.php

# Edite com suas credenciais
nano config.php
```

### 4. Configure o servidor web
Aponte o DocumentRoot para a pasta `public/`

### 5. Acesse o sistema
```
URL: https://seu-dominio.com
Email: admin@base250.com
Senha: Admin@250 (altere no primeiro acesso!)
```

---

## 📁 Estrutura do Projeto

```
base250/
├── 📄 README.md
├── 📄 CHANGELOG.md
├── 📄 LICENSE
├── 📄 .gitignore
│
├── 📂 public/                 # Arquivos públicos (DocumentRoot)
│   ├── index.php              # Login
│   ├── dashboard.php          # Painel principal
│   ├── logout.php             # Encerrar sessão
│   ├── manifest.json          # PWA manifest
│   ├── sw.js                  # Service Worker
│   │
│   ├── 📂 assets/             # Recursos estáticos
│   │   ├── css/
│   │   ├── js/
│   │   ├── images/
│   │   └── icons/
│   │
│   └── 📂 uploads/            # Arquivos enviados
│
├── 📂 includes/               # Arquivos PHP compartilhados
│   ├── config.php             # Configurações (NÃO versionar!)
│   ├── config.example.php     # Exemplo de configuração
│   └── functions.php          # Funções utilitárias
│
├── 📂 database/               # Scripts SQL
│   ├── schema.sql             # Estrutura das tabelas
│   ├── seed.sql               # Dados iniciais
│   └── migrations/            # Atualizações incrementais
│
└── 📂 docs/                   # Documentação
    ├── INSTALL.md
    ├── API.md
    └── screenshots/
```

---

## 🗄️ Banco de Dados

### Tabelas Principais

| Tabela | Descrição |
|--------|-----------|
| `usuarios` | Usuários do sistema |
| `apartamentos` | Cadastro de unidades |
| `inquilinos` | Dados dos inquilinos |
| `contratos` | Contratos de locação |
| `pagamentos` | Histórico financeiro |
| `documentos` | Arquivos anexados |

---

## 🔒 Segurança

- Senhas armazenadas com `password_hash()` (bcrypt)
- Proteção contra SQL Injection via PDO prepared statements
- Validação e sanitização de inputs
- Sessões com tempo de expiração
- HTTPS obrigatório em produção

---

## 📱 PWA (Progressive Web App)

O sistema pode ser instalado como aplicativo no celular:

1. Acesse o site pelo Chrome/Safari
2. Toque em "Adicionar à tela inicial"
3. Use como um app nativo

---

## 🛠️ Desenvolvimento

### Ambiente Local
```bash
# Usando PHP built-in server
cd public
php -S localhost:8000

# Acesse: http://localhost:8000
```

### Padrões de Código
- PHP: PSR-12
- SQL: Nomes em snake_case
- Commits: Conventional Commits

---

## 📞 Suporte

**Desenvolvido para:** Eng. Diogo  
**Projeto:** Edifício Alto do Itacorubi  
**Local:** Florianópolis - SC

---

## 📄 Licença

Este é um software proprietário. Todos os direitos reservados.

---

**Versão Atual:** 1.0.0  
**Última Atualização:** Fevereiro 2025
