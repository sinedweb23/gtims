<?php
include 'config.php';

// Consulta SQL para recuperar todos os produtos cadastrados
$sql = "SELECT id, nome, numeroserie FROM produtos";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dar Baixa em Venda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Dar Baixa em Venda</h2>
    <form action="processar_venda.php" method="POST">
        <label for="produto">Produto:</label>
        <select id="produto" name="produto">
            <?php
            // Loop através dos resultados da consulta para exibir os produtos em um menu suspenso
            while ($row = $resultado->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "' data-numeroserie='" . $row['numeroserie'] . "'>" . $row['nome'] . " (N. Série: " . $row['numeroserie'] . ")</option>";
            }
            ?>
        </select><br>
        
        <label for="quantidade">Quantidade Vendida:</label>
        <input type="number" id="quantidade" name="quantidade" min="1" required><br>
        
        <label for="comprador">Nome do Comprador:</label>
        <input type="text" id="comprador" name="comprador" required><br>
        
        <label for="ra">RA:</label>
        <input type="text" id="ra" name="ra" required><br>
        
        <label for="valor">Valor:</label>
        <input type="text" id="valor" name="valor" required><br>

        <label for="forma_pagamento">Valor / Forma de Pagto.:</label>
        <input type="text" id="forma_pagamento" name="forma_pagamento" required><br>
        
        <label for="observacao">Observação:</label>
        <textarea id="observacao" name="observacao"></textarea><br>
        
        <input type="submit" value="Dar Baixa">
    </form>
</body>
</html>

<?php
// Liberar resultado..
$resultado->free();

// Fechar conexão
$conn->close();
?>
