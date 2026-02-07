<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="/assets/images/logo.png" alt="BASE250">
        <div class="brand-title">BASE250</div>
    </div>
    
    <div class="sidebar-content">
        <!-- 1️⃣ PAINEL GERAL -->
        <div class="nav-section active open" data-section="geral">
            <div class="nav-section-header" onclick="toggleSection('geral')">
                <div class="nav-section-icon">📊</div>
                <div class="nav-section-info">
                    <div class="nav-section-title">Painel Geral</div>
                    <div class="nav-section-desc">Visão macro do sistema</div>
                </div>
                <span class="nav-section-arrow">▼</span>
            </div>
            <div class="nav-section-items">
                <a class="nav-item active" href="/" onclick="navigateTo('dashboard', this); return false;">
                    <span class="nav-item-icon">🏠</span>
                    Dashboard
                </a>
                <a class="nav-item" href="/status-sistema" onclick="navigateTo('status-sistema', this); return false;">
                    <span class="nav-item-icon">⚡</span>
                    Status do Sistema
                </a>
            </div>
        </div>
        
        <!-- 2️⃣ PAINEL ADMINISTRATIVO -->
        <div class="nav-section" data-section="admin">
            <div class="nav-section-header" onclick="toggleSection('admin')">
                <div class="nav-section-icon">⚙️</div>
                <div class="nav-section-info">
                    <div class="nav-section-title">Administrativo</div>
                    <div class="nav-section-desc">Gestão operacional</div>
                </div>
                <span class="nav-badge">3</span>
                <span class="nav-section-arrow">▼</span>
            </div>
            <div class="nav-section-items">
                <a class="nav-item" href="/admin/cadastro-inquilinos" onclick="navigateTo('cadastro-inquilinos', this); return false;">
                    <span class="nav-item-icon">👤</span>
                    Cadastro de Inquilinos
                    <span class="nav-badge warning">3</span>
                </a>
                <a class="nav-item" href="/admin/aprovacoes" onclick="navigateTo('aprovacoes', this); return false;">
                    <span class="nav-item-icon">✅</span>
                    Aprovação / Reprovação
                </a>
                <a class="nav-item" href="/admin/documentos" onclick="navigateTo('checklist', this); return false;">
                    <span class="nav-item-icon">📋</span>
                    Checklist de Documentos
                </a>
            </div>
        </div>
        
        <!-- 3️⃣ PAINEL FINANCEIRO -->
        <div class="nav-section" data-section="financeiro">
            <div class="nav-section-header" onclick="toggleSection('financeiro')">
                <div class="nav-section-icon">💰</div>
                <div class="nav-section-info">
                    <div class="nav-section-title">Financeiro</div>
                    <div class="nav-section-desc">Controle de pagamentos</div>
                </div>
                <span class="nav-badge error">2</span>
                <span class="nav-section-arrow">▼</span>
            </div>
            <div class="nav-section-items">
                <a class="nav-item" href="/financeiro/pagamentos" onclick="navigateTo('pagamentos', this); return false;">
                    <span class="nav-item-icon">💳</span>
                    Tabela de Pagamentos
                    <span class="nav-badge error">2</span>
                </a>
                <a class="nav-item" href="/financeiro/comprovantes" onclick="navigateTo('comprovantes', this); return false;">
                    <span class="nav-item-icon">📎</span>
                    Upload de Comprovantes
                </a>
                <a class="nav-item" href="/financeiro/relatorios" onclick="navigateTo('resumo-mensal', this); return false;">
                    <span class="nav-item-icon">📅</span>
                    Resumo Mensal
                </a>
            </div>
        </div>
        
        <!-- 4️⃣ ÁREA DO INQUILINO -->
        <div class="nav-section" data-section="inquilino">
            <div class="nav-section-header" onclick="toggleSection('inquilino')">
                <div class="nav-section-icon">👥</div>
                <div class="nav-section-info">
                    <div class="nav-section-title">Área do Inquilino</div>
                    <div class="nav-section-desc">Portal do usuário</div>
                </div>
                <span class="nav-section-arrow">▼</span>
            </div>
            <div class="nav-section-items">
                <a class="nav-item" href="/inquilino/meus-dados" onclick="navigateTo('meus-dados', this); return false;">
                    <span class="nav-item-icon">📋</span>
                    Meus Dados
                </a>
                <a class="nav-item" href="/inquilino/contrato" onclick="navigateTo('meu-contrato', this); return false;">
                    <span class="nav-item-icon">📄</span>
                    Contrato
                </a>
                <a class="nav-item" href="/inquilino/pagamentos" onclick="navigateTo('meus-pagamentos', this); return false;">
                    <span class="nav-item-icon">💰</span>
                    Pagamentos
                </a>
            </div>
        </div>
        
        <!-- 5️⃣ CENTRO DE CONFIGURAÇÕES -->
        <div class="nav-section" data-section="config">
            <div class="nav-section-header" onclick="toggleSection('config')">
                <div class="nav-section-icon">🔧</div>
                <div class="nav-section-info">
                    <div class="nav-section-title">Configurações</div>
                    <div class="nav-section-desc">Administração do sistema</div>
                </div>
                <span class="nav-section-arrow">▼</span>
            </div>
            <div class="nav-section-items">
                <a class="nav-item" href="/config/templates" onclick="navigateTo('versoes-contrato', this); return false;">
                    <span class="nav-item-icon">📑</span>
                    Versões de Contrato
                </a>
                <a class="nav-item" href="/config/backup" onclick="navigateTo('backup', this); return false;">
                    <span class="nav-item-icon">💾</span>
                    Backup e Restauração
                </a>
            </div>
        </div>
    </div>
    
    <div class="sidebar-footer">
        <div class="sidebar-footer-info">
            BASE250 v2.0<br>
            © 2025 - Todos os direitos reservados
        </div>
    </div>
</aside>
