<?php
require_once 'includes/config.php';

// Processar formulário de cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $telefone = trim($_POST['telefone']);
    
    // Validações
    $errors = [];
    
    if (empty($nome)) {
        $errors[] = "Nome é obrigatório";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email validos";
    }
    
    if (strlen($senha) < 6) {
        $errors[] = "Senha deve ter pelo menos 6 caracteres";
    }
    
    // Verificar se email já existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = "Email já cadastrado";
    }
    
    // Se não há erros, cadastrar usuário
    if (empty($errors)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, sobrenome, email, senha, telefone) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$nome, $sobrenome, $email, $senha_hash, $telefone])) {
            $_SESSION['success_message'] = "Cadastro realizado com sucesso! Faça login para continuar.";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Erro ao cadastrar. Tente novamente.";
        }
    }
    
    // Se há erros, armazenar para exibir
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode("<br>", $errors);
    }
}

require_once 'header.php';
?>

    <section class="section">
        <div class="container">
            <div class="form-container">
                <h2 class="section-title">Crie sua conta</h2>
                <form method="POST" class="form">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="sobrenome">Sobrenome:</label>
                        <input type="text" id="sobrenome" name="sobrenome" value="<?php echo isset($_POST['sobrenome']) ? htmlspecialchars($_POST['sobrenome']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="senha">Senha:</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefone">Telefone:</label>
                        <input type="tel" id="telefone" name="telefone" value="<?php echo isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : ''; ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-full">Cadastrar</button>
                </form>
                
                <p class="text-center">Já tem uma conta? <a href="login.php">Faça login</a></p>
            </div>
        </div>
    </section>

<?php
require_once 'footer.php';
?>