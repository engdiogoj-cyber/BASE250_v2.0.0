<?php
require_once __DIR__ . '/../includes/config.php';
requireLogin();

// Filtro de status (padrão: todos)
$filtroStatus = isset($_GET['status']) ? $_GET['status'] : '';
$filtroMes = isset($_GET['mes']) ? $_GET['mes'] : '';

// Buscar estatísticas financeiras
$stats = [
    'total_recebido' => 0,
    'total_pendente' => 0,
    'total_atrasado' => 0,
    'pagamentos_mes' => 0
];

// Total recebido (pagamentos com status 'pago')
$stmt = $pdo->query("SELECT COALESCE(SUM(valor_pago), 0) as total FROM pagamentos WHERE status = 'pago'");
$stats['total_recebido'] = $stmt->fetch()['total'];

// Total pendente
$stmt = $pdo->query("SELECT COALESCE(SUM(valor), 0) as total FROM pagamentos WHERE status = 'pendente'");
$stats['total_pendente'] = $stmt->fetch()['total'];

// Total atrasado
$stmt = $pdo->query("SELECT COALESCE(SUM(valor), 0) as total FROM pagamentos WHERE status = 'atrasado'");
$stats['total_atrasado'] = $stmt->fetch()['total'];

// Pagamentos do mês atual
$stmt = $pdo->query("SELECT COUNT(*) as total FROM pagamentos WHERE MONTH(mes_referencia) = MONTH(CURRENT_DATE) AND YEAR(mes_referencia) = YEAR(CURRENT_DATE)");
$stats['pagamentos_mes'] = $stmt->fetch()['total'];

// Construir query de pagamentos com filtros
$sql = "
    SELECT 
        p.id, p.mes_referencia, p.valor, p.valor_pago, p.data_vencimento, 
        p.data_pagamento, p.status, p.forma_pagamento,
        c.valor_aluguel,
        i.nome as inquilino_nome, i.telefone as inquilino_telefone,
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

$sql .= " ORDER BY p.data_vencimento DESC LIMIT 50";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pagamentos = $stmt->fetchAll();

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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financeiro - Base 250</title>
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#1e40af">
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
        }
        
        .header h1 {
            font-size: 1.5rem;
        }
        
        .header-nav {
            display: flex;
            align-items: center;
            gap: 15px;
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
        
        /* Main */
        .main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Page Title */
        .page-title {
            font-size: 1.5rem;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
        }
        
        .stat-card .icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .stat-card .number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-card .label {
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .stat-card.recebido { border-left-color: #16a34a; }
        .stat-card.recebido .number { color: #16a34a; }
        
        .stat-card.pendente { border-left-color: #f59e0b; }
        .stat-card.pendente .number { color: #f59e0b; }
        
        .stat-card.atrasado { border-left-color: #dc2626; }
        .stat-card.atrasado .number { color: #dc2626; }
        
        .stat-card.total { border-left-color: #2563eb; }
        .stat-card.total .number { color: #2563eb; }
        
        /* Filters */
        .filters {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
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
            margin-top: auto;
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
            margin-top: auto;
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
        
        /* Mobile */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.2rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
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
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .stat-card .number {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>💰 Financeiro</h1>
            <div class="header-nav">
                <a href="dashboard.php" class="btn-nav">🏠 Dashboard</a>
                <a href="logout.php" class="btn-nav">Sair</a>
            </div>
        </div>
    </header>
    
    <main class="main">
        <!-- Estatísticas Financeiras -->
        <div class="stats-grid">
            <div class="stat-card recebido">
                <div class="icon">💚</div>
                <div class="number"><?= formatarDinheiro($stats['total_recebido']) ?></div>
                <div class="label">Total Recebido</div>
            </div>
            <div class="stat-card pendente">
                <div class="icon">⏳</div>
                <div class="number"><?= formatarDinheiro($stats['total_pendente']) ?></div>
                <div class="label">Pendente</div>
            </div>
            <div class="stat-card atrasado">
                <div class="icon">⚠️</div>
                <div class="number"><?= formatarDinheiro($stats['total_atrasado']) ?></div>
                <div class="label">Atrasado</div>
            </div>
            <div class="stat-card total">
                <div class="icon">📊</div>
                <div class="number"><?= $stats['pagamentos_mes'] ?></div>
                <div class="label">Pagamentos do Mês</div>
            </div>
        </div>
        
        <!-- Filtros -->
        <form class="filters" method="GET">
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
            <button type="submit" class="btn-filter">🔍 Filtrar</button>
            <a href="financeiro.php" class="btn-clear">✕ Limpar</a>
        </form>
        
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
</body>
</html>
