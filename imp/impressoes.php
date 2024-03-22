<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Impressões</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Relatório de Impressões</h1>

    <?php
    // Incluir arquivo de configuração
    require_once('config.php');

    // Consultar as impressões por mês para cada impressora
    $sql_impressoes_por_mes = "SELECT i.nome AS impressora_nome, 
        im.mes, 
        im.ano, 
        SUM(im.quantidade) AS total_impressoes
        FROM impressoes_mensais im
        INNER JOIN impressoras i ON im.impressora_id = i.id
        GROUP BY i.nome, im.ano, im.mes
        ORDER BY i.nome, im.ano, im.mes";
    $result_impressoes_por_mes = $conn->query($sql_impressoes_por_mes);

    echo "<h2>Impressões por mês</h2>";
    echo "<table>";
    echo "<tr><th>Impressora</th><th>Mês</th><th>Ano</th><th>Total de Impressões</th></tr>";

    while ($row = $result_impressoes_por_mes->fetch_assoc()) {
        $impressora_nome = $row['impressora_nome'];
        $mes = $row['mes'];
        $ano = $row['ano'];
        $total_impressoes = $row['total_impressoes'];

        echo "<tr><td>$impressora_nome</td><td>$mes</td><td>$ano</td><td>$total_impressoes</td></tr>";
    }
    echo "</table>";

    // Consultar o total de impressões por impressora
    $sql_total_impressoes_por_impressora = "SELECT i.nome AS impressora_nome, SUM(im.quantidade) AS total_impressoes
        FROM impressoes_mensais im
        INNER JOIN impressoras i ON im.impressora_id = i.id
        GROUP BY i.nome";
    $result_total_impressoes_por_impressora = $conn->query($sql_total_impressoes_por_impressora);

    echo "<h2>Total de Impressões por Impressora</h2>";
    echo "<table>";
    echo "<tr><th>Impressora</th><th>Total de Impressões</th></tr>";

    while ($row = $result_total_impressoes_por_impressora->fetch_assoc()) {
        $impressora_nome = $row['impressora_nome'];
        $total_impressoes = $row['total_impressoes'];

        echo "<tr><td>$impressora_nome</td><td>$total_impressoes</td></tr>";
    }
    echo "</table>";

    // Fechar conexão com o banco de dados
    $conn->close();
    ?>
</body>
</html>
