<?php
/**
 * BASE250 - Sistema de Gestão de Imóveis
 * Entry Point da Aplicação
 * 
 * @package BASE250
 * @version 2.0.0
 * @author Eng. Diogo
 * @license MIT
 */

declare(strict_types=1);

// Configurações e constantes
require_once __DIR__ . '/../config/constants.php';

// Autoloader simples (em produção usar Composer)
spl_autoload_register(function ($class) {
    $prefix = 'BASE250\\';
    $base_dir = SRC_PATH . '/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Configurar sessão segura
session_start([
    'cookie_secure' => false, // true em produção com HTTPS
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict',
    'use_strict_mode' => true
]);

// Middleware de autenticação
$auth = new \BASE250\Middleware\Auth();
$auth->handle();

// Middleware CSRF
$csrf = new \BASE250\Middleware\CSRF();
$csrf->handle();

// Roteamento simples
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
if ($uri === '') {
    $uri = '/';
}

// Carregar rotas
$routes = require_once CONFIG_PATH . '/routes.php';

// Verificar se rota existe
if (!isset($routes[$uri])) {
    // Por enquanto, sempre mostrar dashboard
    $controllerName = 'BASE250\\Controllers\\DashboardController';
    $method = 'index';
} else {
    $route = $routes[$uri];
    $controllerName = 'BASE250\\Controllers\\' . $route['controller'] . 'Controller';
    $method = $route['method'];
}

// Executar controller
try {
    if (!class_exists($controllerName)) {
        throw new \Exception('Controller não encontrado');
    }
    
    $controller = new $controllerName();
    
    if (!method_exists($controller, $method)) {
        throw new \Exception('Método não encontrado');
    }
    
    $controller->$method();
} catch (\Exception $e) {
    // Log de erro
    error_log('Erro na aplicação: ' . $e->getMessage());
    
    // Exibir página de erro
    http_response_code(500);
    echo '<!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Erro - BASE250</title>
        <style>
            body { font-family: Inter, sans-serif; padding: 40px; text-align: center; }
            h1 { color: #dc2626; }
        </style>
    </head>
    <body>
        <h1>Erro no Sistema</h1>
        <p>Ocorreu um erro ao processar sua solicitação.</p>
        <p><a href="/">Voltar ao início</a></p>
    </body>
    </html>';
}
