<?php
// Inclui a configuração do banco
require_once 'includes/config.php';

// Verifica se há mensagens na sessão
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Limpa as mensagens após exibi-las
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Dogão - Lanchonete</title>
</head>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="menu">
                <div class="logo-container">
                    <div id="title"><h1>Dogão</h1></div>
                    <div class="logo-madero">
                        <img src="uploads/dog.webp" alt="Logo Dogão" class="logo-img">
                    </div>
                </div>
                <ul>
                    <li><a href="index.php" class="menu-item">Início</a></li>
                    <li><a href="index.php#cardapio" class="menu-item">Cardápio</a></li>
                    <li><a href="index.php#localizacao"class="menu-item">Localização</a></li>
                    <li><a href="https://wa.me/5514991044232" target class="menu-item">Whatsapp</a></li>
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <li><a href="dashboard.php" class="menu-item btn-admin">Painel Admin</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php" class="menu-item btn-logout">Sair</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="menu-item">Login</a></li>
                        <li><a href="cadastro.php" class="menu-item">Criar conta</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </header>

    <!-- Mensagens de alerta -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success">
            <div class="container">
                <?php echo $success_message; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-error">
            <div class="container">
                <?php echo $error_message; ?>
            </div>
        </div>
    <?php endif; ?>