<?php
/**
 * Controller Base
 * Classe abstrata com métodos comuns a todos os controllers
 * 
 * @package BASE250
 * @version 2.0.0
 * @author Eng. Diogo
 */

declare(strict_types=1);

namespace BASE250\Controllers;

abstract class BaseController
{
    /**
     * Renderiza uma view com dados
     * 
     * @param string $view Nome da view (ex: 'dashboard/index')
     * @param array $data Dados para passar para a view
     * @return void
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        
        ob_start();
        require_once TEMPLATES_PATH . '/pages/' . $view . '.php';
        $content = ob_get_clean();
        
        require_once TEMPLATES_PATH . '/layout.php';
    }
    
    /**
     * Retorna resposta JSON
     * 
     * @param array $data Dados para retornar
     * @param int $status Código HTTP de status
     * @return void
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Redireciona para uma URL
     * 
     * @param string $url URL para redirecionar
     * @return void
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Valida token CSRF
     * 
     * @return bool
     */
    protected function validateCSRF(): bool
    {
        $token = $_POST[CSRF_TOKEN_NAME] ?? '';
        $sessionToken = $_SESSION[CSRF_TOKEN_NAME] ?? '';
        
        return hash_equals($sessionToken, $token);
    }
}
