#!/bin/bash
# BASE250 - Script de Verificação de Instalação

echo "====================================="
echo "BASE250 - Verificação de Instalação"
echo "====================================="
echo ""

# Cores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Verifica PHP
echo "1. Verificando PHP..."
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
    echo -e "${GREEN}✓ PHP instalado: versão $PHP_VERSION${NC}"
    
    if [ "$(echo "$PHP_VERSION >= 7.4" | bc)" -eq 1 ]; then
        echo -e "${GREEN}✓ Versão adequada (>= 7.4)${NC}"
    else
        echo -e "${RED}✗ Versão inadequada. Requer PHP 7.4 ou superior${NC}"
    fi
else
    echo -e "${RED}✗ PHP não encontrado${NC}"
fi
echo ""

# Verifica extensões PHP necessárias
echo "2. Verificando extensões PHP..."
extensions=("pdo" "pdo_mysql" "json" "session")
for ext in "${extensions[@]}"; do
    if php -m | grep -q "$ext"; then
        echo -e "${GREEN}✓ $ext${NC}"
    else
        echo -e "${RED}✗ $ext não encontrado${NC}"
    fi
done
echo ""

# Verifica MySQL
echo "3. Verificando MySQL..."
if command -v mysql &> /dev/null; then
    MYSQL_VERSION=$(mysql --version | awk '{print $5}' | sed 's/,//')
    echo -e "${GREEN}✓ MySQL instalado: $MYSQL_VERSION${NC}"
else
    echo -e "${YELLOW}⚠ MySQL client não encontrado (pode estar em outro local)${NC}"
fi
echo ""

# Verifica estrutura de arquivos
echo "4. Verificando estrutura de arquivos..."
required_dirs=(
    "backend/config"
    "backend/api"
    "backend/admin"
    "backend/includes"
    "database"
    "frontend/site_publico"
)

for dir in "${required_dirs[@]}"; do
    if [ -d "$dir" ]; then
        echo -e "${GREEN}✓ $dir${NC}"
    else
        echo -e "${RED}✗ $dir não encontrado${NC}"
    fi
done
echo ""

# Verifica arquivos críticos
echo "5. Verificando arquivos críticos..."
required_files=(
    "backend/config/database.php"
    "backend/api/apartamentos.php"
    "backend/admin/login.php"
    "backend/admin/index.php"
    "frontend/site_publico/index.php"
    "database/schema.sql"
    "database/seed.sql"
)

for file in "${required_files[@]}"; do
    if [ -f "$file" ]; then
        echo -e "${GREEN}✓ $file${NC}"
    else
        echo -e "${RED}✗ $file não encontrado${NC}"
    fi
done
echo ""

# Verifica permissões
echo "6. Verificando permissões..."
if [ -w "." ]; then
    echo -e "${GREEN}✓ Diretório atual tem permissão de escrita${NC}"
else
    echo -e "${RED}✗ Sem permissão de escrita no diretório atual${NC}"
fi
echo ""

# Resumo
echo "====================================="
echo "PRÓXIMOS PASSOS:"
echo "====================================="
echo ""
echo "1. Configure o banco de dados:"
echo "   mysql -u root -p -e \"CREATE DATABASE base250 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\""
echo ""
echo "2. Execute o schema:"
echo "   mysql -u root -p base250 < database/schema.sql"
echo ""
echo "3. Execute o seed (dados iniciais):"
echo "   mysql -u root -p base250 < database/seed.sql"
echo ""
echo "4. Configure a conexão em backend/config/database.php"
echo ""
echo "5. Acesse:"
echo "   - Admin: http://localhost/backend/admin/login.php"
echo "   - Site: http://localhost/frontend/site_publico/index.php"
echo ""
echo "Credenciais admin: admin@base250.com / admin123"
echo ""
