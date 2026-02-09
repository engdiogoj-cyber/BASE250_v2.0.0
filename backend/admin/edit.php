<?php
/**
 * BASE250 - Editar Apartamento
 * 
 * Formulário completo para edição de apartamento
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

requireAuth();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header('Location: index.php');
    exit;
}

$pdo = getDBConnection();

// Busca apartamento
$stmt = $pdo->prepare("
    SELECT * FROM apartamentos WHERE id = ?
");
$stmt->execute([$id]);
$apartamento = $stmt->fetch();

if (!$apartamento) {
    header('Location: index.php');
    exit;
}

// Decodifica JSON
$apartamento['features'] = json_decode($apartamento['features'] ?? '[]', true) ?: [];
$apartamento['galeria_fotos'] = json_decode($apartamento['galeria_fotos'] ?? '[]', true) ?: [];

$success = '';
$error = '';

// Processa formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $numero = sanitizeInput($_POST['numero']);
        $tipo = sanitizeInput($_POST['tipo']);
        $metragem = (float)$_POST['metragem'];
        $quartos = (int)$_POST['quartos'];
        $banheiros = (int)$_POST['banheiros'];
        $preco = (float)$_POST['preco'];
        $descricao = sanitizeInput($_POST['descricao']);
        
        // Features
        $features = [];
        if (isset($_POST['features']) && is_array($_POST['features'])) {
            foreach ($_POST['features'] as $feature) {
                $feature = sanitizeInput($feature);
                if (!empty($feature)) {
                    $features[] = $feature;
                }
            }
        }
        
        // Galeria de fotos
        $galeria = [];
        if (isset($_POST['galeria_fotos']) && is_array($_POST['galeria_fotos'])) {
            foreach ($_POST['galeria_fotos'] as $foto) {
                $foto = trim($foto);
                if (!empty($foto)) {
                    $galeria[] = $foto;
                }
            }
        }
        
        // Update no banco
        $stmt = $pdo->prepare("
            UPDATE apartamentos 
            SET numero = ?, tipo = ?, metragem = ?, quartos = ?, banheiros = ?, 
                preco = ?, descricao = ?, features = ?, galeria_fotos = ?
            WHERE id = ?
        ");
        
        $stmt->execute([
            $numero,
            $tipo,
            $metragem,
            $quartos,
            $banheiros,
            $preco,
            $descricao,
            json_encode($features, JSON_UNESCAPED_UNICODE),
            json_encode($galeria, JSON_UNESCAPED_UNICODE),
            $id
        ]);
        
        $success = 'Apartamento atualizado com sucesso!';
        
        // Recarrega dados
        $stmt = $pdo->prepare("SELECT * FROM apartamentos WHERE id = ?");
        $stmt->execute([$id]);
        $apartamento = $stmt->fetch();
        $apartamento['features'] = json_decode($apartamento['features'] ?? '[]', true) ?: [];
        $apartamento['galeria_fotos'] = json_decode($apartamento['galeria_fotos'] ?? '[]', true) ?: [];
        
    } catch (Exception $e) {
        $error = 'Erro ao atualizar: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Apartamento - BASE250 Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <style>
        :root {
            --azul: #16697A;
            --azul-claro: #489FB5;
            --verde: #16a34a;
            --vermelho: #dc2626;
            --cinza-bg: #f4f6f8;
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
        
        .btn-back {
            padding: 10px 20px;
            background: var(--azul);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background: #114d5a;
        }
        
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 14px;
        }
        
        .alert.success {
            background: #d1fae5;
            color: var(--verde);
            border-left: 4px solid var(--verde);
        }
        
        .alert.error {
            background: #fee;
            color: var(--vermelho);
            border-left: 4px solid var(--vermelho);
        }
        
        .form-section {
            margin-bottom: 35px;
        }
        
        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--azul);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--cinza-bg);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            color: #333;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--azul);
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .dynamic-list {
            margin-top: 10px;
        }
        
        .list-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .list-item input {
            flex: 1;
        }
        
        .btn-remove {
            padding: 12px 20px;
            background: var(--vermelho);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .btn-remove:hover {
            background: #b91c1c;
        }
        
        .btn-add {
            padding: 10px 18px;
            background: var(--verde);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .btn-add:hover {
            background: #15803d;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px solid var(--cinza-bg);
        }
        
        .btn-submit {
            flex: 1;
            padding: 16px;
            background: linear-gradient(135deg, var(--azul) 0%, var(--azul-claro) 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(22, 105, 122, 0.3);
        }
        
        .btn-cancel {
            padding: 16px 30px;
            background: #f0f0f0;
            color: #333;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover {
            background: #e0e0e0;
        }
        
        .gallery-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .gallery-preview img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e1e8ed;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-edit"></i> Editar Apartamento</h1>
        <a href="index.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
    
    <div class="container">
        <div class="form-card">
            <?php if ($success): ?>
                <div class="alert success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <!-- Informações Básicas -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-info-circle"></i> Informações Básicas
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="numero">Número *</label>
                            <input 
                                type="text" 
                                id="numero" 
                                name="numero" 
                                value="<?php echo htmlspecialchars($apartamento['numero']); ?>"
                                required
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="tipo">Tipo *</label>
                            <select id="tipo" name="tipo" required>
                                <option value="Studio" <?php echo $apartamento['tipo'] === 'Studio' ? 'selected' : ''; ?>>Studio</option>
                                <option value="Loft" <?php echo $apartamento['tipo'] === 'Loft' ? 'selected' : ''; ?>>Loft</option>
                                <option value="Apartamento" <?php echo $apartamento['tipo'] === 'Apartamento' ? 'selected' : ''; ?>>Apartamento</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="metragem">Metragem (m²) *</label>
                            <input 
                                type="number" 
                                id="metragem" 
                                name="metragem" 
                                step="0.01"
                                value="<?php echo $apartamento['metragem']; ?>"
                                required
                            >
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="quartos">Quartos *</label>
                            <input 
                                type="number" 
                                id="quartos" 
                                name="quartos" 
                                min="0"
                                value="<?php echo $apartamento['quartos']; ?>"
                                required
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="banheiros">Banheiros *</label>
                            <input 
                                type="number" 
                                id="banheiros" 
                                name="banheiros" 
                                min="0"
                                value="<?php echo $apartamento['banheiros']; ?>"
                                required
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="preco">Preço (R$) *</label>
                            <input 
                                type="number" 
                                id="preco" 
                                name="preco" 
                                step="0.01"
                                value="<?php echo $apartamento['preco']; ?>"
                                required
                            >
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea 
                            id="descricao" 
                            name="descricao"
                        ><?php echo htmlspecialchars($apartamento['descricao']); ?></textarea>
                    </div>
                </div>
                
                <!-- Features -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-list"></i> Características
                    </div>
                    
                    <div class="dynamic-list" id="features-list">
                        <?php foreach ($apartamento['features'] as $feature): ?>
                            <div class="list-item">
                                <input 
                                    type="text" 
                                    name="features[]" 
                                    value="<?php echo htmlspecialchars($feature); ?>"
                                    placeholder="Ex: Geladeira, Ar condicionado"
                                >
                                <button type="button" class="btn-remove" onclick="removeItem(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" class="btn-add" onclick="addFeature()">
                        <i class="fas fa-plus"></i> Adicionar Característica
                    </button>
                </div>
                
                <!-- Galeria de Fotos -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-images"></i> Galeria de Fotos
                    </div>
                    
                    <div class="dynamic-list" id="gallery-list">
                        <?php foreach ($apartamento['galeria_fotos'] as $foto): ?>
                            <div class="list-item">
                                <input 
                                    type="url" 
                                    name="galeria_fotos[]" 
                                    value="<?php echo htmlspecialchars($foto); ?>"
                                    placeholder="https://i.ibb.co/..."
                                >
                                <button type="button" class="btn-remove" onclick="removeItem(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" class="btn-add" onclick="addPhoto()">
                        <i class="fas fa-plus"></i> Adicionar Foto
                    </button>
                    
                    <?php if (!empty($apartamento['galeria_fotos'])): ?>
                        <div class="gallery-preview">
                            <?php foreach ($apartamento['galeria_fotos'] as $foto): ?>
                                <img src="<?php echo htmlspecialchars($foto); ?>" alt="Preview">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-actions">
                    <a href="index.php" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function removeItem(button) {
            button.parentElement.remove();
        }
        
        function addFeature() {
            const list = document.getElementById('features-list');
            const div = document.createElement('div');
            div.className = 'list-item';
            div.innerHTML = `
                <input 
                    type="text" 
                    name="features[]" 
                    placeholder="Ex: Geladeira, Ar condicionado"
                >
                <button type="button" class="btn-remove" onclick="removeItem(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            list.appendChild(div);
        }
        
        function addPhoto() {
            const list = document.getElementById('gallery-list');
            const div = document.createElement('div');
            div.className = 'list-item';
            div.innerHTML = `
                <input 
                    type="url" 
                    name="galeria_fotos[]" 
                    placeholder="https://i.ibb.co/..."
                >
                <button type="button" class="btn-remove" onclick="removeItem(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            list.appendChild(div);
        }
    </script>
</body>
</html>
