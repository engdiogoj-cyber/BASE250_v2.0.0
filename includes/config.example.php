<?php
/**
 * BASE250 - Sistema de Gestão de Imóveis
 * Arquivo de Configuração (EXEMPLO)
 * 
 * ⚠️ INSTRUÇÕES:
 * 1. Copie este arquivo para "config.php"
 * 2. Preencha com suas credenciais reais
 * 3. NUNCA faça commit do config.php!
 */

// ==========================================
// MODO DE DESENVOLVIMENTO
// ==========================================
// Mude para false em produção!
define('DEBUG_MODE', true);

// ==========================================
// BANCO DE DADOS
// ==========================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'seu_banco_aqui');      // Ex: u483505869_base250
define('DB_USER', 'seu_usuario_aqui');    // Ex: u483505869_diogo
define('DB_PASS', 'sua_senha_aqui');      // ⚠️ NUNCA compartilhe!

// ==========================================
// CONFIGURAÇÕES DO SITE
// ==========================================
define('SITE_URL', 'https://seu-dominio.com');
define('SITE_NAME', 'Base 250 - Gestão de Imóveis');

// ==========================================
// SESSÃO
// ==========================================
define('SESSION_LIFETIME', 3600); // 1 hora em segundos

// ==========================================
// UPLOAD DE ARQUIVOS
// ==========================================
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_PATH', __DIR__ . '/uploads/');
define('ALLOWED_EXTENSIONS', ['pdf', 'jpg', 'jpeg', 'png']);

// ==========================================
// E-MAIL (para notificações)
// ==========================================
define('MAIL_FROM', 'contato@seu-dominio.com');
define('MAIL_NAME', 'Base 250');
// Se usar SMTP:
// define('SMTP_HOST', 'smtp.hostinger.com');
// define('SMTP_PORT', 587);
// define('SMTP_USER', 'seu@email.com');
// define('SMTP_PASS', 'sua_senha_smtp');

// ==========================================
// TIMEZONE
// ==========================================
date_default_timezone_set('America/Sao_Paulo');

// ==========================================
// INICIAR SESSÃO
// ==========================================
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    if (!DEBUG_MODE) {
        ini_set('session.cookie_secure', 1); // Apenas HTTPS
    }
    session_start();
}

// ==========================================
// CONEXÃO COM BANCO DE DADOS
// ==========================================
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    if (DEBUG_MODE) {
        die("Erro de conexão: " . $e->getMessage());
    } else {
        error_log("Erro DB: " . $e->getMessage());
        die("Erro de conexão com o banco de dados. Contate o administrador.");
    }
}

// ==========================================
// FUNÇÕES UTILITÁRIAS
// ==========================================

/**
 * Limpa e sanitiza input do usuário
 */
function limparInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Verifica se usuário está logado
 */
function isLoggedIn() {
    return isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id']);
}

/**
 * Requer login para acessar a página
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}

/**
 * Verifica se é administrador
 */
function isAdmin() {
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin';
}

/**
 * Formata CPF: 12345678900 -> 123.456.789-00
 */
function formatarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) !== 11) return $cpf;
    return substr($cpf, 0, 3) . '.' . 
           substr($cpf, 3, 3) . '.' . 
           substr($cpf, 6, 3) . '-' . 
           substr($cpf, 9, 2);
}

/**
 * Formata telefone: 48999991234 -> (48) 99999-1234
 */
function formatarTelefone($tel) {
    $tel = preg_replace('/[^0-9]/', '', $tel);
    if (strlen($tel) == 11) {
        return '(' . substr($tel, 0, 2) . ') ' . 
               substr($tel, 2, 5) . '-' . 
               substr($tel, 7, 4);
    }
    if (strlen($tel) == 10) {
        return '(' . substr($tel, 0, 2) . ') ' . 
               substr($tel, 2, 4) . '-' . 
               substr($tel, 6, 4);
    }
    return $tel;
}

/**
 * Formata valor monetário: 1500.00 -> R$ 1.500,00
 */
function formatarDinheiro($valor) {
    return 'R$ ' . number_format((float)$valor, 2, ',', '.');
}

/**
 * Formata data: 2025-02-05 -> 05/02/2025
 */
function formatarData($data) {
    if (empty($data)) return '';
    $dt = new DateTime($data);
    return $dt->format('d/m/Y');
}

/**
 * Gera link do WhatsApp
 */
function linkWhatsApp($telefone, $mensagem = '') {
    $tel = preg_replace('/[^0-9]/', '', $telefone);
    $url = "https://wa.me/55{$tel}";
    if ($mensagem) {
        $url .= "?text=" . urlencode($mensagem);
    }
    return $url;
}

/**
 * Log de ação (auditoria)
 */
function logAcao($pdo, $acao, $detalhes = '') {
    if (!isLoggedIn()) return;
    
    $stmt = $pdo->prepare("
        INSERT INTO logs (usuario_id, acao, detalhes, ip, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $_SESSION['usuario_id'],
        $acao,
        $detalhes,
        $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ]);
}
?>
