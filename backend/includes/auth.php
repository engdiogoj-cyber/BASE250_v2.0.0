<?php
/**
 * BASE250 - Funções de Autenticação
 * 
 * Sistema de autenticação básico para área administrativa
 */

require_once __DIR__ . '/../config/database.php';

ensureSession();

/**
 * Verifica se usuário está logado
 * 
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Requer autenticação - redireciona para login se não autenticado
 */
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: /backend/admin/login.php');
        exit;
    }
}

/**
 * Realiza login do usuário
 * 
 * @param string $email
 * @param string $senha
 * @return array ['success' => bool, 'message' => string]
 */
function login($email, $senha) {
    try {
        $pdo = getDBConnection();
        
        $stmt = $pdo->prepare("
            SELECT id, nome, email, senha, tipo 
            FROM usuarios 
            WHERE email = ? AND tipo = 'admin' AND ativo = 1
        ");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        
        if (!$usuario) {
            return [
                'success' => false,
                'message' => 'Email ou senha inválidos'
            ];
        }
        
        // Verifica senha
        if (password_verify($senha, $usuario['senha'])) {
            // Login bem-sucedido
            $_SESSION['admin_id'] = $usuario['id'];
            $_SESSION['admin_nome'] = $usuario['nome'];
            $_SESSION['admin_email'] = $usuario['email'];
            $_SESSION['admin_tipo'] = $usuario['tipo'];
            
            // Atualiza último acesso
            $updateStmt = $pdo->prepare("
                UPDATE usuarios 
                SET ultimo_acesso = NOW() 
                WHERE id = ?
            ");
            $updateStmt->execute([$usuario['id']]);
            
            return [
                'success' => true,
                'message' => 'Login realizado com sucesso'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Email ou senha inválidos'
            ];
        }
    } catch (Exception $e) {
        error_log("Erro no login: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Erro ao processar login'
        ];
    }
}

/**
 * Realiza logout do usuário
 */
function logout() {
    $_SESSION = [];
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
}

/**
 * Obtém dados do usuário logado
 * 
 * @return array|null
 */
function getLoggedInUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['admin_id'],
        'nome' => $_SESSION['admin_nome'],
        'email' => $_SESSION['admin_email'],
        'tipo' => $_SESSION['admin_tipo']
    ];
}
