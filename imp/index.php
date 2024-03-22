<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Impressões</title>
</head>
<body>
    <h1>Registro de Impressões</h1>
    <form action="processar_contagem.php" method="post">
        <?php
        // Incluir arquivo de configuração
        require_once('config.php');

        // Consultar as impressoras
        $sql = "SELECT id, nome FROM impressoras";
        $result = $conn->query($sql);

        // Exibir um campo para cada impressora
        while ($row = $result->fetch_assoc()) {
            $impressora_id = $row['id'];
            $impressora_nome = $row['nome'];
            echo "<label for='total_impressas_$impressora_id'>$impressora_nome:</label>";
            echo "<input type='number' id='total_impressas_$impressora_id' name='total_impressas[$impressora_id]' required><br>";
        }

        // Fechar conexão com o banco de dados
        $conn->close();
        ?>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
