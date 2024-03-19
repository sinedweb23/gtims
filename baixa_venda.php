<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dar Baixa em Venda</title>
    <style>
        /* Estilos CSS aqui */
    </style>
</head>
<body>
    <h2>Dar Baixa em Venda</h2>
    <form action="processar_venda.php" method="POST">
        <label for="produto">Produto:</label>
        <select id="produto" name="produto">
            <?php
            include 'config.php';
            // Consulta SQL para recuperar todos os produtos cadastrados
            $sql = "SELECT id, nome, numeroserie FROM produtos";
            $resultado = $conn->query($sql);
            // Loop através dos resultados da consulta para exibir os produtos em um menu suspenso
            while ($row = $resultado->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "' data-numeroserie='" . $row['numeroserie'] . "'>" . $row['nome'] . "</option>";
            }
            // Liberar resultado
            $resultado->free();
            ?>
        </select><br>
        
        <input type="hidden" id="numeroserie" name="numeroserie">
        
        <label for="quantidade">Quantidade Vendida:</label>
        <input type="number" id="quantidade" name="quantidade" min="1" required><br>
        
        <label for="comprador">Nome do Comprador:</label>
        <input type="text" id="comprador" name="comprador" required><br>
        
        <label for="ra">RA:</label>
        <input type="text" id="ra" name="ra" required><br>
        
        <label for="forma_pagamento">Forma de Pagamento:</label>
        <input type="text" id="forma_pagamento" name="forma_pagamento" required><br>
        
        <label for="observacao">Observação:</label>
        <textarea id="observacao" name="observacao"></textarea><br>
        
        <input type="submit" value="Dar Baixa">
    </form>
    <script>
        // Função para atualizar o campo hidden com o número de série selecionado
        function atualizarNumeroSerie() {
            var select = document.getElementById("produto");
            var numeroserieInput = document.getElementById("numeroserie");
            var numeroserieSelecionado = select.options[select.selectedIndex].getAttribute("data-numeroserie");
            numeroserieInput.value = numeroserieSelecionado;
        }

        // Chama a função quando o valor do menu suspenso for alterado
        document.getElementById("produto").addEventListener("change", atualizarNumeroSerie);
        
        // Chama a função uma vez para garantir que o campo hidden seja preenchido inicialmente
        atualizarNumeroSerie();
    </script>
</body>
</html>
