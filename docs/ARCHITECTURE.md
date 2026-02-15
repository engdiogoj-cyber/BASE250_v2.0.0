# 📐 Arquitetura do Sistema BASE250

## Visão Geral

O BASE250 utiliza uma arquitetura **MVC (Model-View-Controller)** moderna em PHP, com separação clara de responsabilidades e foco em segurança, escalabilidade e manutenibilidade.

## Camadas da Aplicação

### 1. Entry Point (`public/index.php`)

Ponto de entrada único da aplicação. Responsabilidades:

- Autoloading de classes (PSR-4)
- Inicialização de sessão segura
- Aplicação de middleware global (Auth, CSRF)
- Roteamento de requisições
- Tratamento de erros

**Fluxo de Execução:**
```php
1. Carregar constantes e configurações
2. Registrar autoloader
3. Iniciar sessão segura
4. Aplicar middleware (Auth → CSRF)
5. Rotear requisição para Controller
6. Executar método do Controller
7. Renderizar resposta
```

### 2. Controllers (`src/Controllers/`)

Camada de controle. Responsabilidades:

- Receber requisições HTTP
- Validar entrada do usuário
- Interagir com Models
- Renderizar Views
- Retornar respostas (HTML/JSON)

**Exemplo: DashboardController**
```php
public function index(): void
{
    $data = [
        'totalTenants' => 18,
        'recentNotifications' => $this->getNotifications()
    ];
    
    $this->render('dashboard/index', $data);
}
```

**Controllers Disponíveis:**
- `BaseController`: Classe abstrata com métodos comuns
- `DashboardController`: Dashboard principal
- `AdminController`: Gestão administrativa (planejado)
- `FinanceController`: Controle financeiro (planejado)
- `TenantController`: Área do inquilino (planejado)
- `ConfigController`: Configurações do sistema (planejado)

### 3. Models (`src/Models/`)

Camada de dados. Responsabilidades:

- Comunicação com banco de dados
- Lógica de negócio
- Validação de dados
- Prepared statements (segurança)

**Exemplo: Tenant Model (planejado)**
```php
public function findById(int $id): ?array
{
    $stmt = $this->db->prepare('SELECT * FROM tenants WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}
```

**Models Planejados:**
- `User`: Usuários do sistema
- `Tenant`: Inquilinos
- `Property`: Imóveis
- `Contract`: Contratos
- `Payment`: Pagamentos
- `Document`: Documentos

### 4. Views (`templates/`)

Camada de apresentação. Responsabilidades:

- Renderização HTML
- Escape de dados (XSS protection)
- Componentes reutilizáveis

**Estrutura:**
```
templates/
├── layout.php          # Master layout
├── components/         # Componentes reutilizáveis
│   ├── header.php
│   ├── sidebar.php
│   └── footer.php
└── pages/             # Páginas específicas
    ├── dashboard/
    ├── admin/
    ├── finance/
    ├── tenant/
    └── config/
```

### 5. Middleware (`src/Middleware/`)

Camada de interceptação. Responsabilidades:

- Autenticação de usuários
- Autorização por roles
- CSRF protection
- Rate limiting (planejado)
- Logging (planejado)

**Middleware Implementados:**

**Auth.php** - Autenticação
```php
- Verifica se usuário está logado
- Cria sessão de demonstração (temporário)
- Controle de permissões por role
```

**CSRF.php** - Proteção CSRF
```php
- Gera token único por sessão
- Valida token em requisições POST
- Fornece helpers para templates
```

### 6. Helpers (`src/Helpers/`)

Funções auxiliares. Responsabilidades:

- Sanitização de dados
- Validação de entrada
- Utilitários diversos

**Security.php** - Segurança
```php
- sanitize(): Escape HTML (XSS protection)
- validateEmail(): Validação de email
- validateCPF(): Validação de CPF
- hashPassword(): Hash seguro (Argon2ID)
- verifyPassword(): Verificação de senha
```

---

## Fluxo de Requisição

```
┌─────────────┐
│   Cliente   │
└──────┬──────┘
       │ HTTP Request
       v
┌─────────────────────┐
│  public/index.php   │
│  (Entry Point)      │
└──────┬──────────────┘
       │ 1. Load config
       │ 2. Autoload classes
       │ 3. Start session
       v
┌─────────────────────┐
│   Middleware        │
│   - Auth            │
│   - CSRF            │
└──────┬──────────────┘
       │ Authorized
       v
┌─────────────────────┐
│     Router          │
│  (routes.php)       │
└──────┬──────────────┘
       │ Match route
       v
┌─────────────────────┐
│   Controller        │
│   - Validate input  │
│   - Call Model      │
└──────┬──────────────┘
       │
       v
┌─────────────────────┐
│      Model          │
│   (Database)        │
└──────┬──────────────┘
       │ Data
       v
┌─────────────────────┐
│   View (Template)   │
│   - Render HTML     │
│   - Escape data     │
└──────┬──────────────┘
       │ HTML Response
       v
┌─────────────┐
│   Cliente   │
└─────────────┘
```

---

## Banco de Dados

### Conexão

**Classe: `Database`**
- Driver: PDO (MySQL)
- Charset: UTF-8 (utf8mb4)
- Mode: ERRMODE_EXCEPTION
- Singleton pattern
- Prepared Statements obrigatórios

