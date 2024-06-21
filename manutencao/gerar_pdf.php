<?php
require_once('config1.php');

// Verifica se as datas foram fornecidas
if (isset($_GET['data_inicial']) && isset($_GET['data_final'])) {
    $dataInicial = $_GET['data_inicial'];
    $dataFinal = $_GET['data_final'];

    // Formata as datas para o formato correto do banco de dados
    $dataInicialFormatada = date('Y-m-d 00:00:00', strtotime($dataInicial));
    $dataFinalFormatada = date('Y-m-d 23:59:59', strtotime($dataFinal));

    // Consulta SQL para obter os chamados fechados no intervalo de datas fornecido
    $sql = "SELECT c.id, s.nome AS nome_sala, d.nome AS nome_defeito, d.prioridade, c.observacao, c.status, 
            DATE_FORMAT(c.data_abertura, '%d/%m/%y %H:%i') AS data_abertura_formatada, 
            DATE_FORMAT(c.data_fechamento, '%d/%m/%y %H:%i') AS data_fechamento_formatada, c.solucao
            FROM chamados c
            INNER JOIN salas s ON c.id_sala = s.id
            INNER JOIN defeitos d ON c.id_defeito = d.id
            WHERE c.status = 'Fechado' 
            AND c.data_fechamento IS NOT NULL 
            AND c.data_fechamento BETWEEN '$dataInicialFormatada' AND '$dataFinalFormatada'
            ORDER BY c.data_fechamento DESC";

    $result = $conn->query($sql);

    if (!$result) {
        echo "Erro na consulta: " . $conn->error;
    }
    ?>

    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Relatório de Chamados Fechados</title>
        <!-- Link para o Bootstrap CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <script>
            window.onload = function() {
                window.print();
            }
        </script>
    </head>
    <body>
        <div class="container mt-5">
            <h3 class="mb-4">Relatório de Chamados Fechados</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Local</th>
                        <th>Problema</th>
                        <th>Prioridade</th>
                        <th>Observação</th>
                        <th>Status</th>
                        <th>Data de Abertura</th>
                        <th>Data de Fechamento</th>
                        <th>Solução</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>".$row["nome_sala"]."</td>";
                            echo "<td>".$row["nome_defeito"]."</td>";
                            echo "<td>".$row["prioridade"]."</td>";
                            echo "<td>".$row["observacao"]."</td>";
                            echo "<td>".$row["status"]."</td>";
                            echo "<td>".$row["data_abertura_formatada"]."</td>";
                            echo "<td>".$row["data_fechamento_formatada"]."</td>";
                            echo "<td>".$row["solucao"]."</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Nenhum chamado fechado encontrado neste período.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
    </html>

    <?php
} else {
    echo "Por favor, selecione um intervalo de datas.";
}
?>
