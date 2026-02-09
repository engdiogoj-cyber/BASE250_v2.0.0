<?php
/**
 * BASE250 - Dashboard Administrativo
 * 
 * Lista todos os apartamentos com opções de edição e alteração de status
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

requireAuth();

$user = getLoggedInUser();
$pdo = getDBConnection();

// Busca todos os apartamentos
$stmt = $pdo->query("
    SELECT 
        id, numero, tipo, metragem, quartos, banheiros, preco, status, 
        features, galeria_fotos, andar
    FROM apartamentos
    ORDER BY CAST(numero AS UNSIGNED) ASC, numero ASC
");
$apartamentos = $stmt->fetchAll();

// Decodifica JSON
foreach ($apartamentos as &$apt) {
    $apt['features'] = json_decode($apt['features'] ?? '[]', true) ?: [];
    $apt['galeria_fotos'] = json_decode($apt['galeria_fotos'] ?? '[]', true) ?: [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BASE250 Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <style>
        :root {
            --azul: #16697A;
            --azul-claro: #489FB5;
            --verde: #16a34a;
            --vermelho: #dc2626;
            --cinza-bg: #f4f6f8;
            --cinza-borda: #e1e8ed;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--cinza-bg);
            color: #333;
        }
        
        .header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            color: var(--azul);
            font-size: 24px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-info span {
            color: #666;
            font-size: 14px;
        }
        
        .btn-logout {
            padding: 10px 20px;
            background: var(--vermelho);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            background: #b91c1c;
            transform: translateY(-2px);
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: var(--azul);
        }
        
        .apartments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .apartment-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .apartment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        .card-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .status-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }
        
        .status-badge.disponivel {
            background: var(--verde);
        }
        
        .status-badge.alugado {
            background: var(--vermelho);
        }
        
        .card-content {
            padding: 20px;
        }
        
        .card-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        
        .card-details {
            display: flex;
            gap: 15px;
            margin: 15px 0;
            flex-wrap: wrap;
            font-size: 14px;
            color: #666;
        }
        
        .card-detail-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .card-price {
            font-size: 24px;
            font-weight: bold;
            color: var(--azul);
            margin: 15px 0;
        }
        
        .card-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .btn-edit {
            background: var(--azul);
            color: white;
        }
        
        .btn-edit:hover {
            background: #114d5a;
        }
        
        .btn-toggle {
            background: #f0f0f0;
            color: #333;
        }
        
        .btn-toggle:hover {
            background: #e0e0e0;
        }
        
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 1000;
            animation: slideIn 0.3s ease;
        }
        
        .alert.success {
            background: var(--verde);
            color: white;
        }
        
        .alert.error {
            background: var(--vermelho);
            color: white;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @media (max-width: 768px) {
            .apartments-grid {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-building"></i> BASE250 - Dashboard</h1>
        <div class="user-info">
            <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($user['nome']); ?></span>
            <a href="logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Sair
            </a>
        </div>
    </div>
    
    <div class="container">
        <div class="stats">
            <?php
            $total = count($apartamentos);
            $disponiveis = count(array_filter($apartamentos, fn($a) => $a['status'] === 'disponivel'));
            $alugados = count(array_filter($apartamentos, fn($a) => $a['status'] === 'alugado'));
            ?>
            <div class="stat-card">
                <h3>Total de Apartamentos</h3>
                <div class="number"><?php echo $total; ?></div>
            </div>
            <div class="stat-card">
                <h3>Disponíveis</h3>
                <div class="number" style="color: var(--verde);"><?php echo $disponiveis; ?></div>
            </div>
            <div class="stat-card">
                <h3>Alugados</h3>
                <div class="number" style="color: var(--vermelho);"><?php echo $alugados; ?></div>
            </div>
        </div>
        
        <div class="apartments-grid">
            <?php foreach ($apartamentos as $apt): ?>
                <div class="apartment-card">
                    <div class="card-image" style="background-image: url('<?php echo htmlspecialchars($apt['galeria_fotos'][0] ?? ''); ?>')">
                        <span class="status-badge <?php echo $apt['status']; ?>">
                            <?php echo $apt['status'] === 'disponivel' ? '✓ DISPONÍVEL' : '✗ ALUGADO'; ?>
                        </span>
                    </div>
                    <div class="card-content">
                        <div class="card-title">
                            <?php echo htmlspecialchars($apt['tipo'] . ' ' . $apt['numero']); ?>
                        </div>
                        <div class="card-details">
                            <span class="card-detail-item">
                                <i class="fas fa-ruler-combined"></i> <?php echo $apt['metragem']; ?>m²
                            </span>
                            <span class="card-detail-item">
                                <i class="fas fa-bed"></i> <?php echo $apt['quartos']; ?> quarto(s)
                            </span>
                            <span class="card-detail-item">
                                <i class="fas fa-bath"></i> <?php echo $apt['banheiros']; ?> banheiro(s)
                            </span>
                        </div>
                        <div class="card-price">
                            R$ <?php echo number_format($apt['preco'], 2, ',', '.'); ?>/mês
                        </div>
                        <div class="card-actions">
                            <a href="edit.php?id=<?php echo $apt['id']; ?>" class="btn btn-edit">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <button 
                                class="btn btn-toggle" 
                                onclick="toggleStatus(<?php echo $apt['id']; ?>, '<?php echo $apt['status']; ?>')">
                                <i class="fas fa-sync-alt"></i> 
                                <?php echo $apt['status'] === 'disponivel' ? 'Marcar Alugado' : 'Marcar Disponível'; ?>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <script>
        function showAlert(message, type) {
            const alert = document.createElement('div');
            alert.className = `alert ${type}`;
            alert.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i> ${message}`;
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 3000);
        }
        
        async function toggleStatus(id, currentStatus) {
            const newStatus = currentStatus === 'disponivel' ? 'alugado' : 'disponivel';
            
            if (!confirm(`Tem certeza que deseja marcar este apartamento como ${newStatus}?`)) {
                return;
            }
            
            try {
                const response = await fetch('/backend/api/update_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id, status: newStatus })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert('Status atualizado com sucesso!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert(data.message || 'Erro ao atualizar status', 'error');
                }
            } catch (error) {
                showAlert('Erro de conexão', 'error');
                console.error(error);
            }
        }
    </script>
</body>
</html>
