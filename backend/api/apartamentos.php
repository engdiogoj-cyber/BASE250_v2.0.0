<?php
/**
 * BASE250 - API: Listar Apartamentos
 * 
 * Endpoint GET para listar todos os apartamentos
 * Retorna JSON com informações completas incluindo galeria de fotos
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../config/database.php';

try {
    $pdo = getDBConnection();
    
    // Query para buscar todos os apartamentos
    $sql = "
        SELECT 
            id,
            numero,
            tipo,
            metragem,
            quartos,
            banheiros,
            preco,
            status,
            descricao,
            features,
            galeria_fotos,
            andar
        FROM apartamentos
        ORDER BY 
            CAST(numero AS UNSIGNED) ASC,
            numero ASC
    ";
    
    $stmt = $pdo->query($sql);
    $apartamentos = $stmt->fetchAll();
    
    // Decodifica JSON dos campos features e galeria_fotos
    foreach ($apartamentos as &$apt) {
        $apt['features'] = json_decode($apt['features'] ?? '[]', true) ?: [];
        $apt['galeria_fotos'] = json_decode($apt['galeria_fotos'] ?? '[]', true) ?: [];
        
        // Converte tipos numéricos
        $apt['id'] = (int)$apt['id'];
        $apt['metragem'] = (float)$apt['metragem'];
        $apt['quartos'] = (int)$apt['quartos'];
        $apt['banheiros'] = (int)$apt['banheiros'];
        $apt['preco'] = (float)$apt['preco'];
        $apt['andar'] = (int)$apt['andar'];
    }
    
    jsonResponse(true, $apartamentos, 'Apartamentos carregados com sucesso');
    
} catch (Exception $e) {
    error_log("Erro ao listar apartamentos: " . $e->getMessage());
    jsonResponse(false, null, 'Erro ao carregar apartamentos', 500);
}
