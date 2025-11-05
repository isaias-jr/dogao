<?php
require_once 'includes/config.php';

// Buscar produtos do banco de dados
$stmt = $pdo->query("SELECT * FROM produtos WHERE disponivel = TRUE ORDER BY nome");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inclui o header
require_once 'header.php';
?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>O melhor DOGÃO da cidade</h2>
            <p>Experimente nossos lanches artesanais com ingredientes selecionados e sabores incríveis</p>
            <a href="#cardapio" class="btn">Ver Cardápio</a>
            <a href="#pedidofinal" class="btn">Fazer Pedido</a>
        </div>
    </section>
    <!-- Products Section -->
    <section id="cardapio" class="section">
        <div class="container">
            <div class="section-title">
              <section id="pedidofinal" class="section">
        <div class="container">
            <div class="section-title">
                <h2>Nosso Cardápio</h2>
                <p>Delicie-se com nossos lanches especiais</p>
            </div>
            <div class="products-grid">
                <?php foreach ($produtos as $produto): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                    </div>
                    <div class="product-content">
                        <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                        <p class="product-price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                        <div class="product-actions">
                        <button class="btn" onclick="adicionarAoCarrinho('<?php echo addslashes($produto['nome']); ?>', '<?php echo number_format($produto['preco'], 2, ',', '.'); ?>')">Fazer Pedido</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- localizacao Section -->
 <section id="localizacao" class="section">
        <div class="container">
            <div class="section-title">
                <h2>Localização</h2>
                <p>Nós encontre aqui</p>
            </div>
            <div class="mapBox">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d4788.096712788282!2d-48.984087300573556!3d-22.4590379913921!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94c0a0e353099547%3A0xf6b7add0d20ec63e!2sR.%20Jos%C3%A9%20de%20Rosas%2C%20460%20-%20Santa%20Angelina%2C%20Agudos%20-%20SP%2C%2017120-000!5e1!3m2!1spt-BR!2sbr!4v1760462263118!5m2!1spt-BR!2sbr" width="1080" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>


  
    <!-- Carrinho  -->
<div id="carrinho" class="carrinho-container" style="display: none;" onclick="abrirCarrinhoDetalhes()">
  <div class="bolinha-quantidade" id="contador" onclick="abrirCarrinhoDetalhes()">0</div>
  <div class="carrinho-bola">
    🛒 <!-- Você pode trocar por um ícone SVG ou imagem -->
  </div>
</div>

<div id="carrinho-detalhes" class="carrinho-detalhes" style="display: none;">
  <h3>Itens no Carrinho</h3>
  <ul id="lista-carrinho"></ul>
  <p><strong>Total:</strong> R$ <span id="total-carrinho">0,00</span></p>
  <button onclick="finalizarPedido()">Finalizar Pedido</button>
</div>

<form id="form-finalizar" action="pedido_final.php" method="POST" style="display: none;">
  <input type="hidden" name="itens" id="input-itens">
  <input type="hidden" name="total" id="input-total">
</form>


<style>
.carrinho-container {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 1000;
}

.carrinho-bola {
  width: 60px;
  height: 60px;
  background-color: black;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  color: white;
  cursor: pointer;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  position: relative;
}

.bolinha-quantidade {
  position: absolute;
  top: -10px;
  right: -10px;
  background-color: white;
  color: red;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  font-size: 14px;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}

.carrinho-detalhes {
  position: fixed;
  bottom: 100px;
  right: 20px;
  background: white;
  border: 1px solid #ccc;
  padding: 16px;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  max-height: 300px;
  overflow-y: auto;
}
.carrinho-detalhes button {
  background-color: red;
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 10px;
}
.carrinho-detalhes li {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
}
.carrinho-detalhes li button {
  background: none;
  border: none;
  color: red;
  font-weight: bold;
  cursor: pointer;
}

</style>

<script>
  let carrinho = [];
  let contador = 0;

  function mostrarCarrinho() {
    document.getElementById("carrinho").style.display = "block";
  }

  function adicionarAoCarrinho(nome, preco) {
    carrinho.push({ nome, preco });
    contador++;
    document.getElementById("contador").textContent = contador;
    atualizarListaCarrinho();
    mostrarCarrinho();
  }

  function removerItem(index) {
    carrinho.splice(index, 1);
    contador--;
    document.getElementById("contador").textContent = contador;
    atualizarListaCarrinho();
  }

  function atualizarListaCarrinho() {
    const lista = document.getElementById("lista-carrinho");
    lista.innerHTML = "";
    let total = 0;

    carrinho.forEach((item, index) => {
      const precoNum = parseFloat(item.preco.replace(',', '.'));
      total += precoNum;

      const li = document.createElement("li");
      li.innerHTML = `${item.nome} - R$ ${item.preco} <button onclick="removerItem(${index})">x</button>`;
      lista.appendChild(li);
    });

    document.getElementById("total-carrinho").textContent = total.toFixed(2).replace('.', ',');
  }

  function abrirCarrinhoDetalhes() {
    const detalhes = document.getElementById("carrinho-detalhes");
    detalhes.style.display = detalhes.style.display === "none" ? "block" : "none";
  }

  function finalizarPedido() {
    const inputItens = document.getElementById("input-itens");
    const inputTotal = document.getElementById("input-total");

    // Prepara os dados
    const itensFormatados = carrinho.map(item => `${item.nome}|${item.preco}`);
    inputItens.value = JSON.stringify(itensFormatados);

    const total = carrinho.reduce((acc, item) => acc + parseFloat(item.preco.replace(',', '.')), 0);
    inputTotal.value = total.toFixed(2);

    // Envia o formulário
    document.getElementById("form-finalizar").submit();
  }


</script>


<?php
// Inclui o footer
require_once 'footer.php';
?>