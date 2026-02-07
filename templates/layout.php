<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'BASE250 - Gestão de Imóveis' ?></title>
    
    <!-- Design System BASE250 v1.0 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/design-system.css">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?= \BASE250\Middleware\CSRF::token() ?>">
</head>
<body>
    <div class="app-container">
        <?php include __DIR__ . '/components/header.php'; ?>
        
        <div class="app-main">
            <?php include __DIR__ . '/components/sidebar.php'; ?>
            
            <main class="content">
                <?= $content ?? '' ?>
            </main>
        </div>
    </div>
    
    <script src="/assets/js/app.js"></script>
    <?= $additionalScripts ?? '' ?>
</body>
</html>
