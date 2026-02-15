# 🤝 Guia de Contribuição - BASE250

Obrigado por considerar contribuir para o BASE250! Este documento fornece diretrizes para contribuições ao projeto.

---

## 📋 Código de Conduta

Ao participar deste projeto, você concorda em seguir nosso código de conduta:

- **Seja respeitoso**: Trate todos com respeito e consideração
- **Seja construtivo**: Críticas devem ser construtivas e focadas no código, não nas pessoas
- **Seja colaborativo**: Trabalhe em conjunto para melhorar o projeto
- **Seja profissional**: Mantenha discussões focadas e produtivas

---

## 🚀 Como Contribuir

### 1. Reportar Bugs

**Antes de reportar:**
- Verifique se o bug já foi reportado nas [Issues](https://github.com/engdiogoj-cyber/BASE250_v2.0.0/issues)
- Use a versão mais recente do código

**Ao reportar:**
- Use um título claro e descritivo
- Descreva os passos para reproduzir o problema
- Inclua screenshots se aplicável
- Informe seu ambiente (SO, PHP version, MySQL version)

**Template de Bug Report:**
```markdown
## Descrição do Bug
[Descrição clara e concisa do bug]

## Passos para Reproduzir
1. Ir para '...'
2. Clicar em '...'
3. Rolar até '...'
4. Ver erro

## Comportamento Esperado
[O que deveria acontecer]

## Comportamento Atual
[O que realmente acontece]

## Screenshots
[Se aplicável]

## Ambiente
- SO: [Windows/Linux/Mac]
- PHP: [Versão]
- MySQL: [Versão]
- Navegador: [Chrome/Firefox/Safari]
```

### 2. Sugerir Melhorias

**Antes de sugerir:**
- Verifique se a sugestão já existe nas Issues
- Considere se a funcionalidade se alinha com os objetivos do projeto

**Ao sugerir:**
- Use um título claro e descritivo
- Explique o problema que a melhoria resolve
- Descreva a solução proposta
- Liste alternativas consideradas

### 3. Pull Requests

#### Preparação

**Fork e Clone:**
```bash
# Fork o repositório no GitHub
# Clone seu fork
git clone https://github.com/SEU_USUARIO/BASE250_v2.0.0.git
cd BASE250_v2.0.0

# Adicione o upstream
git remote add upstream https://github.com/engdiogoj-cyber/BASE250_v2.0.0.git
```

**Crie uma Branch:**
```bash
git checkout -b feature/nome-da-feature
# OU
git checkout -b fix/nome-do-fix
```

#### Desenvolvimento

**Padrões de Código:**

1. **PHP**
   - Seguir PSR-12
   - Usar type hints
   - Comentários PHPDoc
   - Declarar tipos de retorno

```php
<?php
/**
 * Descrição da função
 * 
 * @param string $param Descrição do parâmetro
 * @return array
 */
public function exemploFuncao(string $param): array
{
    // Código aqui
    return [];
}
```

2. **CSS**
   - Seguir ordem alfabética de propriedades
   - Usar variáveis CSS quando possível
   - Comentários para seções

```css
/* ========================
   NOME DA SEÇÃO
   ======================== */

.classe {
    background: var(--cor-primaria);
    border-radius: var(--border-radius);
    color: var(--text);
    padding: 16px;
}
```

3. **JavaScript**
   - ES6+ syntax
   - Comentários JSDoc
   - CamelCase para variáveis
   - PascalCase para classes

```javascript
/**
 * Descrição da função
 * @param {string} param - Descrição do parâmetro
 * @returns {boolean}
 */
function exemploFuncao(param) {
    // Código aqui
    return true;
}
```

**Commits:**
- Mensagens em português
- Formato: `tipo: descrição`
- Tipos: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`

```bash
git commit -m "feat: adicionar validação de CPF"
git commit -m "fix: corrigir erro no cálculo de aluguéis"
git commit -m "docs: atualizar ARCHITECTURE.md"
```

#### Testes

**Antes de submeter PR:**
```bash
# Verificar sintaxe PHP
find . -name "*.php" -exec php -l {} \;

# Testar aplicação localmente
php -S localhost:8000 -t public/

# Testar em diferentes navegadores
# Chrome, Firefox, Safari, Edge
```

#### Submeter PR

**Push das mudanças:**
```bash
git push origin feature/nome-da-feature
```

**Criar PR no GitHub:**
- Título claro e descritivo
- Descrição detalhada das mudanças
- Referenciar Issues relacionadas: `Fixes #123`
- Marcar como Draft se ainda em desenvolvimento

**Template de PR:**
```markdown
## Descrição
[Descrição clara das mudanças]

## Tipo de Mudança
- [ ] Bug fix
- [ ] Nova funcionalidade
- [ ] Breaking change
- [ ] Documentação

## Como Testar
1. [Passo 1]
2. [Passo 2]
3. [Passo 3]

## Checklist
- [ ] Código segue PSR-12
- [ ] Comentários adicionados em áreas complexas
- [ ] Documentação atualizada
- [ ] Testado localmente
- [ ] Sem warnings PHP
- [ ] Responsivo (mobile/desktop)

## Issues Relacionadas
Fixes #123
```

---

## 🎨 Guia de Estilo

### Nomes de Arquivos
- PHP: `PascalCase.php` (ex: `DashboardController.php`)
- CSS: `kebab-case.css` (ex: `design-system.css`)
- JS: `camelCase.js` (ex: `app.js`)

### Nomes de Classes (PHP)
```php
namespace BASE250\Controllers;

class DashboardController extends BaseController
{
    // ...
}
```

### Nomes de Métodos (PHP)
```php
public function meuMetodo(): void
{
    // camelCase
}
```

### Nomes de Variáveis
```php
$minhaVariavel = 'valor';  // PHP: camelCase
let minhaVariavel = 'valor';  // JS: camelCase
```

### Indentação
- **4 espaços** para PHP
- **2 espaços** para HTML/CSS/JS
- Nunca usar tabs

---

## 🔒 Segurança

**Se encontrar vulnerabilidade:**
- ⚠️ **NÃO** abra issue pública
- Envie email para: contato@base250.com
- Inclua descrição detalhada e passos para reproduzir
- Aguarde resposta antes de divulgar publicamente

---

## 📚 Recursos

### Documentação
- [ARCHITECTURE.md](docs/ARCHITECTURE.md) - Arquitetura do sistema
- [DEPLOYMENT.md](docs/DEPLOYMENT.md) - Guia de deploy
- [CHANGELOG.md](CHANGELOG.md) - Histórico de mudanças

### Padrões
- [PSR-12](https://www.php-fig.org/psr/psr-12/) - PHP Coding Style
- [Keep a Changelog](https://keepachangelog.com/pt-BR/) - Formato de changelog
- [Semantic Versioning](https://semver.org/lang/pt-BR/) - Versionamento

### Ferramentas
- [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) - Linter PHP
- [PHPStan](https://phpstan.org/) - Static Analysis
- [PHP CS Fixer](https://cs.symfony.com/) - Auto-formatter

---

## ✅ Processo de Review

**O que esperamos:**
1. Código limpo e legível
2. Seguir padrões do projeto
3. Documentação atualizada
4. Testado localmente
5. Sem conflitos com main branch

**Timeline:**
- Review inicial: 2-3 dias úteis
- Feedback: iterativo até aprovação
- Merge: após aprovação de maintainer

---

## 🎯 Prioridades Atuais

Áreas que mais precisam de contribuições:

1. **Models** - Implementar Models completos (User, Tenant, Payment)
2. **Testes** - Adicionar unit tests e integration tests
3. **Documentação** - Melhorar docs e adicionar exemplos
4. **UI/UX** - Melhorias de interface e acessibilidade
5. **Performance** - Otimizações de queries e cache

---

## 📞 Contato

**Dúvidas sobre contribuições?**
- Email: contato@base250.com
- Issues: [GitHub Issues](https://github.com/engdiogoj-cyber/BASE250_v2.0.0/issues)
- Discussões: [GitHub Discussions](https://github.com/engdiogoj-cyber/BASE250_v2.0.0/discussions)

---

## 🙏 Agradecimentos

Obrigado por contribuir para o BASE250! Cada contribuição, por menor que seja, é valiosa e apreciada.

---

**Última atualização:** 2025-02-07
