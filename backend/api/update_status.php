<?php
/**
 * BASE250 - API: Atualizar Status de Apartamento
 * 
 * Endpoint POST para alternar status entre disponível e alugado
 * Requer autenticação
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: POST');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

// Verifica autenticação
if (!isLoggedIn()) {
    jsonResponse(false, null, 'Não autenticado', 401);
}

// Apenas POST permitido
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, null, 'Método não permitido', 405);
}

try {
    // Obtém dados da requisição
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        $data = $_POST;
    }
    
    // Valida campos obrigatórios
    $missing = validateRequired($data, ['id', 'status']);
    if (!empty($missing)) {
        jsonResponse(false, null, 'Campos obrigatórios faltando: ' . implode(', ', $missing), 400);
    }
    
    $id = (int)$data['id'];
    $status = sanitizeInput($data['status']);
    
    // Valida status
    $statusValidos = ['disponivel', 'alugado'];
    if (!in_array($status, $statusValidos)) {
        jsonResponse(false, null, 'Status inválido. Use: disponivel ou alugado', 400);
    }
    
    $pdo = getDBConnection();
    
    // Verifica se apartamento existe
    $stmt = $pdo->prepare("SELECT id, numero FROM apartamentos WHERE id = ?");
    $stmt->execute([$id]);
    $apartamento = $stmt->fetch();
    
    if (!$apartamento) {
        jsonResponse(false, null, 'Apartamento não encontrado', 404);
    }
    
    // Atualiza status
    $stmt = $pdo->prepare("
        UPDATE apartamentos 
        SET status = ?
        WHERE id = ?
    ");
    $stmt->execute([$status, $id]);
    
    jsonResponse(true, [
        'id' => $id,
        'numero' => $apartamento['numero'],
        'status' => $status
    ], 'Status atualizado com sucesso');
    
} catch (Exception $e) {
    error_log("Erro ao atualizar status: " . $e->getMessage());
    jsonResponse(false, null, 'Erro ao atualizar status', 500);
}
