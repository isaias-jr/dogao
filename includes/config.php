<?php
// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'dogao');
define('DB_USER', 'root');
define('DB_PASS', '');

// Inicia a sessão
session_start();

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERRO: Não foi possível conectar. " . $e->getMessage());
}

// Função para verificar se o usuário está logado
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Função para verificar se o usuário é admin
function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

// Função para redirecionar usuários não logados
function redirectIfNotLoggedIn($url = 'login.php') {
    if (!isLoggedIn()) {
        header("Location: $url");
        exit();
    }
}

// Função para redirecionar usuários não admin
function redirectIfNotAdmin($url = '../index.php') {
    if (!isAdmin()) {
        header("Location: $url");
        exit();
    }
}
?>