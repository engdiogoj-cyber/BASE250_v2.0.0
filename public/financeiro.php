<?php
require_once __DIR__ . '/../includes/config.php';
requireLogin();

// Processar inserção de pagamento via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'inserir_pagamento') {
    $contrato_id = (int)$_POST['contrato_id'];
    $mes_referencia = $_POST['mes_referencia'] . '-01';
    $valor = (float)str_replace(['.', ','], ['', '.'], $_POST['valor']);
    $valor_pago = (float)str_replace(['.', ','], ['', '.'], $_POST['valor_pago']);
    $data_pagamento = $_POST['data_pagamento'];
    $forma_pagamento = $_POST['forma_pagamento'];
    
    // Buscar data de vencimento do contrato
    $stmtContrato = $pdo->prepare("SELECT dia_vencimento FROM contratos WHERE id = ?");
    $stmtContrato->execute([$contrato_id]);
    $contrato = $stmtContrato->fetch();
    $dia_vencimento = $contrato ? $contrato['dia_vencimento'] : 10;
    
    $data_vencimento = date('Y-m-' . str_pad($dia_vencimento, 2, '0', STR_PAD_LEFT), strtotime($mes_referencia));
    $status = $data_pagamento ? 'pago' : 'pendente';
    
    $stmtInsert = $pdo->prepare("
        INSERT INTO pagamentos (contrato_id, mes_referencia, valor, valor_pago, data_vencimento, data_pagamento, status, forma_pagamento)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmtInsert->execute([
        $contrato_id,
        $mes_referencia,
        $valor,
        $valor_pago ?: null,
        $data_vencimento,
        $data_pagamento ?: null,
        $status,
        $forma_pagamento
    ]);
    
    logAcao($pdo, 'inserir_pagamento', "Pagamento inserido para contrato {$contrato_id}");
    header('Location: financeiro.php?msg=pagamento_inserido');
    exit;
}

// Filtros (padrão: todos)
$filtroStatus = isset($_GET['status']) ? $_GET['status'] : '';
$filtroMes = isset($_GET['mes']) ? $_GET['mes'] : '';
$filtroInquilino = isset($_GET['inquilino']) ? (int)$_GET['inquilino'] : 0;
$filtroTipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'periodo'; // 'periodo' ou 'inquilino'

