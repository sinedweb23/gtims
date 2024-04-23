<?php
// Inclui o arquivo de configuração do banco de dados
require_once('config.php');

// Consulta o banco de dados para obter os chamados fechados
$sql = "SELECT c.id, c.nome AS solicitante, st.Setor AS nome_setor, d.nome AS nome_defeito, d.prioridade, c.observacao, c.status, c.data_abertura
        FROM chamados c
        INNER JOIN setor st ON c.SetorID = st.SetorID
        INNER JOIN defeitos d ON c.id_defeito = d.id
        ORDER BY c.data_abertura DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Chamados</title>
    <!-- Link para o Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Histórico de Chamados Fechados</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Setor</th>
                    <th>Defeito</th>
                    <th>Prioridade</th>
                    <th>Observação</th>
                    <th>Status</th>
                    <th>Data de Abertura</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verifica se a consulta retornou resultados
                if ($result->num_rows > 0) {
                    // Exibe os chamados fechados em uma tabela
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row["id"]."</td>";
                        echo "<td>".$row["nome_setor"]."</td>";
                        echo "<td>".$row["nome_defeito"]."</td>";
                        echo "<td>".$row["prioridade"]."</td>";
                        echo "<td>".$row["observacao"]."</td>";
                        echo "<td>".$row["status"]."</td>";
                        echo "<td>".$row["data_abertura"]."</td>";
                        echo "</tr>";
                    }
                } else {
                    // Se não houver chamados fechados, exibe uma mensagem
                    echo "<tr><td colspan='7'>Nenhum chamado fechado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
