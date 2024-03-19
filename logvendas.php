<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log de Vendas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h2>Log de Vendas</h2>
    <table>
        <tr>
            <th>Produto</th>
            <th>Quantidade Vendida</th>
            <th>Numero de Serie</th>
            <th>Comprador</th>
            <th>RA</th>
            <th>Valor / Forma pgto.</th>
            <th>Observação</th>
            <th>Data da Venda</th>
        </tr>
        <?php
        include 'config.php';

        $sql = "SELECT produtos.nome AS produto, log_vendas.quantidade_vendida, log_vendas.numeroserie, log_vendas.comprador, log_vendas.ra, log_vendas.forma_pagamento, log_vendas.observacao, log_vendas.data_venda 
        FROM log_vendas 
        INNER JOIN produtos ON log_vendas.produto_id = produtos.id 
        ORDER BY log_vendas.data_venda DESC";


        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['produto'] . "</td>";
                echo "<td>" . $row['quantidade_vendida'] . "</td>"; // Corrigido para acessar a coluna 'quantidade_vendida'
                echo "<td>" . $row['numeroserie'] . "</td>";
                echo "<td>" . $row['comprador'] . "</td>";
                echo "<td>" . $row['ra'] . "</td>";
                echo "<td>" . $row['forma_pagamento'] . "</td>";
                echo "<td>" . $row['observacao'] . "</td>";
                echo "<td>" . $row['data_venda'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Nenhum registro encontrado.</td></tr>";
        }

        $conn->close();
        ?>
    </table>
</body>
</html>
