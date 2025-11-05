<?php
session_start();
require_once 'includes/config.php';
include('header.php'); // onde está isLoggedIn()

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- A. Captura e tratamento dos dados ---
    $usuario_id = $_SESSION['user_id'] ?? null; // ID do usuário logado
    $total = trim($_POST['total'] ?? '0.00'); 
    $observacoes = trim($_POST['observacao'] ?? ''); 
    $endereco_entrega = trim($_POST['endereco'] ?? ''); 
    $datapedido = date('Y-m-d H:i:s');
    
    // --- B. Itens do carrinho ---
    $itens = isset($_POST['itens']) ? json_decode($_POST['itens'], true) : []; 

    // --- C. Validação ---
    $errors = [];
    if (empty($usuario_id)) $errors[] = "Usuário não autenticado.";
    if (empty($endereco_entrega)) $errors[] = "Endereço de entrega é obrigatório.";
    if (empty($itens)) $errors[] = "O carrinho está vazio.";

    // --- D. Inserção no banco ---
    if (empty($errors)) {
        $sql = "INSERT INTO pedidos (usuario_id, total, endereco_entrega, observacoes, data_pedido)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        try {
            if ($stmt->execute([$usuario_id, $total, $endereco_entrega, $observacoes, $datapedido])) {
                $_SESSION['success_message'] = "Pedido realizado com sucesso!";
                header("Location: index.php"); 
                exit();
            } else {
                $errors[] = "Erro ao finalizar o pedido. Tente novamente.";
            }
        } catch (PDOException $e) {
            $errors[] = "Erro do Banco de Dados: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Pedido Final</title>
  <script src="app.js" type="module" defer></script>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    .pedido-container { max-width: 600px; margin: auto; }
    ul { list-style: none; padding: 0; }
    li { margin-bottom: 10px; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
    .total { font-size: 18px; font-weight: bold; margin-top: 20px; }
    .btn-finalizar {
      background-color: green;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="pedido-container">
  <br><br>  
  <h2>Resumo do Pedido</h2>

  <?php if (!empty($errors)): ?>
    <div style="color:red;">
      <?php foreach ($errors as $erro) echo "<p>$erro</p>"; ?>
    </div>
  <?php endif; ?>

  <?php if (empty($itens)): ?>
      <p>Nenhum item no carrinho.</p>
  <?php else: ?>
      <ul>
        <?php foreach ($itens as $item): 
          list($nome, $preco) = explode('|', $item);
        ?>
          <li><?php echo htmlspecialchars($nome); ?> — R$ <?php echo htmlspecialchars($preco); ?></li>
        <?php endforeach; ?>
      </ul>

      <p class="total">Total: R$ <?php echo htmlspecialchars($total); ?></p>
      
      <form action="" method="POST"> 
        <div class="form-group">
          <label for="cep">CEP</label>
          <input type="text" id="cep" name="cep" required />
        </div>
        <div class="form-group">
          <label for="cidade">Cidade</label>
          <input type="text" id="cidade" name="cidade" required />
        </div>
        <div class="form-group">
          <label for="estado">Estado</label>
          <select id="estado" name="estado" required>
            <option value="">Selecione...</option>
            <option value="SP">São Paulo</option>
            <option value="RJ">Rio de Janeiro</option>
            <option value="MG">Minas Gerais</option>
            <!-- ... restantes ... -->
          </select>
        </div>
        <div class="form-group">
          <label for="endereco">Endereço</label>
          <input type="text" id="endereco" name="endereco" required />
        </div>
        <div class="form-group">
          <label for="bairro">Bairro</label>
          <input type="text" id="bairro" name="bairro" required />
        </div>
        
        <input type="hidden" name="itens" value='<?php echo htmlspecialchars(json_encode($itens), ENT_QUOTES, "UTF-8"); ?>'>
        <input type="hidden" name="total" value='<?php echo htmlspecialchars($total); ?>'>
        
        <button type="submit" class="btn-finalizar">Finalizar Pedido</button>
      </form>
  <?php endif; ?>
  </div>
</body>

<script>
  // Máscara de CEP
  document.getElementById('cep')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 8) {
      value = value.replace(/^(\d{5})(\d)/, '$1-$2');
    }
    e.target.value = value;
  });
</script>
</html>