<div align="center">

# 🏢 BASE250

### Sistema Profissional de Gestão de Imóveis

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?logo=mysql)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Version](https://img.shields.io/badge/Version-2.0.0-blue.svg)](CHANGELOG.md)

[Demonstração](#) · [Documentação](#documentação) · [Reportar Bug](https://github.com/engdiogoj-cyber/BASE250_v2.0.0/issues) · [Solicitar Funcionalidade](https://github.com/engdiogoj-cyber/BASE250_v2.0.0/issues)

</div>

---

## 📋 Sobre o Projeto

BASE250 é um sistema completo e profissional para gestão de imóveis, desenvolvido com arquitetura PHP MVC moderna. Oferece controle total sobre cadastros, contratos, pagamentos e documentação de inquilinos, com interface responsiva e design system proprietário.

### ✨ Funcionalidades Principais

- 🏠 **Painel Geral**: Dashboard com métricas e notificações em tempo real
- ⚙️ **Administrativo**: Gestão de cadastros, aprovações, documentos e contratos
- 💰 **Financeiro**: Controle de pagamentos, comprovantes e relatórios
- 👥 **Área do Inquilino**: Portal self-service com integração Gov.br
- 🔧 **Configurações**: Templates, backup, logs de auditoria e parâmetros

---

## 🏗️ Arquitetura

```
BASE250_v2.0.0/
├── config/          # Configurações e rotas
├── src/             # Código-fonte (MVC)
│   ├── Controllers/
│   ├── Models/
│   ├── Middleware/
│   └── Helpers/
├── public/          # Entry point e assets
│   ├── index.php
│   ├── .htaccess
│   └── assets/
│       ├── css/
│       ├── js/
│       └── images/
├── templates/       # Templates PHP
│   ├── layout.php
│   ├── components/
│   └── pages/
└── docs/            # Documentação
```

### 🎨 Design System

- **Cor Primária**: `#16697A` (BASE250 Teal)
- **Tipografia**: Inter (Google Fonts)
- **Framework**: Custom CSS (Design System v1.0)
- **Responsividade**: Mobile-first

---

## 🚀 Instalação

### Requisitos

- PHP 8.0 ou superior
- MySQL 8.0+ / MariaDB 10.5+
- Servidor web (Apache/Nginx)

### Passos

1. **Clone o repositório**
   ```bash
   git clone https://github.com/engdiogoj-cyber/BASE250_v2.0.0.git
   cd BASE250_v2.0.0
   ```

2. **Configure o banco de dados**
   ```bash
   # Edite config/database.php com suas credenciais
   nano config/database.php
   ```

3. **Importe o schema** (quando disponível)
   ```bash
   mysql -u usuario -p base250_db < database/schema.sql
   ```

4. **Configure o servidor web**
   - **Apache**: Use o `.htaccess` incluído em `public/`
   - **Nginx**: Veja `docs/DEPLOYMENT.md`

5. **Acesse o sistema**
   ```
   http://localhost/BASE250_v2.0.0/public/
   ```

**Credenciais padrão**: Sistema em demonstração (sem autenticação por enquanto)

---

## 📖 Documentação

- [📐 Arquitetura](docs/ARCHITECTURE.md)
- [🚀 Deploy](docs/DEPLOYMENT.md)
- [🔒 Segurança](docs/SECURITY.md)

---

## 🛡️ Segurança

- ✅ Proteção CSRF em todos os formulários
- ✅ Prepared Statements (proteção SQL Injection)
- ✅ Sanitização de entrada (proteção XSS)
- ✅ Sessões seguras com HTTPOnly e Secure flags
- ✅ Validação de CPF e email

Para reportar vulnerabilidades: [contato@base250.com](mailto:contato@base250.com)

---

## 🤝 Contribuindo

Contribuições são bem-vindas! Veja [CONTRIBUTING.md](CONTRIBUTING.md) para detalhes.

---

## 📄 Licença

Este projeto está sob a licença MIT. Veja [LICENSE](LICENSE) para mais informações.

---

## 👤 Autor

**Eng. Diogo**

- GitHub: [@engdiogoj-cyber](https://github.com/engdiogoj-cyber)
- Email: contato@base250.com

---

<div align="center">

Desenvolvido com ❤️ por [Eng. Diogo](https://github.com/engdiogoj-cyber)

⭐ Se este projeto foi útil, considere dar uma estrela!

</div>
