<?php
/**
 * Sistema de Rotas
 * 
 * @package BASE250
 * @version 2.0.0
 * @author Eng. Diogo
 */

declare(strict_types=1);

// Rotas do sistema
$routes = [
    '/' => ['controller' => 'Dashboard', 'method' => 'index'],
    '/dashboard' => ['controller' => 'Dashboard', 'method' => 'index'],
    '/status-sistema' => ['controller' => 'Dashboard', 'method' => 'statusSistema'],
    
    // Administrativo
    '/admin/cadastro-inquilinos' => ['controller' => 'Admin', 'method' => 'cadastroInquilinos'],
    '/admin/aprovacoes' => ['controller' => 'Admin', 'method' => 'aprovacoes'],
    '/admin/documentos' => ['controller' => 'Admin', 'method' => 'documentos'],
    
    // Financeiro
    '/financeiro/pagamentos' => ['controller' => 'Finance', 'method' => 'pagamentos'],
    '/financeiro/comprovantes' => ['controller' => 'Finance', 'method' => 'comprovantes'],
    '/financeiro/relatorios' => ['controller' => 'Finance', 'method' => 'relatorios'],
    
    // Inquilino
    '/inquilino/meus-dados' => ['controller' => 'Tenant', 'method' => 'meusDados'],
    '/inquilino/contrato' => ['controller' => 'Tenant', 'method' => 'contrato'],
    '/inquilino/pagamentos' => ['controller' => 'Tenant', 'method' => 'pagamentos'],
    
    // Configurações
    '/config/templates' => ['controller' => 'Config', 'method' => 'templates'],
    '/config/backup' => ['controller' => 'Config', 'method' => 'backup'],
];

return $routes;
