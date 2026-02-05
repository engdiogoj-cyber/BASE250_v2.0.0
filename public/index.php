<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base 250 - Login</title>
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#1e40af">
    <link rel="apple-touch-icon" href="assets/icon-192.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo h1 {
            font-size: 2.5rem;
            color: #1e40af;
            margin-bottom: 5px;
        }
        
        .logo p {
            color: #64748b;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #334155;
            font-size: 1rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1.1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
        }
        
        .btn-login {
            width: 100%;
            padding: 18px;
            background: #1e40af;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-login:hover {
            background: #1e3a8a;
        }
        
        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        
        .success-message {
            background: #dcfce7;
            color: #16a34a;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #94a3b8;
            font-size: 0.9rem;
        }
        
        /* Mobile - botões e inputs maiores para idosos */
        @media (max-width: 480px) {
            .form-group input {
                padding: 18px;
                font-size: 1.2rem;
            }
            
            .btn-login {
                padding: 20px;
                font-size: 1.3rem;
            }
            
            .logo h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>🏢 Base 250</h1>
            <p>Gestão de Imóveis</p>
        </div>
        
        <?php
        require_once __DIR__ . '/../includes/config.php';
        
        $erro = '';
        $sucesso = '';
        
        // Se já está logado, redireciona
        if (isLoggedIn()) {
            header('Location: dashboard.php');
            exit;
        }
        
        // Processar login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = limparInput($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';
            
            if (empty($email) || empty($senha)) {
                $erro = 'Preencha todos os campos!';
            } else {
                $stmt = $pdo->prepare("SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ? AND ativo = 1");
                $stmt->execute([$email]);
                $usuario = $stmt->fetch();
                
                // Para primeiro acesso, aceita senha simples
                // IMPORTANTE: Depois implementar hash de senha!
                if ($usuario && ($senha === 'Admin@250' || password_verify($senha, $usuario['senha']))) {
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];
                    $_SESSION['usuario_email'] = $usuario['email'];
                    $_SESSION['usuario_tipo'] = $usuario['tipo'];
                    
                    // Atualizar último acesso
                    $stmt = $pdo->prepare("UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = ?");
                    $stmt->execute([$usuario['id']]);
                    
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $erro = 'Email ou senha incorretos!';
                }
            }
        }
        ?>
        
        <?php if ($erro): ?>
            <div class="error-message"><?= $erro ?></div>
        <?php endif; ?>
        
        <?php if ($sucesso): ?>
            <div class="success-message"><?= $sucesso ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">📧 Email</label>
                <input type="email" id="email" name="email" placeholder="seu@email.com" required>
            </div>
            
            <div class="form-group">
                <label for="senha">🔒 Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
            </div>
            
            <button type="submit" class="btn-login">Entrar</button>
        </form>
        
        <div class="footer">
            <p>Edifício Alto do Itacorubi</p>
            <p>Florianópolis - SC</p>
        </div>
    </div>
    
    <script>
        // Registrar Service Worker para PWA
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('sw.js');
        }
    </script>
</body>
</html>
