# GitHub Copilot Instructions for BASE250

## Project Overview

BASE250 is a comprehensive apartment management system with a public-facing website and administrative backend. The system allows for dynamic listing of apartments, photo galleries, and complete CRUD operations for apartment management.

**Domain:** base250.com  
**Tech Stack:** PHP 7.4+, MySQL 5.7+, JavaScript (Vanilla), HTML5, CSS3  
**Architecture:** Backend PHP with PDO, RESTful JSON API, Session-based authentication

## Core Principles

1. **Security First**: Always use prepared statements, sanitize inputs, validate data, and encrypt sensitive information
2. **Minimal Changes**: Make the smallest possible changes to achieve the goal
3. **Code Quality**: Follow PSR-12 for PHP, use type hints and return types
4. **Documentation**: Update documentation when making changes that affect APIs or user-facing features
5. **Portuguese Language**: All user-facing messages, comments in Portuguese, and commit messages in Portuguese

## File Structure

```
BASE250_v2.0.0/
├── backend/               # Backend PHP code
│   ├── api/              # REST API endpoints
│   ├── admin/            # Admin panel pages
│   ├── includes/         # Shared utilities (auth, etc.)
│   └── config/           # Configuration files (database, etc.)
├── frontend/
│   ├── site_publico/     # Public-facing website
│   └── painel/           # Dashboard panel
├── database/             # Database schema and seeds
├── assets/               # Static assets (images, etc.)
├── docs/                 # Public documentation
└── docs_private/         # Sensitive docs (NEVER commit)
```

## Coding Standards

### PHP (PSR-12)

- Use 4 spaces for indentation
- Type hints for all parameters and return types
- PHPDoc comments for all functions and classes
- camelCase for methods and variables
- PascalCase for class names
- Always use prepared statements with PDO
- Validate and sanitize all inputs

Example:
```php
<?php
/**
 * Atualiza o status de um apartamento
 * 
 * @param int $id ID do apartamento
 * @param bool $disponivel Status de disponibilidade
 * @return bool
 */
public function atualizarStatus(int $id, bool $disponivel): bool
{
    $stmt = $this->pdo->prepare("UPDATE apartamentos SET disponivel = ? WHERE id = ?");
    return $stmt->execute([$disponivel, $id]);
}
```

### JavaScript

- Use ES6+ syntax
- 2 spaces for indentation
- camelCase for variables and functions
- PascalCase for classes
- JSDoc comments for functions

### CSS

- 2 spaces for indentation
- Use CSS variables when possible
- Alphabetical order for properties
- kebab-case for class names
- Section comments for organization

### HTML

- 2 spaces for indentation
- Semantic HTML5 elements
- Accessibility attributes (aria-label, alt text)
- Responsive design (mobile-first)

## Security Guidelines

### Critical Security Rules

1. **SQL Injection Prevention**: ALWAYS use prepared statements with PDO
   ```php
   // ✅ CORRECT
   $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
   $stmt->execute([$id]);
   
   // ❌ NEVER DO THIS
   $query = "SELECT * FROM users WHERE id = " . $id;
   ```

2. **XSS Prevention**: Sanitize all outputs
   ```php
   echo htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8');
   ```

3. **Password Security**: Use bcrypt/password_hash
   ```php
   $hash = password_hash($password, PASSWORD_BCRYPT);
   password_verify($password, $hash);
   ```

4. **Session Security**: Configured in auth.php
   - Use secure session settings
   - Regenerate session IDs on login
   - Validate session data

5. **File Upload Security**: Validate types, sizes, and sanitize filenames

### Sensitive Files (NEVER Commit)

- `backend/config/database.php` (actual credentials)
- `.env` files
- Files in `docs_private/`
- Anything with passwords or API keys

## Database

**Connection**: PDO with prepared statements  
**Character Set**: utf8mb4 (full Unicode support)  
**Schema**: See `database/schema.sql`  
**Seeds**: See `database/seed.sql`

### Main Tables

