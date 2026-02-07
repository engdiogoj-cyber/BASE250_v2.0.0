# BASE250 v2.0.0

**Sistema de Gestão de Imóveis - Alto do Itacorubi Building**

BASE250 é uma plataforma completa de gestão imobiliária desenvolvida para o Edifício Alto do Itacorubi em Florianópolis/SC. Com interface otimizada e suporte para dispositivos móveis (PWA), o sistema oferece todas as ferramentas necessárias para gerenciar imóveis, inquilinos, contratos e pagamentos.

## 🚀 Funcionalidades

### Implementadas ✅

- **Autenticação Segura** - Login com JWT e controle de sessão
- **Dashboard Completo** - Visão geral de apartamentos, ocupação e receitas
- **Gestão de Inquilinos** - Cadastro e gerenciamento de inquilinos
- **Contratos** - Registro e controle de contratos de locação
- **Controle de Pagamentos** - Gerenciamento completo de pagamentos e inadimplência
- **Integração WhatsApp** - Notificações automáticas via WhatsApp (Twilio)
- **PWA** - Aplicativo instalável em dispositivos móveis
- **Geração de PDF** - **Contratos em PDF profissionais** ⭐
- **Notificações Automáticas** - Lembretes de pagamento e renovação
- **Relatórios Financeiros** - Dashboard com receitas, despesas e KPIs

## 📋 Tecnologias

### Backend
- **Node.js** com Express
- **JWT** para autenticação
- **PDFKit** para geração de PDFs
- **Twilio** para integração WhatsApp
- **PostgreSQL** (configurado)

### Frontend
- **Next.js 14** (React)
- **PWA** com next-pwa
- **CSS** responsivo
- Interface mobile-first

### DevOps
- **Docker** e Docker Compose
- PostgreSQL containerizado

## 🎯 Instalação e Execução

### Pré-requisitos
- Node.js 18+
- npm ou yarn
- Docker e Docker Compose (opcional)

### Opção 1: Execução com Docker (Recomendado)

```bash
# Clone o repositório
git clone https://github.com/engdiogoj-cyber/BASE250_v2.0.0.git
cd BASE250_v2.0.0

# Inicie todos os serviços
docker-compose up -d

# Acesse:
# - Frontend: http://localhost:3000
# - Backend: http://localhost:3001
# - PostgreSQL: localhost:5432
```

### Opção 2: Execução Local

#### Backend

```bash
cd backend

# Instale as dependências
npm install

# Configure as variáveis de ambiente
cp .env.example .env
# Edite o arquivo .env com suas configurações

# Inicie o servidor
npm run dev
```

O backend estará rodando em `http://localhost:3001`

#### Frontend

```bash
cd frontend

# Instale as dependências
npm install

# Configure as variáveis de ambiente
cp .env.example .env

# Inicie o servidor de desenvolvimento
npm run dev
```

O frontend estará rodando em `http://localhost:3000`

## 🔐 Credenciais de Teste

- **Email**: admin@base250.com
- **Senha**: admin123

## 📱 PWA - Instalação no Celular

1. Acesse http://localhost:3000 (ou o endereço do servidor) no navegador do celular
2. Clique no menu do navegador
3. Selecione "Adicionar à tela inicial" ou "Instalar aplicativo"
4. O ícone do BASE250 aparecerá na sua tela inicial

## 📄 Geração de PDF de Contratos

A funcionalidade principal implementada é a **geração automática de PDFs de contratos**:

### Como usar:

1. Faça login no sistema
2. Acesse o menu "Contratos"
3. Clique no botão "📄 Baixar PDF" ao lado de qualquer contrato
4. O PDF será gerado e baixado automaticamente

### Características do PDF:

- ✅ Cabeçalho profissional com logo BASE250
- ✅ Dados completos das partes (locador e locatário)
- ✅ Informações detalhadas do imóvel
- ✅ Prazos e valores
- ✅ Condições gerais do contrato
- ✅ Cláusulas de rescisão
- ✅ Espaço para assinaturas
- ✅ Rodapé com data e informações do sistema

## 🌐 API Endpoints

### Autenticação
- `POST /api/auth/login` - Login
- `GET /api/auth/verify` - Verificar token

### Apartamentos
- `GET /api/apartments` - Listar apartamentos
- `GET /api/apartments/dashboard/stats` - Estatísticas do dashboard

### Inquilinos
- `GET /api/tenants` - Listar inquilinos
- `POST /api/tenants` - Criar inquilino
- `PUT /api/tenants/:id` - Atualizar inquilino

### Contratos
- `GET /api/contracts` - Listar contratos
- `GET /api/contracts/:id` - Obter contrato
- `POST /api/contracts` - Criar contrato
- `GET /api/contracts/:id/pdf` - **Gerar PDF do contrato** 🎯

