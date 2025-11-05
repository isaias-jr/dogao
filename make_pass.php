<?php

// Função para criar uma senha em formato bcrypt
function criarSenhabcrypt($senha) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    return $senha_hash;
}

// Exemplo de uso
$senha = "gabriel123"; // Sua senha
$senha_hash = criarSenhabcrypt($senha);
echo "Senha criptografada: " . $senha_hash;

?>
