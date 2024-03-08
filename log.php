<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log de Movimentações</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Log de Movimentações</h1>

    <form method="GET">
        <label for="filtro">Filtrar:</label>
        <select name="filtro" id="filtro">
            <option value="todos">Todos</option>
            <option value="entrada">Entradas</option>
            <option value="saida">Saídas</option>
        </select>
        <button type="submit">Filtrar</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Tipo de Movimentação</th>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Data</th>
                <th>Responsável</th>
                <th>Setor</th>
                <th>Origem</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';
            
            $sql = "SELECT m.TipoMovimentacao, p.NomeProduto, m.Quantidade, m.Data, m.Responsavel, IFNULL(c.Setor, '') AS Setor, m.Origem
                    FROM movimentacao m
                    INNER JOIN produto p ON m.ProdutoID = p.ProdutoID
                    LEFT JOIN setor c ON m.SetorID = c.SetorID";

            if ($filtro == 'entrada') {
                $sql .= " WHERE m.TipoMovimentacao = 'Entrada'";
            } elseif ($filtro == 'saida') {
                $sql .= " WHERE m.TipoMovimentacao = 'Saída'";
            }

            $sql .= " ORDER BY m.MovimentacaoID DESC";




            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row_count = 0;
                while ($row = $result->fetch_assoc()) {
                    $row_count++;
                    $class = ($row_count % 2 == 0) ? 'even' : 'odd';
                    echo "<tr class='$class'>";
                    echo "<td>" . $row['TipoMovimentacao'] . "</td>";
                    echo "<td>" . $row['NomeProduto'] . "</td>";
                    echo "<td>" . $row['Quantidade'] . "</td>";
                    echo "<td>" . $row['Data'] . "</td>";
                    echo "<td>" . $row['Responsavel'] . "</td>";
                    echo "<td>" . $row['Setor'] . "</td>";
                    echo "<td>" . $row['Origem'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Nenhuma movimentação encontrada.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