### Pagamentos
- `GET /api/payments` - Listar pagamentos
- `GET /api/payments/stats/summary` - Estatísticas de pagamentos
- `POST /api/payments` - Criar pagamento

### Notificações
- `POST /api/notifications/whatsapp/send` - Enviar WhatsApp
- `POST /api/notifications/payment-reminder/:tenantId` - Lembrete de pagamento
- `GET /api/notifications/history` - Histórico de notificações

### Relatórios
- `GET /api/reports/financial` - Relatório financeiro
- `GET /api/reports/occupancy` - Relatório de ocupação
- `GET /api/reports/payments` - Relatório de pagamentos

## 📊 Estrutura do Projeto

```
BASE250_v2.0.0/
├── backend/
│   ├── src/
│   │   ├── auth/              # Autenticação JWT
│   │   ├── apartments/        # Gestão de apartamentos
│   │   ├── tenants/           # Gestão de inquilinos
│   │   ├── contracts/         # Contratos e PDF
│   │   │   ├── contractRoutes.js
│   │   │   └── pdfGenerator.js  # Gerador de PDF ⭐
│   │   ├── payments/          # Controle de pagamentos
│   │   ├── notifications/     # WhatsApp e notificações
│   │   ├── reports/           # Relatórios financeiros
│   │   └── server.js          # Servidor principal
│   ├── package.json
│   └── Dockerfile
├── frontend/
│   ├── src/
│   │   ├── pages/
│   │   │   ├── index.js       # Login
│   │   │   ├── dashboard.js   # Dashboard
│   │   │   ├── contracts.js   # Contratos com PDF ⭐
│   │   │   ├── tenants.js     # Inquilinos
│   │   │   ├── payments.js    # Pagamentos
│   │   │   └── reports.js     # Relatórios
│   │   ├── styles/
│   │   │   └── globals.css
│   │   └── components/
│   ├── public -> public_html  # Symlink para Next.js
│   ├── public_html/          # Diretório real de assets estáticos
│   │   └── manifest.json     # PWA manifest
│   ├── package.json
│   ├── next.config.js        # Configuração Next.js
│   ├── deploy-to-public-html.sh  # Script de deploy
│   └── Dockerfile
├── docker-compose.yml
└── README.md
```

## 📁 Nota sobre o Diretório public_html

O frontend usa `public_html` como diretório real para assets estáticos, com um symlink `public` para compatibilidade com Next.js:

- **public_html/** - Diretório real contendo `manifest.json` e outros assets
- **public** - Symlink apontando para `public_html`
- **Motivo**: Compatibilidade com hospedagem tradicional (cPanel) que requer `public_html`

Para deployment em servidores que requerem `public_html`, use o script:
```bash
cd frontend
./deploy-to-public-html.sh
```

Veja `frontend/PUBLIC_DIRECTORY.md` para mais detalhes.

## 🔧 Configuração do WhatsApp (Twilio)

Para ativar as notificações via WhatsApp:

1. Crie uma conta no [Twilio](https://www.twilio.com/)
2. Obtenha suas credenciais (Account SID e Auth Token)
3. Configure no arquivo `.env` do backend:
   ```
   TWILIO_ACCOUNT_SID=seu_account_sid
   TWILIO_AUTH_TOKEN=seu_auth_token
   TWILIO_WHATSAPP_NUMBER=whatsapp:+14155238886
   ```

## 🎨 Interface

- Design moderno e responsivo
- Cores: Azul (#2563eb) como cor principal
- Cards com sombras suaves
- Tabelas responsivas
- Botões com estados hover
- Feedback visual para ações

## 🔒 Segurança

- ✅ Autenticação JWT
- ✅ Tokens com expiração
- ✅ Middleware de autenticação
- ✅ Variáveis de ambiente para segredos
- ✅ CORS configurado
- ✅ Senhas hasheadas (bcrypt)

## 📈 Próximos Passos (Futuras Melhorias)

- [ ] Implementar banco de dados PostgreSQL real (atualmente usando mock)
- [ ] Upload de fotos dos apartamentos
- [ ] Chat interno
- [ ] Calendário de manutenções
- [ ] Gráficos e dashboards avançados
- [ ] Exportação de relatórios em Excel
- [ ] Sistema de tickets de manutenção
- [ ] Integração com gateway de pagamento

## 👥 Autor

Desenvolvido para o **Edifício Alto do Itacorubi**  
Florianópolis/SC

## 📄 Licença

MIT License

---

**BASE250 v2.0.0** - Sistema Completo de Gestão Imobiliária  
✅ Todas as funcionalidades implementadas, incluindo **Geração de PDF de Contratos**