- `apartamentos`: Apartment listings
- `usuarios`: User accounts (bcrypt passwords)
- `fotos_apartamento`: Photo galleries
- `caracteristicas_apartamento`: Apartment features

## API Endpoints

**Base**: `/backend/api/`  
**Format**: JSON  
**Authentication**: Session-based for admin endpoints

- `GET /apartamentos.php` - List all apartments (public)
- `POST /update_apartamento.php` - Update apartment (admin)
- `POST /update_status.php` - Toggle availability status (admin)

## Testing Approach

This project does NOT have automated tests. When making changes:

1. **Manual Testing**: Test locally using `php -S localhost:8000`
2. **PHP Syntax**: Run `php -l` on changed files
3. **Browser Testing**: Test in Chrome, Firefox, Safari
4. **Responsive Testing**: Test on mobile and desktop viewports
5. **Security Testing**: Verify input validation and sanitization

## Development Workflow

### Making Changes

1. **Understand the code**: Read existing code before making changes
2. **Minimal changes**: Only change what's necessary
3. **Follow patterns**: Match existing code style and patterns
4. **Test locally**: Always test changes before committing
5. **Update docs**: If changing APIs or features

### Commit Messages (Portuguese)

Format: `tipo: descrição`

Types:
- `feat`: Nova funcionalidade
- `fix`: Correção de bug
- `docs`: Documentação
- `style`: Formatação/estilo
- `refactor`: Refatoração de código
- `test`: Testes
- `chore`: Manutenção

Examples:
```bash
git commit -m "feat: adicionar filtro de apartamentos por preço"
git commit -m "fix: corrigir validação de email no login"
git commit -m "docs: atualizar documentação da API"
```

## Common Tasks

### Adding a New API Endpoint

1. Create PHP file in `backend/api/`
2. Include database connection: `require_once '../config/database.php';`
3. Use prepared statements for all queries
4. Validate and sanitize all inputs
5. Return JSON: `header('Content-Type: application/json');`
6. Handle errors gracefully
7. Document in `backend/API.md`

### Adding a New Admin Page

1. Create PHP file in `backend/admin/`
2. Include auth check: `require_once '../includes/auth.php';`
3. Check if user is logged in
4. Use consistent HTML structure with existing pages
5. Include CSRF protection
6. Add to admin navigation if needed

### Modifying Database Schema

1. Create migration SQL in `database/migrations/`
2. Update `database/schema.sql`
3. Test with fresh database install
4. Document changes in CHANGELOG.md

## Dependencies

### PHP Extensions Required

- PDO
- pdo_mysql
- mbstring
- json

### No External PHP Dependencies

- No Composer packages currently
- Standard library only
- Keep it simple and maintainable

## Performance Considerations

1. **Database Queries**: Use LIMIT for large datasets
2. **Images**: Optimize images before upload
3. **Caching**: Consider implementing simple file-based cache for public data
4. **Sessions**: Use database sessions for production

## Accessibility

- Use semantic HTML5 elements
- Provide alt text for images
- ARIA labels for interactive elements
- Keyboard navigation support
- Sufficient color contrast

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile browsers (iOS Safari, Chrome Mobile)
- No IE11 support required

## Deployment Notes

- Deploy to Hostinger or compatible PHP hosting
- HTTPS required in production
- Change default passwords immediately
- Set appropriate file permissions (644 for files, 755 for directories)
- Disable `display_errors` in production
- Enable error logging to file

## Resources

- [CONTRIBUTING.md](../CONTRIBUTING.md) - Full contribution guidelines
- [backend/SETUP.md](../backend/SETUP.md) - Installation guide
- [backend/API.md](../backend/API.md) - API documentation
- [CHANGELOG.md](../CHANGELOG.md) - Version history
- [PSR-12](https://www.php-fig.org/psr/psr-12/) - PHP coding standard

## Contact

- Email: contato@base250.com
- GitHub Issues: Report bugs and request features
- WhatsApp: (48) 99935-2627

---

**Last Updated**: 2026-02-15
