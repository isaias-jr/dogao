<?php
require_once 'includes/config.php';

// Destroi a sessão
session_destroy();

// Redireciona para a página inicial
header("Location: index.php");
exit();