// Buscar lista de inquilinos para filtro
$stmtInquilinos = $pdo->query("
    SELECT DISTINCT i.id, i.nome, a.numero as apartamento_numero
    FROM inquilinos i
    INNER JOIN contratos c ON i.id = c.inquilino_id AND c.status = 'ativo'
    INNER JOIN apartamentos a ON c.apartamento_id = a.id
    ORDER BY i.nome ASC
");
$listaInquilinos = $stmtInquilinos->fetchAll();

// Buscar contratos ativos para o formulário de inserção
$stmtContratos = $pdo->query("
    SELECT c.id, i.nome as inquilino_nome, a.numero as apartamento_numero, c.valor_aluguel
    FROM contratos c
    INNER JOIN inquilinos i ON c.inquilino_id = i.id
    INNER JOIN apartamentos a ON c.apartamento_id = a.id
    WHERE c.status = 'ativo'
    ORDER BY i.nome ASC
");
$listaContratos = $stmtContratos->fetchAll();

// Buscar estatísticas financeiras
$mesAtual = date('Y-m');
$stats = [
    'total_recebido_mes' => 0,
    'atrasado_mes' => 0,
    'atrasado_acumulado' => 0,
    'pagamentos_mes' => 0
];

// Total recebido do mês atual
$stmt = $pdo->prepare("SELECT COALESCE(SUM(valor_pago), 0) as total FROM pagamentos WHERE status = 'pago' AND DATE_FORMAT(mes_referencia, '%Y-%m') = ?");
$stmt->execute([$mesAtual]);
$stats['total_recebido_mes'] = $stmt->fetch()['total'];

// Atrasado do mês atual
$stmt = $pdo->prepare("SELECT COALESCE(SUM(valor), 0) as total FROM pagamentos WHERE status = 'atrasado' AND DATE_FORMAT(mes_referencia, '%Y-%m') = ?");
$stmt->execute([$mesAtual]);
$stats['atrasado_mes'] = $stmt->fetch()['total'];

// Atrasado acumulado (todos os meses)
$stmt = $pdo->query("SELECT COALESCE(SUM(valor), 0) as total FROM pagamentos WHERE status = 'atrasado'");
$stats['atrasado_acumulado'] = $stmt->fetch()['total'];

// Pagamentos do mês atual
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM pagamentos WHERE DATE_FORMAT(mes_referencia, '%Y-%m') = ?");
$stmt->execute([$mesAtual]);
$stats['pagamentos_mes'] = $stmt->fetch()['total'];

// Dados para gráficos (últimos 6 meses)
$chartData = [];
for ($i = 5; $i >= 0; $i--) {
    $mesChart = date('Y-m', strtotime("-{$i} months"));
    $stmtChart = $pdo->prepare("
        SELECT 
            COALESCE(SUM(CASE WHEN status = 'pago' THEN valor_pago ELSE 0 END), 0) as recebido,
            COALESCE(SUM(CASE WHEN status IN ('pendente', 'atrasado') THEN valor ELSE 0 END), 0) as pendente
        FROM pagamentos 
        WHERE DATE_FORMAT(mes_referencia, '%Y-%m') = ?
    ");
    $stmtChart->execute([$mesChart]);
    $chartRow = $stmtChart->fetch();
    $chartData[] = [
        'mes' => formatarMesReferenciaShort($mesChart . '-01'),
        'recebido' => (float)$chartRow['recebido'],
        'pendente' => (float)$chartRow['pendente']
    ];
}

// Construir query de pagamentos com filtros
$sql = "
    SELECT 
        p.id, p.mes_referencia, p.valor, p.valor_pago, p.data_vencimento, 
        p.data_pagamento, p.status, p.forma_pagamento,
        c.valor_aluguel, c.id as contrato_id,
        i.id as inquilino_id, i.nome as inquilino_nome, i.telefone as inquilino_telefone,
        a.numero as apartamento_numero
    FROM pagamentos p
    INNER JOIN contratos c ON p.contrato_id = c.id
    INNER JOIN inquilinos i ON c.inquilino_id = i.id
    INNER JOIN apartamentos a ON c.apartamento_id = a.id
    WHERE 1=1
";

$params = [];

if ($filtroStatus && in_array($filtroStatus, ['pendente', 'pago', 'atrasado', 'cancelado'])) {
    $sql .= " AND p.status = ?";
    $params[] = $filtroStatus;
}

if ($filtroMes) {
    $sql .= " AND DATE_FORMAT(p.mes_referencia, '%Y-%m') = ?";
    $params[] = $filtroMes;
}

if ($filtroInquilino > 0) {
    $sql .= " AND i.id = ?";
    $params[] = $filtroInquilino;
}

$sql .= " ORDER BY p.data_vencimento DESC LIMIT 100";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pagamentos = $stmt->fetchAll();

// Dados para detalhes dos cartões (modal)
// Recebidos do mês
$stmtRecebidosMes = $pdo->prepare("
    SELECT p.*, i.nome as inquilino_nome, a.numero as apartamento_numero
    FROM pagamentos p
    INNER JOIN contratos c ON p.contrato_id = c.id
    INNER JOIN inquilinos i ON c.inquilino_id = i.id
    INNER JOIN apartamentos a ON c.apartamento_id = a.id
    WHERE p.status = 'pago' AND DATE_FORMAT(p.mes_referencia, '%Y-%m') = ?
    ORDER BY p.data_pagamento DESC
");
$stmtRecebidosMes->execute([$mesAtual]);
$recebidosMes = $stmtRecebidosMes->fetchAll();

// Atrasados do mês
$stmtAtrasadosMes = $pdo->prepare("
    SELECT p.*, i.nome as inquilino_nome, a.numero as apartamento_numero, i.telefone
    FROM pagamentos p
    INNER JOIN contratos c ON p.contrato_id = c.id
    INNER JOIN inquilinos i ON c.inquilino_id = i.id
    INNER JOIN apartamentos a ON c.apartamento_id = a.id
    WHERE p.status = 'atrasado' AND DATE_FORMAT(p.mes_referencia, '%Y-%m') = ?
    ORDER BY p.data_vencimento ASC
");
$stmtAtrasadosMes->execute([$mesAtual]);
$atrasadosMes = $stmtAtrasadosMes->fetchAll();

// Atrasados acumulados
$stmtAtrasadosAcum = $pdo->query("
    SELECT p.*, i.nome as inquilino_nome, a.numero as apartamento_numero, i.telefone
    FROM pagamentos p
    INNER JOIN contratos c ON p.contrato_id = c.id
    INNER JOIN inquilinos i ON c.inquilino_id = i.id
    INNER JOIN apartamentos a ON c.apartamento_id = a.id
    WHERE p.status = 'atrasado'
    ORDER BY p.data_vencimento ASC
");
$atrasadosAcum = $stmtAtrasadosAcum->fetchAll();

// Função para label de status
function getStatusLabel($status) {
    switch ($status) {
        case 'pago': return ['label' => '✅ Pago', 'class' => 'pago'];
        case 'pendente': return ['label' => '⏳ Pendente', 'class' => 'pendente'];
        case 'atrasado': return ['label' => '⚠️ Atrasado', 'class' => 'atrasado'];
        case 'cancelado': return ['label' => '❌ Cancelado', 'class' => 'cancelado'];
        default: return ['label' => $status, 'class' => ''];
    }
}

// Função para formatar mês em português
function formatarMesReferencia($data) {
    $meses = [
        1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
        5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
        9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
    ];
    $dt = new DateTime($data);
    $mes = (int)$dt->format('n');
    $ano = $dt->format('Y');
    return $meses[$mes] . '/' . $ano;
}

// Função para formatar mês curto
function formatarMesReferenciaShort($data) {
    $meses = [
        1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
        5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
        9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
    ];
    $dt = new DateTime($data);
    $mes = (int)$dt->format('n');
    return $meses[$mes];
}

// Função para label de forma de pagamento
function getFormaPagamentoLabel($forma) {
    switch ($forma) {
        case 'pix': return '💠 PIX';
        case 'transferencia': return '🏦 Transferência';
        case 'boleto': return '📄 Boleto';
        case 'dinheiro': return '💵 Dinheiro';
        default: return $forma ?: '-';
    }
}

// Mensagens de sucesso
$mensagem = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'pagamento_inserido') {
        $mensagem = '✅ Pagamento inserido com sucesso!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financeiro - Base 250</title>
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#1e40af">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 20px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .header h1 {
            font-size: 1.5rem;
        }
        
        .header-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn-nav {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            transition: background 0.2s;
        }
        
        .btn-nav:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .btn-nav.primary {
            background: #16a34a;
        }
        
        .btn-nav.primary:hover {
            background: #15803d;
        }
        
        /* Main */
        .main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Alert */
        .alert {
            background: #dcfce7;
            color: #166534;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert.error {
            background: #fee2e2;
            color: #991b1b;
        }
        
        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #1e40af;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .stat-card .icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .stat-card .number {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-card .label {
            color: #64748b;
            font-size: 0.85rem;
        }
        
        .stat-card .sublabel {
            color: #94a3b8;
            font-size: 0.75rem;
            margin-top: 5px;
        }
        
        .stat-card.recebido { border-left-color: #16a34a; }
        .stat-card.recebido .number { color: #16a34a; }
        
        .stat-card.atrasado-mes { border-left-color: #f59e0b; }
        .stat-card.atrasado-mes .number { color: #f59e0b; }
        
        .stat-card.atrasado-acum { border-left-color: #dc2626; }
        .stat-card.atrasado-acum .number { color: #dc2626; }
        
        .stat-card.total { border-left-color: #2563eb; }
        .stat-card.total .number { color: #2563eb; }
        
        /* Chart Section */
        .chart-section {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .chart-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
        }
        
        .chart-container {
            position: relative;
            height: 250px;
        }
        
        /* Filters */
        .filters-section {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
        }
        
        .filter-tab {
            padding: 10px 20px;
            border: none;
            background: #f1f5f9;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            color: #64748b;
            transition: all 0.2s;
        }
        
        .filter-tab.active {
            background: #1e40af;
            color: white;
        }
        
        .filter-tab:hover:not(.active) {
            background: #e2e8f0;
        }
        
        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .filter-group label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
        }
        
        .filter-group select,
        .filter-group input {
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            min-width: 180px;
        }
        
        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: #1e40af;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }
        
        .btn-filter {
            background: #1e40af;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .btn-filter:hover {
            background: #1e3a8a;
        }
        
        .btn-clear {
            background: #e2e8f0;
            color: #64748b;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        
        .btn-clear:hover {
            background: #cbd5e1;
        }
        
        /* Payments Table */
        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .table-header {
            background: #f8fafc;
            padding: 15px 20px;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .payments-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .payments-table th {
            background: #f8fafc;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #64748b;
            font-size: 0.85rem;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .payments-table td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        
        .payments-table tbody tr:hover {
            background: #f8fafc;
        }
        
        .payments-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Payment Info Cell */
        .payment-info {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        
        .payment-info .name {
            font-weight: 600;
            color: #1e293b;
        }
        
        .payment-info .apto {
            font-size: 0.85rem;
            color: #64748b;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-badge.pago {
            background: #dcfce7;
            color: #16a34a;
        }
        
        .status-badge.pendente {
            background: #fef3c7;
            color: #d97706;
        }
        
        .status-badge.atrasado {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .status-badge.cancelado {
            background: #f1f5f9;
            color: #64748b;
        }
        
        /* Value Cell */
        .value-cell {
            font-weight: 600;
            color: #1e293b;
        }
        
        .value-cell.paid {
            color: #16a34a;
        }
        
        /* Date Cell */
        .date-cell {
            color: #64748b;
        }
        
        /* Action Buttons */
        .action-btn {
            background: none;
            border: none;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: background 0.2s;
            text-decoration: none;
        }
        
        .action-btn:hover {
            background: #f1f5f9;
        }
        
        .action-btn.whatsapp:hover {
            background: #dcfce7;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }
        
        .empty-state .icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            font-size: 1.3rem;
            color: #1e293b;
            margin-bottom: 10px;
        }
        
        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .modal-overlay.active {
            display: flex;
        }
        
        .modal {
            background: white;
            border-radius: 15px;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h2 {
            font-size: 1.3rem;
            color: #1e293b;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #64748b;
            padding: 5px;
        }
        
        .modal-close:hover {
            color: #1e293b;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #1e40af;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .modal-footer {
            padding: 20px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: #1e40af;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1e3a8a;
        }
        
        .btn-secondary {
            background: #e2e8f0;
            color: #64748b;
        }
        
        .btn-secondary:hover {
            background: #cbd5e1;
        }
        
        /* Detail Modal List */
        .detail-list {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-item-info {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        
        .detail-item-info .name {
            font-weight: 600;
            color: #1e293b;
        }
        
        .detail-item-info .sub {
            font-size: 0.85rem;
            color: #64748b;
        }
        
        .detail-item-value {
            text-align: right;
        }
        
        .detail-item-value .amount {
            font-weight: 700;
            color: #1e293b;
        }
        
        .detail-item-value .date {
            font-size: 0.85rem;
            color: #64748b;
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.2rem;
            }
            
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-tabs {
                flex-wrap: wrap;
            }
            
            .filters {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-group {
                width: 100%;
            }
            
            .filter-group select,
            .filter-group input {
                min-width: 100%;
            }
            
            .table-container {
                overflow-x: auto;
            }
            
            .payments-table {
                min-width: 700px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .chart-container {
                height: 200px;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .stat-card .number {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>💰 Financeiro</h1>
            <div class="header-nav">
                <button class="btn-nav primary" onclick="openModal('modalInserirPagamento')">➕ Inserir Pagamento</button>
                <a href="dashboard.php" class="btn-nav">🏠 Dashboard</a>
                <a href="logout.php" class="btn-nav">Sair</a>
            </div>
        </div>
    </header>
    
    <main class="main">
        <?php if ($mensagem): ?>
            <div class="alert"><?= $mensagem ?></div>
        <?php endif; ?>
        
        <!-- Estatísticas Financeiras (Clicáveis) -->
        <div class="stats-grid">
            <div class="stat-card recebido" onclick="openModal('modalRecebidosMes')">
                <div class="icon">💚</div>
                <div class="number"><?= formatarDinheiro($stats['total_recebido_mes']) ?></div>
                <div class="label">Total Recebido</div>
                <div class="sublabel">Clique para ver detalhes do mês</div>
            </div>
            <div class="stat-card atrasado-mes" onclick="openModal('modalAtrasadosMes')">
                <div class="icon">⏳</div>
                <div class="number"><?= formatarDinheiro($stats['atrasado_mes']) ?></div>
                <div class="label">Atrasado (Mês)</div>
                <div class="sublabel">Clique para ver detalhes</div>
            </div>
            <div class="stat-card atrasado-acum" onclick="openModal('modalAtrasadosAcum')">
                <div class="icon">⚠️</div>
                <div class="number"><?= formatarDinheiro($stats['atrasado_acumulado']) ?></div>
                <div class="label">Atrasado Acumulado</div>
                <div class="sublabel">Clique para ver todos</div>
            </div>
            <div class="stat-card total">
                <div class="icon">📊</div>
                <div class="number"><?= $stats['pagamentos_mes'] ?></div>
                <div class="label">Pagamentos do Mês</div>
                <div class="sublabel"><?= date('F/Y') ?></div>
            </div>
        </div>
        
        <!-- Gráfico -->
        <div class="chart-section">
            <div class="chart-header">📉 Evolução Financeira (Últimos 6 meses)</div>
            <div class="chart-container">
                <canvas id="financeChart"></canvas>
            </div>
        </div>
        
        <!-- Filtros -->
        <div class="filters-section">
            <div class="filter-tabs">
                <button class="filter-tab <?= $filtroTipo === 'periodo' ? 'active' : '' ?>" onclick="setFilterType('periodo')">📅 Por Período</button>
                <button class="filter-tab <?= $filtroTipo === 'inquilino' ? 'active' : '' ?>" onclick="setFilterType('inquilino')">👤 Por Inquilino</button>
            </div>
            
            <form class="filters" method="GET">
                <input type="hidden" name="tipo" value="<?= htmlspecialchars($filtroTipo) ?>">
                
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select name="status" id="status">
                        <option value="">Todos</option>
                        <option value="pendente" <?= $filtroStatus === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                        <option value="pago" <?= $filtroStatus === 'pago' ? 'selected' : '' ?>>Pago</option>
                        <option value="atrasado" <?= $filtroStatus === 'atrasado' ? 'selected' : '' ?>>Atrasado</option>
                        <option value="cancelado" <?= $filtroStatus === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="mes">Mês de Referência</label>
                    <input type="month" name="mes" id="mes" value="<?= htmlspecialchars($filtroMes) ?>">
                </div>
                
                <div class="filter-group">
                    <label for="inquilino">Inquilino</label>
                    <select name="inquilino" id="inquilino">
                        <option value="0">Todos</option>
                        <?php foreach ($listaInquilinos as $inq): ?>
                            <option value="<?= $inq['id'] ?>" <?= $filtroInquilino == $inq['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($inq['nome']) ?> (Apto <?= $inq['apartamento_numero'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn-filter">🔍 Filtrar</button>
                <a href="financeiro.php" class="btn-clear">✕ Limpar</a>
            </form>
        </div>
        
        <!-- Tabela de Pagamentos -->
        <div class="table-container">
            <div class="table-header">
                <span>📋 Pagamentos</span>
                <span><?= count($pagamentos) ?> registro(s)</span>
            </div>
            
            <?php if (empty($pagamentos)): ?>
                <div class="empty-state">
                    <div class="icon">📭</div>
                    <h3>Nenhum pagamento encontrado</h3>
                    <p>Não há pagamentos registrados com os filtros selecionados.</p>
                </div>
            <?php else: ?>
                <table class="payments-table">
                    <thead>
                        <tr>
                            <th>Inquilino / Apto</th>
                            <th>Referência</th>
                            <th>Valor</th>
                            <th>Vencimento</th>
                            <th>Pagamento</th>
                            <th>Status</th>
                            <th>Forma</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pagamentos as $pag): ?>
                            <?php $statusInfo = getStatusLabel($pag['status']); ?>
                            <tr>
                                <td>
                                    <div class="payment-info">
                                        <span class="name"><?= htmlspecialchars($pag['inquilino_nome']) ?></span>
                                        <span class="apto">🏢 Apto <?= htmlspecialchars($pag['apartamento_numero']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?= formatarMesReferencia($pag['mes_referencia']) ?>
                                </td>
                                <td class="value-cell">
                                    <?= formatarDinheiro($pag['valor']) ?>
                                </td>
                                <td class="date-cell">
                                    <?= formatarData($pag['data_vencimento']) ?>
                                </td>
                                <td class="date-cell <?= $pag['data_pagamento'] ? 'paid' : '' ?>">
                                    <?= $pag['data_pagamento'] ? formatarData($pag['data_pagamento']) : '-' ?>
                                </td>
                                <td>
                                    <span class="status-badge <?= $statusInfo['class'] ?>">
                                        <?= $statusInfo['label'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?= getFormaPagamentoLabel($pag['forma_pagamento']) ?>
                                </td>
                                <td>
                                    <?php if ($pag['inquilino_telefone']): ?>
                                        <?php 
                                        $tel = preg_replace('/[^0-9]/', '', $pag['inquilino_telefone']);
                                        $whatsappLink = "https://wa.me/55{$tel}";
                                        ?>
                                        <a href="<?= $whatsappLink ?>" target="_blank" class="action-btn whatsapp" title="WhatsApp">📱</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>
    
    <!-- Modal: Inserir Pagamento -->
    <div class="modal-overlay" id="modalInserirPagamento">
        <div class="modal">
            <div class="modal-header">
                <h2>➕ Inserir Pagamento</h2>
                <button class="modal-close" onclick="closeModal('modalInserirPagamento')">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="inserir_pagamento">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="contrato_id">Inquilino / Apartamento</label>
                        <select name="contrato_id" id="contrato_id" required>
                            <option value="">Selecione...</option>
                            <?php foreach ($listaContratos as $contrato): ?>
                                <option value="<?= $contrato['id'] ?>" data-valor="<?= $contrato['valor_aluguel'] ?>">
                                    <?= htmlspecialchars($contrato['inquilino_nome']) ?> - Apto <?= $contrato['apartamento_numero'] ?> (<?= formatarDinheiro($contrato['valor_aluguel']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="mes_referencia">Mês de Referência</label>
                            <input type="month" name="mes_referencia" id="mes_referencia" required value="<?= date('Y-m') ?>">
                        </div>
                        <div class="form-group">
                            <label for="valor">Valor (R$)</label>
                            <input type="text" name="valor" id="valor" required placeholder="0,00">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="valor_pago">Valor Pago (R$)</label>
                            <input type="text" name="valor_pago" id="valor_pago" placeholder="0,00">
                        </div>
                        <div class="form-group">
                            <label for="data_pagamento">Data do Pagamento</label>
                            <input type="date" name="data_pagamento" id="data_pagamento" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="forma_pagamento">Forma de Pagamento</label>
                        <select name="forma_pagamento" id="forma_pagamento" required>
                            <option value="pix" selected>💠 PIX</option>
                            <option value="transferencia">🏦 Transferência</option>
                            <option value="boleto">📄 Boleto</option>
                            <option value="dinheiro">💵 Dinheiro</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalInserirPagamento')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">💾 Salvar Pagamento</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal: Recebidos do Mês -->
    <div class="modal-overlay" id="modalRecebidosMes">
        <div class="modal">
            <div class="modal-header">
                <h2>💚 Recebidos do Mês</h2>
                <button class="modal-close" onclick="closeModal('modalRecebidosMes')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="detail-list">
                    <?php if (empty($recebidosMes)): ?>
                        <div class="empty-state" style="padding: 30px;">
                            <p>Nenhum pagamento recebido no mês atual.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recebidosMes as $item): ?>
                            <div class="detail-item">
                                <div class="detail-item-info">
                                    <span class="name"><?= htmlspecialchars($item['inquilino_nome']) ?></span>
                                    <span class="sub">Apto <?= $item['apartamento_numero'] ?> - <?= formatarMesReferencia($item['mes_referencia']) ?></span>
                                </div>
                                <div class="detail-item-value">
                                    <div class="amount" style="color: #16a34a;"><?= formatarDinheiro($item['valor_pago']) ?></div>
                                    <div class="date"><?= formatarData($item['data_pagamento']) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal: Atrasados do Mês -->
    <div class="modal-overlay" id="modalAtrasadosMes">
        <div class="modal">
            <div class="modal-header">
                <h2>⏳ Atrasados do Mês</h2>
                <button class="modal-close" onclick="closeModal('modalAtrasadosMes')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="detail-list">
                    <?php if (empty($atrasadosMes)): ?>
                        <div class="empty-state" style="padding: 30px;">
                            <p>Nenhum pagamento atrasado no mês atual. 🎉</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($atrasadosMes as $item): ?>
                            <div class="detail-item">
                                <div class="detail-item-info">
                                    <span class="name"><?= htmlspecialchars($item['inquilino_nome']) ?></span>
                                    <span class="sub">Apto <?= $item['apartamento_numero'] ?> - Venc: <?= formatarData($item['data_vencimento']) ?></span>
                                </div>
                                <div class="detail-item-value">
                                    <div class="amount" style="color: #f59e0b;"><?= formatarDinheiro($item['valor']) ?></div>
                                    <?php if ($item['telefone']): ?>
                                        <?php $tel = preg_replace('/[^0-9]/', '', $item['telefone']); ?>
                                        <a href="https://wa.me/55<?= $tel ?>" target="_blank" style="font-size: 1.2rem;">📱</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal: Atrasados Acumulados -->
    <div class="modal-overlay" id="modalAtrasadosAcum">
        <div class="modal">
            <div class="modal-header">
                <h2>⚠️ Atrasados Acumulados</h2>
                <button class="modal-close" onclick="closeModal('modalAtrasadosAcum')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="detail-list">
                    <?php if (empty($atrasadosAcum)): ?>
                        <div class="empty-state" style="padding: 30px;">
                            <p>Nenhum pagamento atrasado. 🎉</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($atrasadosAcum as $item): ?>
                            <div class="detail-item">
                                <div class="detail-item-info">
                                    <span class="name"><?= htmlspecialchars($item['inquilino_nome']) ?></span>
                                    <span class="sub">Apto <?= $item['apartamento_numero'] ?> - <?= formatarMesReferencia($item['mes_referencia']) ?></span>
                                </div>
                                <div class="detail-item-value">
                                    <div class="amount" style="color: #dc2626;"><?= formatarDinheiro($item['valor']) ?></div>
                                    <?php if ($item['telefone']): ?>
                                        <?php $tel = preg_replace('/[^0-9]/', '', $item['telefone']); ?>
                                        <a href="https://wa.me/55<?= $tel ?>" target="_blank" style="font-size: 1.2rem;">📱</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Dados do gráfico
        const chartData = <?= json_encode($chartData) ?>;
        
        // Inicializar gráfico
        const ctx = document.getElementById('financeChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.map(d => d.mes),
                datasets: [
                    {
                        label: 'Recebido',
                        data: chartData.map(d => d.recebido),
                        backgroundColor: 'rgba(22, 163, 74, 0.8)',
                        borderColor: 'rgba(22, 163, 74, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pendente/Atrasado',
                        data: chartData.map(d => d.pendente),
                        backgroundColor: 'rgba(245, 158, 11, 0.8)',
                        borderColor: 'rgba(245, 158, 11, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR');
                            }
                        }
                    }
                }
            }
        });
        
        // Funções de Modal
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }
        
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }
        
        // Fechar modal ao clicar fora
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });
        
        // Definir tipo de filtro
        function setFilterType(tipo) {
            const url = new URL(window.location);
            url.searchParams.set('tipo', tipo);
            window.location = url.toString();
        }
        
        // Preencher valor automaticamente ao selecionar contrato
        document.getElementById('contrato_id').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const valor = option.dataset.valor;
            if (valor) {
                document.getElementById('valor').value = parseFloat(valor).toLocaleString('pt-BR', {minimumFractionDigits: 2});
                document.getElementById('valor_pago').value = parseFloat(valor).toLocaleString('pt-BR', {minimumFractionDigits: 2});
            }
        });
        
        // Formatar campo de valor
        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, '');
            value = (parseInt(value) / 100).toFixed(2);
            value = value.replace('.', ',');
            value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            input.value = value;
        }
        
        document.getElementById('valor').addEventListener('input', function() { formatCurrency(this); });
        document.getElementById('valor_pago').addEventListener('input', function() { formatCurrency(this); });
    </script>
</body>
</html>
