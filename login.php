<?php
require_once 'includes/config.php';

// Redirecionar se já estiver logado
if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

// Processar formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo']; // cliente ou admin
    
    // Buscar usuário
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND tipo = ?");
    $stmt->execute([$email, $tipo]);
    $usuarios = $stmt->fetch();
    
    if ($usuarios && password_verify($senha, $usuarios['senha'])) {
        // Login bem-sucedido
        $_SESSION['user_id'] = $usuarios['id'];
        $_SESSION['user_email'] = $usuarios['email'];
        $_SESSION['user_name'] = $usuarios['nome'];
        $_SESSION['user_type'] = $usuarios['tipo'];
        
        $_SESSION['success_message'] = "Login realizado com sucesso!";
        
        // Redirecionar conforme o tipo de usuário
        if ($usuarios['tipo'] === 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $_SESSION['error_message'] = "Email ou senha incorretos!";
    }
}
?>

<?php
// Inclui a configuração do banco
require_once 'includes/config.php';

// Verifica se há mensagens na sessão
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';


require_once 'header.php';
?>

    <section class="section" style="margin-top: 100px;">
        <div class="container">
            <div class="form-container">
                <h2 class="section-title">Login</h2>

                <div class="form-group">
                    <form method="POST" class="form">
                        <label for="tipo">Tipo de acesso:</label>
                        <select id="tipo" name="tipo" required>
                            <option value="cliente">Cliente</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="senha">Senha:</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>
                    
                    
                    
                    <button type="submit" class="btn btn-full">Entrar</button>
                </form>
                
                <p class="text-center">Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
            </div>
        </div>
    </section>

<?php
require_once 'footer.php';
?>