**Configuração:**
```php
private const HOST = 'localhost';
private const DB = 'base250_db';
private const USER = 'base250_user';
private const PASS = 'SENHA_SEGURA';
```

### Tabelas Principais (Planejadas)

| Tabela | Descrição |
|--------|-----------|
| `users` | Usuários do sistema (admin, gerente, inquilino) |
| `tenants` | Dados dos inquilinos |
| `properties` | Imóveis disponíveis |
| `contracts` | Contratos de locação |
| `payments` | Histórico de pagamentos |
| `documents` | Documentos armazenados |
| `notifications` | Notificações do sistema |
| `audit_logs` | Logs de auditoria |

---

## Segurança

### Implementações

1. **CSRF Protection**
   - Token único por sessão
   - Validação em todas as requisições POST
   - Helper para inserir token em formulários

2. **SQL Injection**
   - Prepared Statements obrigatórios
   - PDO com modo de exceções
   - Sem concatenação de SQL

3. **XSS Protection**
   - Escape automático com `htmlspecialchars()`
   - Helper `Security::sanitize()`
   - Flag ENT_QUOTES | ENT_HTML5

4. **Session Security**
   - HTTPOnly flag (previne JavaScript access)
   - SameSite=Strict (previne CSRF)
   - Secure flag (HTTPS em produção)
   - Strict mode ativado

5. **Password Security**
   - Hash com Argon2ID
   - Verificação com `password_verify()`
   - Nunca armazenar senhas em texto plano

6. **Input Validation**
   - Validação de email (FILTER_VALIDATE_EMAIL)
   - Validação de CPF com dígitos verificadores
   - Sanitização de arrays

### Boas Práticas

- ✅ Sempre usar prepared statements
- ✅ Sempre escapar output HTML
- ✅ Validar entrada do usuário
- ✅ Usar HTTPS em produção
- ✅ Manter dependências atualizadas
- ✅ Logs de erro seguros (sem dados sensíveis)
- ✅ Rate limiting em endpoints críticos (planejado)

---

## Design Patterns

### 1. MVC (Model-View-Controller)
Separação de responsabilidades entre dados, lógica e apresentação.

### 2. Singleton
Conexão de banco de dados única e reutilizada.

### 3. Template Method
BaseController com métodos comuns herdados.

### 4. Dependency Injection (planejado)
Injeção de dependências via construtor.

### 5. Repository Pattern (planejado)
Abstração de acesso a dados.

---

## Tecnologias

| Camada | Tecnologia | Versão |
|--------|-----------|--------|
| Backend | PHP | 8.0+ |
| Database | MySQL | 8.0+ |
| Frontend | HTML5, CSS3 | - |
| JavaScript | ES6+ | - |
| Template Engine | PHP Nativo | - |
| Fonts | Google Fonts (Inter) | - |

---

## Performance

### Otimizações

1. **CSS**
   - Arquivo único minificado
   - Variáveis CSS (`:root`)
   - Mobile-first design

2. **JavaScript**
   - Carregamento no final do `<body>`
   - Event delegation
   - Debounce em inputs (planejado)

3. **Database**
   - Índices em campos buscados
   - Prepared statements cacheados
   - Conexão persistente desativada (evita overhead)

4. **Cache** (planejado)
   - Opcode cache (OPcache)
   - Session cache (Redis/Memcached)
   - Static asset caching

---

## Escalabilidade

### Horizontal

- Stateless design (sessões em DB/Redis)
- Load balancer compatível
- Assets servidos via CDN

### Vertical

- Pool de conexões gerenciado
- Queries otimizadas
- Lazy loading planejado

---

## Testes (Planejados)

### Unit Tests
- PHPUnit para Models e Helpers
- Cobertura mínima: 80%

### Integration Tests
- Testes de Controllers
- Testes de Middleware

### E2E Tests
- Selenium/Playwright
- Fluxos críticos

---

## Deployment

Veja [DEPLOYMENT.md](DEPLOYMENT.md) para instruções completas de deploy.

**Ambientes:**
- **Development**: Máquina local
- **Staging**: Servidor de testes (planejado)
- **Production**: Hostinger

---

## Manutenção

### Logs

**Localização:**
- PHP errors: `error_log` do servidor
- Application logs: `logs/app.log` (planejado)
- Audit logs: Tabela `audit_logs` (planejado)

### Backup

- Diário automático às 3h (planejado)
- Retenção: 30 dias
- Armazenamento: Local + Cloud

### Monitoramento (Planejado)

- Uptime monitoring
- Error tracking
- Performance metrics

---

## Roadmap

### v2.1 (Próxima)
- [ ] Models completos (User, Tenant, Payment, etc.)
- [ ] Sistema de autenticação real
- [ ] CRUD de inquilinos
- [ ] Upload de documentos

### v2.2
- [ ] Módulo financeiro completo
- [ ] Geração de PDFs (contratos, recibos)
- [ ] Sistema de notificações por email

### v3.0
- [ ] API REST
- [ ] Integração Gov.br
- [ ] App mobile (PWA)
- [ ] Dashboard analítico avançado

---

## Contato

Para questões sobre a arquitetura:
- Email: contato@base250.com
- Issues: https://github.com/engdiogoj-cyber/BASE250_v2.0.0/issues
