<div class="content-header">
    <h1 class="page-title">
        <span class="icon">🏠</span>
        Dashboard
    </h1>
    <p class="page-subtitle">Visão geral do sistema BASE250</p>
</div>

<!-- Cards de Estatísticas -->
<div class="card-grid">
    <div class="card card-stat">
        <div class="card-stat-header">
            <div class="card-stat-icon">👥</div>
        </div>
        <div class="card-stat-label">Total de Inquilinos</div>
        <div class="card-stat-value"><?= $totalTenants ?? 18 ?></div>
        <div class="card-stat-info">
            <span class="card-stat-trend up">↑ 2</span>
            <span>novos este mês</span>
        </div>
    </div>
    
    <div class="card card-stat success">
        <div class="card-stat-header">
            <div class="card-stat-icon">✅</div>
        </div>
        <div class="card-stat-label">Pagamentos em Dia</div>
        <div class="card-stat-value"><?= $totalPayments ?? 15 ?></div>
        <div class="card-stat-info">
            <span>de 18 total</span>
        </div>
    </div>
    
    <div class="card card-stat warning">
        <div class="card-stat-header">
            <div class="card-stat-icon">⏳</div>
        </div>
        <div class="card-stat-label">Aprovações Pendentes</div>
        <div class="card-stat-value"><?= $pendingApprovals ?? 3 ?></div>
        <div class="card-stat-info">
            <span>requer atenção</span>
        </div>
    </div>
    
    <div class="card card-stat error">
        <div class="card-stat-header">
            <div class="card-stat-icon">⚠️</div>
        </div>
        <div class="card-stat-label">Pagamentos Atrasados</div>
        <div class="card-stat-value"><?= $overduePayments ?? 2 ?></div>
        <div class="card-stat-info">
            <span>ação necessária</span>
        </div>
    </div>
</div>

<!-- Notificações Recentes -->
<div class="panel">
    <div class="panel-header">
        <span class="icon">🔔</span>
        Notificações Recentes
    </div>
    <div class="panel-body">
        <?php if (!empty($recentNotifications)): ?>
            <?php foreach ($recentNotifications as $notification): ?>
                <div class="alert alert-<?= $notification['type'] ?>">
                    <span class="alert-icon">
                        <?php if ($notification['type'] === 'success'): ?>✅<?php endif; ?>
                        <?php if ($notification['type'] === 'warning'): ?>⚠️<?php endif; ?>
                        <?php if ($notification['type'] === 'error'): ?>❌<?php endif; ?>
                    </span>
                    <div>
                        <strong><?= htmlspecialchars($notification['title']) ?></strong><br>
                        <?= htmlspecialchars($notification['message']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted text-center">Nenhuma notificação no momento.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Atalhos Rápidos -->
<div class="content-header">
    <h2 class="page-title" style="font-size: 20px; margin-top: 32px;">
        <span class="icon">⚡</span>
        Atalhos Rápidos
    </h2>
</div>

<div class="card-grid">
    <div class="card card-shortcut">
        <div class="card-shortcut-icon">👤</div>
        <div class="card-shortcut-info">
            <h4>Novo Cadastro</h4>
            <p>Cadastrar novo inquilino</p>
        </div>
        <span class="card-shortcut-arrow">→</span>
    </div>
    
    <div class="card card-shortcut">
        <div class="card-shortcut-icon">💳</div>
        <div class="card-shortcut-info">
            <h4>Registrar Pagamento</h4>
            <p>Lançar novo pagamento</p>
        </div>
        <span class="card-shortcut-arrow">→</span>
    </div>
    
    <div class="card card-shortcut">
        <div class="card-shortcut-icon">📄</div>
        <div class="card-shortcut-info">
            <h4>Gerar Contrato</h4>
            <p>Emitir novo contrato</p>
        </div>
        <span class="card-shortcut-arrow">→</span>
    </div>
    
    <div class="card card-shortcut">
        <div class="card-shortcut-icon">📊</div>
        <div class="card-shortcut-info">
            <h4>Relatórios</h4>
            <p>Visualizar relatórios</p>
        </div>
        <span class="card-shortcut-arrow">→</span>
    </div>
</div>
