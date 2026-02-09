<?php
/**
 * BASE250 - Página Pública de Apartamentos
 * 
 * Página modernizada com dados dinâmicos do banco de dados
 * Mantém TODAS as funcionalidades de galeria existentes
 */

// Busca apartamentos do banco de dados
require_once __DIR__ . '/../../backend/config/database.php';

try {
    $pdo = getDBConnection();
    
    $stmt = $pdo->query("
        SELECT 
            id, numero, tipo, metragem, quartos, banheiros, preco, status,
            descricao, features, galeria_fotos, andar
        FROM apartamentos
        WHERE 1=1
        ORDER BY CAST(numero AS UNSIGNED) ASC, numero ASC
    ");
    
    $apartamentos = $stmt->fetchAll();
    
    // Decodifica JSON
    foreach ($apartamentos as &$apt) {
        $apt['features'] = json_decode($apt['features'] ?? '[]', true) ?: [];
        $apt['galeria_fotos'] = json_decode($apt['galeria_fotos'] ?? '[]', true) ?: [];
    }
    
} catch (Exception $e) {
    error_log("Erro ao carregar apartamentos: " . $e->getMessage());
    $apartamentos = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="Apartamentos para alugar em Florianópolis - BASE250" name="description"/>
    <title>BASE250 - Apartamentos para Alugar em Florianópolis</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="True" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <style>
        :root { 
            --cor-primaria: #16697A;
            --azul: #16697A;
            --cinza-bg: #f4f6f8;
            --verde: #16a34a;
            --vermelho: #dc2626;
        }

        * {margin: 0; padding: 0; box-sizing: border-box;}
        
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif; 
            background: linear-gradient(354deg,#16697A 26%, rgba(87, 199, 133, 1) 100%); 
            min-height: 100vh;
        }
        
        .container {max-width: 1200px; margin: 0 auto; padding: 20px;}
        
        header {text-align: center; color: white; padding: 60px 20px 40px;}
        header h1 {font-size: 56px; margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);}
        header .subtitle {font-size: 24px; opacity: 0.95; margin-bottom: 10px;}
        header .location {font-size: 16px; opacity: 0.85; margin-top: 15px;}
        
        .contact-info {
            background: rgba(255,255,255,0.2); 
            backdrop-filter: blur(10px); 
            border-radius: 15px; 
            padding: 20px; 
            margin: 30px auto; 
            max-width: 600px; 
            color: White; 
            display: flex; 
            justify-content: space-around; 
            flex-wrap: wrap; 
            gap: 15px;
        }
        .contact-info a {
            color: white; 
            text-decoration: none; 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            transition: transform 0.3s ease;
        }
        .contact-info a:hover {transform: scale(1.1);}
        
        .apartments-grid {
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); 
            gap: 30px; 
            padding: 20px 0;
        }
        
        .apartment-card {
            background: white; 
            border-radius: 20px; 
            overflow: hidden; 
            box-shadow: 0 15px 40px rgba(0,0,0,0.4); 
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }
        .apartment-card:hover {
            transform: translateY(-15px); 
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
        
        .apartment-gallery {
            position: relative; 
            height: 300px; 
            overflow: hidden; 
            background: #f0f0f0;
        }
        
        .gallery-images {
            display: flex; 
            transition: transform 0.5s ease; 
            height: 100%;
        }
        .gallery-images img {
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            flex-shrink: 0;
        }
        
        .status-badge {
            position: absolute; 
            top: 20px; 
            left: 20px; 
            color: white; 
            padding: 10px 20px; 
            border-radius: 25px; 
            font-weight: bold; 
            font-size: 14px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.3); 
            z-index: 5;
        }
        .status-badge.disponivel {background: var(--verde);}
        .status-badge.alugado {background: var(--vermelho);}
        
        .apartment-info {padding: 30px;}
        .apartment-title {font-size: 28px; color: #333; margin-bottom: 15px; font-weight: 600;}
        
        .apartment-details {
            display: flex; 
            gap: 20px; 
            margin: 20px 0; 
            flex-wrap: wrap; 
            color: #666;
        }
        .detail-item {display: flex; align-items: center; gap: 8px; font-size: 15px;}
        .detail-item i {color: #3D57FF; font-size: 18px;}
        
        .price-section {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); 
            padding: 20px; 
            border-radius: 15px; 
            margin: 20px 0;
        }
        .price {font-size: 32px; color: #3D57FF; font-weight: bold; margin-bottom: 10px;}
        .price-detail {font-size: 14px; color: #666; margin: 5px 0;}
        
        .rent-button {
            display: block; 
            width: 100%; 
            padding: 18px; 
            background: linear-gradient(135deg, #3D57FF 0%, #2a3eb8 100%); 
            color: white; 
            text-align: center; 
            text-decoration: none; 
            border-radius: 12px; 
            font-weight: bold; 
            font-size: 16px; 
            transition: all 0.3s ease; 
            box-shadow: 0 5px 20px rgba(61, 87, 255, 0.4); 
            letter-spacing: 1px;
        }
        .rent-button:hover {
            transform: translateY(-3px); 
            box-shadow: 0 8px 25px rgba(61, 87, 255, 0.6);
        }
        
        .location-info {
            color: #666; 
            margin-bottom: 20px; 
            font-size: 14px; 
            display: flex; 
            align-items: center; 
            gap: 8px;
        }
        .location-info i {color: #13628A;}
        
        .features-list {list-style: none; margin: 20px 0;}
        .features-list li {
            padding: 8px 0; 
            color: #666; 
            display: flex; 
            align-items: center; 
            gap: 10px;
        }
        .features-list li:before {
            content: "✓"; 
            color: #28a745; 
            font-weight: bold; 
            font-size: 18px;
        }
        
        /* Controles da Galeria */
        .gallery-btn {
            position: absolute; 
            top: 50%; 
            transform: translateY(-50%); 
            background: rgba(0,0,0,0.5); 
            color: white; 
            border: none; 
            width: 40px; 
            height: 40px; 
            border-radius: 50%; 
            cursor: pointer; 
            font-size: 18px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            transition: all 0.3s ease; 
            z-index: 10;
        }
        .gallery-btn:hover {
            background: rgba(0,0,0,0.8); 
            transform: translateY(-50%) scale(1.1);
        }
        .gallery-btn.prev {left: 10px;}
        .gallery-btn.next {right: 10px;}
        
        .gallery-indicators {
            position: absolute; 
            bottom: 15px; 
            left: 50%; 
            transform: translateX(-50%); 
            display: flex; 
            gap: 8px; 
            z-index: 10;
        }
        .indicator {
            width: 10px; 
            height: 10px; 
            border-radius: 50%; 
            background: rgba(255,255,255,0.5); 
            cursor: pointer; 
            transition: all 0.3s ease;
        }
        .indicator.active {
            background: white; 
            width: 30px; 
            border-radius: 5px;
        }
        
        .photo-counter {
            position: absolute; 
            top: 15px; 
            right: 15px; 
            background: rgba(0,0,0,0.6); 
            color: white; 
            padding: 5px 12px; 
            border-radius: 20px; 
            font-size: 12px; 
            z-index: 10;
        }
        
        /* Apartamento Alugado */
        .apartment-card.alugado {
            opacity: 0.55; 
            pointer-events: none;
        }
        .apartment-card.alugado .price-section, 
        .apartment-card.alugado .rent-button {
            display: none;
        }
        .apartment-card.alugado:hover {
            transform: none; 
            box-shadow: 0 15px 40px rgba(0,0,0,0.4);
        }
        
        .info-section {
            background: white; 
            border-radius: 20px; 
            padding: 40px; 
            margin: 40px 0; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .info-section h2 {color: #13628A; margin-bottom: 20px; font-size: 28px;}
        .info-section p {color: #666; line-height: 1.8; margin-bottom: 15px;}
        
        footer {text-align: center; color: white; padding: 60px 20px 40px; margin-top: 60px;}
        footer .footer-content {
            background: rgba(255,255,255,0.1); 
            backdrop-filter: blur(10px); 
            border-radius: 15px; 
            padding: 30px; 
            max-width: 600px; 
            margin: 0 auto;
        }
        footer p {margin: 10px 0; font-size: 16px;}
        footer .social-links {margin-top: 20px; display: flex; justify-content: center; gap: 20px;}
        footer .social-links a {color: white; font-size: 24px; transition: transform 0.3s ease;}
        footer .social-links a:hover {transform: scale(1.2);}
        
        @media (max-width: 768px) {
            .apartments-grid {grid-template-columns: 1fr;}
            header h1 {font-size: 36px;}
            header .subtitle {font-size: 18px;}
            .apartment-title {font-size: 24px;}
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>BASE250</h1>
            <p class="subtitle">Apartamentos para Alugar em Florianópolis</p>
            <p class="location"><i class="fas fa-map-marker-alt"></i> Servidão Joaquim Soares, 250 - Itacorubi</p>
            <div class="contact-info">
                <a href="tel:+5548999352627"><i class="fas fa-phone"></i> (48) 99935-2627</a>
                <a href="https://wa.me/5548999352627"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                <a href="mailto:floripamoso@gmail.com"><i class="fas fa-envelope"></i> Email</a>
            </div>
        </header>
        
        <div class="info-section">
            <h2>Sobre os Apartamentos</h2>
            <p>Localizados próximo à Epagri e ao Campus de Agronomia da UFSC, os apartamentos oferecem conforto e segurança em Florianópolis.</p>
            <p><strong>🎯 Para facilitar usamos a caução no valor de um aluguel, que é devolvido na devolução das chaves.</strong></p>
            <ul class="features-list">
                <li>Contrato de 30 meses podendo romper após 12 meses.</li>
                <li>A partir desse período contrato renova automaticamente, para saída aviso prévio de 30 dias.</li>
                <li>Incluso: Aluguel, água, gás e internet.</li>
                <li>Não incluso: Luz (medidor individual), IPTU e garagem (taxa mensal).</li>
                <li>Processo de locação rápido e transparente</li>
            </ul>
        </div>
        
        <div class="apartments-grid">
            <?php foreach ($apartamentos as $index => $apt): ?>
                <div class="apartment-card <?php echo $apt['status']; ?>">
                    <div class="apartment-gallery" data-gallery="<?php echo $index; ?>">
                        <div class="photo-counter">
                            <i class="fas fa-camera"></i> 
                            <span class="current">1</span>/<span class="total"><?php echo count($apt['galeria_fotos']); ?></span>
                        </div>
                        <span class="status-badge <?php echo $apt['status']; ?>">
                            <?php echo $apt['status'] === 'disponivel' ? '✅ DISPONÍVEL' : '✗ ALUGADO'; ?>
                        </span>
                        <div class="gallery-images">
                            <?php foreach ($apt['galeria_fotos'] as $foto): ?>
                                <img alt="<?php echo htmlspecialchars($apt['tipo'] . ' ' . $apt['numero']); ?>" 
                                     src="<?php echo htmlspecialchars($foto); ?>"/>
                            <?php endforeach; ?>
                        </div>
                        <button class="gallery-btn prev" onclick="changeImage(<?php echo $index; ?>, -1)">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="gallery-btn next" onclick="changeImage(<?php echo $index; ?>, 1)">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <div class="gallery-indicators">
                            <?php foreach ($apt['galeria_fotos'] as $photoIndex => $foto): ?>
                                <span class="indicator <?php echo $photoIndex === 0 ? 'active' : ''; ?>" 
                                      onclick="goToImage(<?php echo $index; ?>, <?php echo $photoIndex; ?>)"></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="apartment-info">
                        <h2 class="apartment-title"><?php echo htmlspecialchars($apt['tipo'] . ' ' . $apt['numero']); ?></h2>
                        <div class="location-info">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Itacorubi, Florianópolis/SC</span>
                        </div>
                        <div class="apartment-details">
                            <span class="detail-item">
                                <i class="fas fa-ruler-combined"></i> <?php echo $apt['metragem']; ?>m²
                            </span>
                            <?php if ($apt['quartos'] > 0): ?>
                                <span class="detail-item">
                                    <i class="fas fa-bed"></i> <?php echo $apt['quartos']; ?> quarto<?php echo $apt['quartos'] > 1 ? 's' : ''; ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($apt['banheiros'] > 0): ?>
                                <span class="detail-item">
                                    <i class="fas fa-bath"></i> <?php echo $apt['banheiros']; ?> banheiro<?php echo $apt['banheiros'] > 1 ? 's' : ''; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($apt['features'])): ?>
                            <ul class="features-list">
                                <?php foreach (array_slice($apt['features'], 0, 4) as $feature): ?>
                                    <li><?php echo htmlspecialchars($feature); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <?php if ($apt['status'] === 'disponivel'): ?>
                            <div class="price-section">
                                <div class="price">R$ <?php echo number_format($apt['preco'], 0, ',', '.'); ?><span style="font-size: 18px;">/mês</span></div>
                                <p class="price-detail">Sem taxa de condomínio</p>
                                <p class="price-detail">+ IPTU</p>
                            </div>
                            <a class="rent-button" href="https://docs.google.com/forms/d/e/1FAIpQLSe5MCN3g-EAgep88ovBmDUa14JrNhetjzGcq6EoaYy-W33_0w/viewform" target="_blank">
                                <i class="fas fa-home"></i> QUERO ALUGAR
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="info-section">
            <h2>Como Alugar?</h2>
            <p><strong>É simples e rápido!</strong></p>
            <ol style="color: #666; line-height: 2; margin-left: 20px;">
                <li><strong>Escolha o apartamento</strong> e clique em "QUERO ALUGAR"</li>
                <li><strong>Preencha o formulário</strong> com seus dados</li>
                <li><strong>Aguarde análise</strong> (até 48 horas)</li>
                <li><strong>Receba o contrato</strong> por email</li>
                <li><strong>Assine digitalmente</strong> via Gov.br</li>
                <li><strong>Retire as chaves</strong> e seja bem-vindo!</li>
            </ol>
            <p style="margin-top: 20px;"><strong>📄 Documentos necessários:</strong></p>
            <ul class="features-list">
                <li>RG e CPF</li>
                <li>Comprovante de residência</li>
                <li>Foto 3x4</li>
            </ul>
        </div>
        
        <footer>
            <div class="footer-content">
                <p><strong>📍 Endereço</strong></p>
                <p>Servidão Joaquim Soares, nº 250<br/>Bairro Itacorubi - Florianópolis/SC</p>
                <p style="margin-top: 20px;"><strong>📞 Contato</strong></p>
                <p>Telefone/WhatsApp: (48) 99935-2627</p>
                <p>Email: floripamoso@gmail.com</p>
                <div class="social-links">
                    <a href="https://wa.me/5548999352627" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    <a href="mailto:floripamoso@gmail.com"><i class="fas fa-envelope"></i></a>
                </div>
                <p style="margin-top: 30px; font-size: 12px; opacity: 0.7;">© 2025 Base 250 - Todos os direitos reservados</p>
            </div>
        </footer>
    </div>
    
    <script>
        // Mantém TODO o JavaScript original da galeria
        let currentPositions = new Array(<?php echo count($apartamentos); ?>).fill(0);
        
        function changeImage(galleryIndex, direction) {
            const gallery = document.querySelectorAll('.apartment-gallery')[galleryIndex];
            const images = gallery.querySelector('.gallery-images');
            const totalImages = images.children.length;
            const indicators = gallery.querySelectorAll('.indicator');
            const counter = gallery.querySelector('.current');
            
            currentPositions[galleryIndex] += direction;
            
            if (currentPositions[galleryIndex] < 0) {
                currentPositions[galleryIndex] = totalImages - 1;
            } else if (currentPositions[galleryIndex] >= totalImages) {
                currentPositions[galleryIndex] = 0;
            }
            
            const offset = -currentPositions[galleryIndex] * 100;
            images.style.transform = `translateX(${offset}%)`;
            
            indicators.forEach((ind, index) => {
                ind.classList.toggle('active', index === currentPositions[galleryIndex]);
            });
            
            counter.textContent = currentPositions[galleryIndex] + 1;
        }
        
        function goToImage(galleryIndex, imageIndex) {
            const gallery = document.querySelectorAll('.apartment-gallery')[galleryIndex];
            const images = gallery.querySelector('.gallery-images');
            const indicators = gallery.querySelectorAll('.indicator');
            const counter = gallery.querySelector('.current');
            
            currentPositions[galleryIndex] = imageIndex;
            const offset = -imageIndex * 100;
            images.style.transform = `translateX(${offset}%)`;
            
            indicators.forEach((ind, index) => {
                ind.classList.toggle('active', index === imageIndex);
            });
            
            counter.textContent = imageIndex + 1;
        }
    </script>
</body>
</html>
