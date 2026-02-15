<header class="header">
    <div class="header-left">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="logo-container">
            <div class="logo-icon">
                <div class="bar bar-1"></div>
                <div class="bar bar-2"></div>
                <div class="bar bar-3"></div>
            </div>
            <div class="logo-text">
                <span class="brand">BASE250</span>
                <span class="tagline">Gestão de Imóveis</span>
            </div>
        </div>
    </div>
    
    <div class="header-right">
        <div class="header-datetime">
            <span>📅</span>
            <span id="datetime">Carregando...</span>
        </div>
        
        <div class="header-user">
            <div class="user-avatar"><?= strtoupper(substr($_SESSION['user']['name'] ?? 'ED', 0, 2)) ?></div>
            <div class="user-info">
                <span class="user-name"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Eng. Diogo') ?></span>
                <span class="user-role"><?= htmlspecialchars($_SESSION['user']['role'] ?? 'Administrador') ?></span>
            </div>
        </div>
    </div>
</header>
