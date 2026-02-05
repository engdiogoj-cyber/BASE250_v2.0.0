<?php
require_once __DIR__ . '/../includes/config.php';
requireLogin();

// Buscar estatísticas
$stats = [
    'total_apartamentos' => 14,
    'ocupados' => 0,
    'disponiveis' => 0,
    'manutencao' => 0
];

$stmt = $pdo->query("SELECT status, COUNT(*) as total FROM apartamentos GROUP BY status");
while ($row = $stmt->fetch()) {
    if ($row['status'] === 'ocupado') $stats['ocupados'] = $row['total'];
    if ($row['status'] === 'disponivel') $stats['disponiveis'] = $row['total'];
    if ($row['status'] === 'manutencao') $stats['manutencao'] = $row['total'];
}

// Buscar apartamentos com inquilinos
$stmt = $pdo->query("
    SELECT 
        a.id, a.numero, a.status, a.andar,
        i.nome as inquilino_nome, i.telefone as inquilino_telefone,
        c.valor_aluguel, c.data_fim
    FROM apartamentos a
    LEFT JOIN contratos c ON a.id = c.apartamento_id AND c.status = 'ativo'
    LEFT JOIN inquilinos i ON c.inquilino_id = i.id
    ORDER BY a.numero ASC
");
$apartamentos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Base 250</title>
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
        
        .header-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .btn-logout {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
        }
        
        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
        }
        
        /* Main */
        .main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card .number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-card .label {
            color: #64748b;
            font-size: 1rem;
        }
        
        .stat-card.disponivel .number { color: #16a34a; }
        .stat-card.ocupado .number { color: #2563eb; }
        .stat-card.manutencao .number { color: #f59e0b; }
        
        /* Action Buttons - GRANDES para idosos */
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .action-btn {
            background: white;
            border: none;
            padding: 25px 20px;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: #1e293b;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .action-btn .icon {
            font-size: 2.5rem;
        }
        
        .action-btn.primary {
            background: #1e40af;
            color: white;
        }
        
        /* Apartamentos Grid */
        .section-title {
            font-size: 1.3rem;
            color: #1e293b;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .apartamentos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
        }
        
        .apto-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #16a34a;
        }
        
        .apto-card.ocupado {
            border-left-color: #2563eb;
        }
        
        .apto-card.manutencao {
            border-left-color: #f59e0b;
        }
        
        .apto-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .apto-numero {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }
        
        .apto-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .apto-status.disponivel {
            background: #dcfce7;
            color: #16a34a;
        }
        
        .apto-status.ocupado {
            background: #dbeafe;
            color: #2563eb;
        }
        
        .apto-status.manutencao {
            background: #fef3c7;
            color: #f59e0b;
        }
        
        .apto-info {
            color: #64748b;
            font-size: 0.95rem;
        }
        
        .apto-info p {
            margin-bottom: 5px;
        }
        
        .apto-info strong {
            color: #1e293b;
        }
        
        .apto-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }
        
        .apto-btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }
        
        .apto-btn.whatsapp {
            background: #25d366;
            color: white;
        }
        
        .apto-btn.ver {
            background: #e2e8f0;
            color: #1e293b;
        }
        
        /* Mobile */
        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.2rem;
            }
            
            .action-btn {
                padding: 30px 20px;
                font-size: 1.2rem;
            }
            
            .action-btn .icon {
                font-size: 3rem;
            }
            
            .stat-card .number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>🏢 Base 250</h1>
            <div class="header-user">
                <span>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</span>
                <a href="logout.php" class="btn-logout">Sair</a>
            </div>
        </div>
    </header>
    
    <main class="main">
        <!-- Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card disponivel">
                <div class="number"><?= $stats['disponiveis'] ?></div>
                <div class="label">Disponíveis</div>
            </div>
            <div class="stat-card ocupado">
                <div class="number"><?= $stats['ocupados'] ?></div>
                <div class="label">Ocupados</div>
            </div>
            <div class="stat-card manutencao">
                <div class="number"><?= $stats['manutencao'] ?></div>
                <div class="label">Manutenção</div>
            </div>
            <div class="stat-card">
                <div class="number"><?= $stats['total_apartamentos'] ?></div>
                <div class="label">Total</div>
            </div>
        </div>
        
        <!-- Botões de Ação -->
        <div class="action-buttons">
            <a href="novo-contrato.php" class="action-btn primary">
                <span class="icon">📋</span>
                <span>Novo Contrato</span>
            </a>
            <a href="inquilinos.php" class="action-btn">
                <span class="icon">👥</span>
                <span>Inquilinos</span>
            </a>
            <a href="whatsapp.php" class="action-btn">
                <span class="icon">📱</span>
                <span>WhatsApp</span>
            </a>
            <a href="configuracoes.php" class="action-btn">
                <span class="icon">⚙️</span>
                <span>Configurações</span>
            </a>
        </div>
        
        <!-- Lista de Apartamentos -->
        <h2 class="section-title">🏠 Apartamentos</h2>
        <div class="apartamentos-grid">
            <?php foreach ($apartamentos as $apto): ?>
                <div class="apto-card <?= $apto['status'] ?>">
                    <div class="apto-header">
                        <span class="apto-numero">Apto <?= $apto['numero'] ?></span>
                        <span class="apto-status <?= $apto['status'] ?>">
                            <?php
                            switch ($apto['status']) {
                                case 'disponivel': echo '✅ Livre'; break;
                                case 'ocupado': echo '🔵 Ocupado'; break;
                                case 'manutencao': echo '🔧 Manutenção'; break;
                            }
                            ?>
                        </span>
                    </div>
                    
                    <div class="apto-info">
                        <?php if ($apto['inquilino_nome']): ?>
                            <p><strong>👤 <?= htmlspecialchars($apto['inquilino_nome']) ?></strong></p>
                            <?php if ($apto['inquilino_telefone']): ?>
                                <p>📞 <?= htmlspecialchars($apto['inquilino_telefone']) ?></p>
                            <?php endif; ?>
                            <?php if ($apto['valor_aluguel']): ?>
                                <p>💰 <?= formatarDinheiro($apto['valor_aluguel']) ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p>Apartamento disponível para locação</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="apto-actions">
                        <?php if ($apto['inquilino_telefone']): ?>
                            <?php 
                            $tel = preg_replace('/[^0-9]/', '', $apto['inquilino_telefone']);
                            $whatsappLink = "https://wa.me/55{$tel}";
                            ?>
                            <a href="<?= $whatsappLink ?>" target="_blank" class="apto-btn whatsapp">📱 WhatsApp</a>
                        <?php endif; ?>
                        <a href="apartamento.php?id=<?= $apto['id'] ?>" class="apto-btn ver">Ver detalhes</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
