<?php
require_once 'includes/config.php';
redirectIfNotAdmin();

// Processar ações (cadastrar, editar, excluir)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Diretório de upload
        $uploadDir = 'uploads/';

        if ($action === 'cadastrar') {
            // Cadastrar novo produto
            $nome = trim($_POST['nome']);
            $descricao = trim($_POST['descricao']);
            $preco = floatval(str_replace(',', '.', $_POST['preco']));
            $categoria = trim($_POST['categoria']);
            $disponivel = isset($_POST['disponivel']) ? 1 : 0;

            $nomeArquivo = '';

            // Upload da imagem
            if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['imagem']['tmp_name'];
                $fileName = basename($_FILES['imagem']['name']);
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                $novoNome = uniqid() . '.' . $ext;

                if(move_uploaded_file($tmpName, $uploadDir . $novoNome)) {
                    $nomeArquivo = $novoNome;
                } else {
                    $_SESSION['error_message'] = "Erro ao enviar a imagem.";
                    header("Location: produtos.php");
                    exit();
                }
            }

            $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, imagem, categoria, disponivel) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$nome, $descricao, $preco, $nomeArquivo, $categoria, $disponivel])) {
                $_SESSION['success_message'] = "Produto cadastrado com sucesso!";
            } else {
                $_SESSION['error_message'] = "Erro ao cadastrar produto.";
            }
        }
        elseif ($action === 'editar') {
            // Editar produto existente
            $id = intval($_POST['id']);
            $nome = trim($_POST['nome']);
            $descricao = trim($_POST['descricao']);
            $preco = floatval(str_replace(',', '.', $_POST['preco']));
            $categoria = trim($_POST['categoria']);
            $disponivel = isset($_POST['disponivel']) ? 1 : 0;

            // Buscar dados atuais do produto
            $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
            $stmt->execute([$id]);
            $produto_editar = $stmt->fetch(PDO::FETCH_ASSOC);

            $nomeArquivo = $produto_editar['imagem'] ?? '';

            // Upload da nova imagem, se enviada
            if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['imagem']['tmp_name'];
                $fileName = basename($_FILES['imagem']['name']);
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                $novoNome = uniqid() . '.' . $ext;

                if(move_uploaded_file($tmpName, $uploadDir . $novoNome)) {
                    $nomeArquivo = $novoNome;
                } else {
                    $_SESSION['error_message'] = "Erro ao enviar a imagem.";
                    header("Location: produtos.php");
                    exit();
                }
            }

            $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, imagem = ?, categoria = ?, disponivel = ? WHERE id = ?");
            if ($stmt->execute([$nome, $descricao, $preco, $nomeArquivo, $categoria, $disponivel, $id])) {
                $_SESSION['success_message'] = "Produto atualizado com sucesso!";
            } else {
                $_SESSION['error_message'] = "Erro ao atualizar produto.";
            }
        }
        elseif ($action === 'excluir') {
            // Excluir produto
            $id = intval($_POST['id']);
            
            $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
            if ($stmt->execute([$id])) {
                $_SESSION['success_message'] = "Produto excluído com sucesso!";
            } else {
                $_SESSION['error_message'] = "Erro ao excluir produto.";
            }
        }

        header("Location: produtos.php");
        exit();
    }
}

// Buscar produtos
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY nome");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inclui o header do admin
require_once 'header.php';
?>

<section class="section" style="margin-top: 100px;">
    <div class="container">
        <h2 class="section-title">Gerenciar Produtos</h2>
        
        <div class="admin-content">
            <!-- Formulário de cadastro/edição -->
            <div class="admin-form">
                <h3><?php echo isset($_GET['editar']) ? 'Editar Produto' : 'Cadastrar Novo Produto'; ?></h3>
                
                <?php
                $produto_editar = null;
                if (isset($_GET['editar'])) {
                    $id_editar = intval($_GET['editar']);
                    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
                    $stmt->execute([$id_editar]);
                    $produto_editar = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                ?>
                
                <form method="POST" enctype="multipart/form-data" class="form">
                    <?php if ($produto_editar): ?>
                        <input type="hidden" name="action" value="editar">
                        <input type="hidden" name="id" value="<?php echo $produto_editar['id']; ?>">
                    <?php else: ?>
                        <input type="hidden" name="action" value="cadastrar">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="nome">Nome do Produto:</label>
                        <input type="text" id="nome" name="nome" value="<?php echo $produto_editar ? htmlspecialchars($produto_editar['nome']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao">Descrição:</label>
                        <textarea id="descricao" name="descricao" rows="3" required><?php echo $produto_editar ? htmlspecialchars($produto_editar['descricao']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="preco">Preço (R$):</label>
                        <input type="text" id="preco" name="preco" value="<?php echo $produto_editar ? number_format($produto_editar['preco'], 2, ',', '') : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="imagem">Imagem do Produto:</label>
                        <input type="file" id="imagem" name="imagem" accept="image/*" onchange="previewImage(event)" <?php echo $produto_editar ? '' : 'required'; ?>>

                        <!-- Preview da imagem -->
                        <div id="preview-container" style="margin-top:10px;">
                            <?php if ($produto_editar && $produto_editar['imagem']): ?>
                                <img id="preview" src="uploads/<?php echo htmlspecialchars($produto_editar['imagem']); ?>" alt="Prévia" width="120" style="border:1px solid #ccc; border-radius:5px; padding:2px;">
                            <?php else: ?>
                                <img id="preview" src="" alt="Prévia" width="120" style="display:none; border:1px solid #ccc; border-radius:5px; padding:2px;">
                            <?php endif; ?>
                        </div>
                    </div>

                    <script>
                    function previewImage(event) {
                        const preview = document.getElementById('preview');
                        const file = event.target.files[0];
                        if (file) {
                            preview.src = URL.createObjectURL(file);
                            preview.style.display = "block";
                        } else {
                            preview.src = "";
                            preview.style.display = "none";
                        }
                    }
                    </script>

                    
                    <div class="form-group">
                        <label for="categoria">Categoria:</label>
                        <input type="text" id="categoria" name="categoria" value="<?php echo $produto_editar ? htmlspecialchars($produto_editar['categoria']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="disponivel" <?php echo ($produto_editar && $produto_editar['disponivel']) || !$produto_editar ? 'checked' : ''; ?>>
                            Disponível para venda
                        </label>
                    </div>
                    
                    <button type="submit" class="btn"><?php echo $produto_editar ? 'Atualizar' : 'Cadastrar'; ?></button>
                    
                    <?php if ($produto_editar): ?>
                        <a href="produtos.php" class="btn btn-outline">Cancelar</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <!-- Lista de produtos -->
            <div class="admin-list">
                <h3>Produtos Cadastrados</h3>
                
                <?php if (count($produtos) > 0): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Preço</th>
                                <th>Categoria</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produtos as $produto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($produto['categoria']); ?></td>
                                <td><?php echo $produto['disponivel'] ? 'Disponível' : 'Indisponível'; ?></td>
                                <td class="actions">
                                    <a href="produtos.php?editar=<?php echo $produto['id']; ?>" class="btn-edit">Editar</a>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="excluir">
                                        <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                                        <button type="submit" class="btn-delete" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Nenhum produto cadastrado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
require_once 'footer.php';
?>
