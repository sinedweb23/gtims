<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Estoque - Sistema de Gestão de TI</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type=text] {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            margin-bottom: 16px;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .product-image {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>
    <h1>Consulta de Estoque</h1>
    <input type="text" id="myInput" onkeyup="filterTable()" placeholder="Buscar por qualquer coisa...">
    <button onclick="exportToCSV()">Exportar CSV</button>
    <table id="myTable">
        <tr>
            <th>Nome do Produto</th>
            <th>Imagem</th>
            <th>Valor Unitário</th>
            <th>Valor Total</th>
            <th>Categoria</th>
            <th>Número de Série</th>
            <th>Validade da Garantia</th>
            <th>Link da DANFE</th>
            <th>Estoque</th>
        </tr>
        <?php
        include 'config.php';

        $sql = "SELECT p.NomeProduto, p.Valor, p.CategoriaID, p.NumeroSerie, p.ValidadeGarantia, p.LinkDanfe, p.LinkImagem, p.Estoque, c.Categoria 
                FROM produto p
                INNER JOIN categoria c ON p.CategoriaID = c.CategoriaID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['NomeProduto'] . "</td>";
                echo "<td><img src='" . $row['LinkImagem'] . "' alt='Imagem do Produto' class='product-image'></td>";
                echo "<td>R$ " . number_format($row['Valor'], 2, ',', '.') . "</td>";
                echo "<td>R$ " . number_format($row['Valor'] * $row['Estoque'], 2, ',', '.') . "</td>";
                echo "<td>" . $row['Categoria'] . "</td>";
                echo "<td>" . $row['NumeroSerie'] . "</td>";
                echo "<td>" . $row['ValidadeGarantia'] . "</td>";
                echo "<td><a href='" . $row['LinkDanfe'] . "' target='_blank'>Abrir DANFE</a></td>";
                echo "<td>" . $row['Estoque'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>Nenhum produto encontrado</td></tr>";
        }

        $conn->close();
        ?>
    </table>

    <script>
        function filterTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td");
                for (var j = 0; j < td.length; j++) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        break;
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function exportToCSV() {
            var csv = [];
            var rows = document.querySelectorAll("table tr");
            for (var i = 0; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll("td, th");
                for (var j = 0; j < cols.length; j++)
                    row.push(cols[j].innerText);
                csv.push(row.join(","));
            }
            var csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
            var encodedUri = encodeURI(csvContent);
            var link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "estoque.csv");
            document.body.appendChild(link);
            link.click();
        }
    </script>
</body>
</html>
