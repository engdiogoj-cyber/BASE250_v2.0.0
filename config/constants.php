<?php
/**
 * Constantes Globais do Sistema
 * 
 * @package BASE250
 * @version 2.0.0
 * @author Eng. Diogo
 */

declare(strict_types=1);

// Diretórios
define('BASE_PATH', dirname(__DIR__));
define('CONFIG_PATH', BASE_PATH . '/config');
define('SRC_PATH', BASE_PATH . '/src');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('TEMPLATES_PATH', BASE_PATH . '/templates');

// URLs
define('BASE_URL', '/');
define('ASSETS_URL', BASE_URL . 'assets/');

// Sistema
define('APP_NAME', 'BASE250');
define('APP_VERSION', '2.0.0');
define('APP_ENV', 'production'); // production, development

// Segurança
define('SESSION_LIFETIME', 7200); // 2 horas
define('CSRF_TOKEN_NAME', 'csrf_token');

// Timezone
date_default_timezone_set('America/Sao_Paulo');
