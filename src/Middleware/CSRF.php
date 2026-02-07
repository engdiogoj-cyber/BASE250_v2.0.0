<?php
/**
 * Middleware CSRF
 * Proteção contra Cross-Site Request Forgery
 * 
 * @package BASE250
 * @version 2.0.0
 * @author Eng. Diogo
 */

declare(strict_types=1);

namespace BASE250\Middleware;

class CSRF
{
    /**
     * Inicializa ou valida token CSRF
     * 
     * @return void
     */
    public function handle(): void
    {
        // Gerar token se não existir
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        
        // Validar token em requisições POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST[CSRF_TOKEN_NAME] ?? '';
            
            if (!hash_equals($_SESSION[CSRF_TOKEN_NAME], $token)) {
                http_response_code(403);
                die('CSRF token inválido');
            }
        }
    }
    
    /**
     * Retorna o token CSRF atual
     * 
     * @return string
     */
    public static function token(): string
    {
        return $_SESSION[CSRF_TOKEN_NAME] ?? '';
    }
    
    /**
     * Gera campo hidden com token CSRF
     * 
     * @return string
     */
    public static function field(): string
    {
        $token = self::token();
        return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . htmlspecialchars($token) . '">';
    }
}
