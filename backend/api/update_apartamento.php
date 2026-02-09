<?php
/**
 * BASE250 - API: Atualizar Apartamento
 * 
 * Endpoint POST para atualizar informações completas de um apartamento
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
    $missing = validateRequired($data, ['id']);
    if (!empty($missing)) {
        jsonResponse(false, null, 'ID do apartamento é obrigatório', 400);
    }
    
    $id = (int)$data['id'];
    
    $pdo = getDBConnection();
    
    // Verifica se apartamento existe
    $stmt = $pdo->prepare("SELECT id FROM apartamentos WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        jsonResponse(false, null, 'Apartamento não encontrado', 404);
    }
    
    // Campos que podem ser atualizados
    $updateFields = [];
    $updateValues = [];
    
    if (isset($data['numero'])) {
        $updateFields[] = "numero = ?";
        $updateValues[] = sanitizeInput($data['numero']);
    }
    
    if (isset($data['tipo'])) {
        $updateFields[] = "tipo = ?";
        $updateValues[] = sanitizeInput($data['tipo']);
    }
    
    if (isset($data['metragem'])) {
        $updateFields[] = "metragem = ?";
        $updateValues[] = (float)$data['metragem'];
    }
    
    if (isset($data['quartos'])) {
        $updateFields[] = "quartos = ?";
        $updateValues[] = (int)$data['quartos'];
    }
    
    if (isset($data['banheiros'])) {
        $updateFields[] = "banheiros = ?";
        $updateValues[] = (int)$data['banheiros'];
    }
    
    if (isset($data['preco'])) {
        $updateFields[] = "preco = ?";
        $updateValues[] = (float)$data['preco'];
    }
    
    if (isset($data['descricao'])) {
        $updateFields[] = "descricao = ?";
        $updateValues[] = sanitizeInput($data['descricao']);
    }
    
    if (isset($data['features'])) {
        $updateFields[] = "features = ?";
        $features = is_array($data['features']) ? $data['features'] : json_decode($data['features'], true);
        $updateValues[] = json_encode($features, JSON_UNESCAPED_UNICODE);
    }
    
    if (isset($data['galeria_fotos'])) {
        $updateFields[] = "galeria_fotos = ?";
        $galeria = is_array($data['galeria_fotos']) ? $data['galeria_fotos'] : json_decode($data['galeria_fotos'], true);
        $updateValues[] = json_encode($galeria, JSON_UNESCAPED_UNICODE);
    }
    
    if (empty($updateFields)) {
        jsonResponse(false, null, 'Nenhum campo para atualizar', 400);
    }
    
    // Adiciona ID ao final dos valores
    $updateValues[] = $id;
    
    // Executa update
    $sql = "UPDATE apartamentos SET " . implode(', ', $updateFields) . " WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($updateValues);
    
    // Busca apartamento atualizado
    $stmt = $pdo->prepare("
        SELECT id, numero, tipo, metragem, quartos, banheiros, preco, status, descricao, features, galeria_fotos
        FROM apartamentos 
        WHERE id = ?
    ");
    $stmt->execute([$id]);
    $apartamento = $stmt->fetch();
    
    // Decodifica JSON
    $apartamento['features'] = json_decode($apartamento['features'] ?? '[]', true);
    $apartamento['galeria_fotos'] = json_decode($apartamento['galeria_fotos'] ?? '[]', true);
    
    jsonResponse(true, $apartamento, 'Apartamento atualizado com sucesso');
    
} catch (Exception $e) {
    error_log("Erro ao atualizar apartamento: " . $e->getMessage());
    jsonResponse(false, null, 'Erro ao atualizar apartamento: ' . $e->getMessage(), 500);
}
