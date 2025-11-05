<?php
require_once 'includes/config.php';
redirectIfNotAdmin();
?>

<?php
// Inclui o header do admin
require_once 'header.php';
?>
    <section class="section" style="margin-top: 100px;">
        <div class="container">
            <h2 class="section-title">Painel Administrativo</h2>
            
            <div class="admin-dashboard">
                <div class="dashboard-cards">
                    <div class="dashboard-card">
                        <h3>Produtos Cadastrados</h3>
                        <?php
                        $stmt = $pdo->query("SELECT COUNT(*) as total FROM produtos");
                        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                        ?>
                        <p class="number"><?php echo $total; ?></p>
                        <a href="produtos.php" class="btn">Gerenciar Produtos</a>
                    </div>
                    
                    <div class="dashboard-card">
                        <h3>Usuários Cadastrados</h3>
                        <?php
                        $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
                        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                        ?>
                        <p class="number"><?php echo $total; ?></p>
                    </div>
                    
                    <div class="dashboard-card">
                        <h3>Pedidos Hoje</h3>
                        <p class="number">0</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
require_once 'footer.php';
?>