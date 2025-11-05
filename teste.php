<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Calcular Frete</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f5f5f5, #ddd);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            text-align: center;
            width: 480px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .form-row {
            display: flex;
            gap: 10px;
        }
        .form-row input[type="text"] {
            flex: 2;
            padding: 12px;
            border: 1px solid #aaa;
            border-radius: 6px;
            font-size: 16px;
            text-align: center;
        }
        .form-row button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            background: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        .form-row button:hover {
            background: #0056b3;
        }
        .resultado {
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
            font-size: 16px;
            text-align: left;
        }
        .resultado p {
            margin: 6px 0;
        }
        .resultado strong {
            color: #ff0202ff;
        }
    </style>
</head>

<body>

    <?php include "Calcular_frete.php" ?>
    <div class="container">
        <h2>Calcular Frete</h2>
        <form method="post">
            <div class="form-row">
                <input type="text" name="cep" placeholder="Digite seu CEP" maxlength="9" required>
                <button type="submit">Calcular</button>
            </div>
        </form>

        <?php if ($resultado): ?>
            <div class="resultado">
                <?php if ($endereco): ?>
                    <p><strong>Endereço:</strong> <?php echo $endereco['logradouro']; ?></p>
                    <p><strong>Bairro:</strong> <?php echo $endereco['bairro']; ?></p>
                    <p><strong>Cidade/UF:</strong> <?php echo $endereco['cidade']." - ".$endereco['uf']; ?></p>
                    <hr>
                <?php endif; ?>
                <p><strong>Região:</strong> <?php echo $resultado['regiao']; ?></p>
                <p><strong>Valor do Frete:</strong> R$ <?php echo $resultado['valor']; ?></p>
                <p id="valor-frete"><strong>Prazo de Entrega:</strong> <?php echo $resultado['prazo']; ?></p>
                <script> 
                const freteEl = document.getElementById("valor-frete");
                const texto = freteEl.textContent;
                if (texto.includes("Não")) {
                    freteEl.style.color = "black";
                    freteEl.style.fontWeight = "bold";
                }
                </script>
            </div>
        <?php endif; ?>
    </div>
    
</body>
</html>