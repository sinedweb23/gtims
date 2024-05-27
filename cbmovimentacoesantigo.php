<?php
include 'config.php';

$filtro_chromebook = isset($_POST['filtro_chromebook']) ? $_POST['filtro_chromebook'] : '';
$filtro_data = isset($_POST['filtro_data']) ? $_POST['filtro_data'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Página atual

$limit = 30; // Número de registros por página
$offset = ($page - 1) * $limit; // Offset para consulta no banco de dados

// Processa o formulário de filtro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $filtro_chromebook = $_POST['filtro_chromebook'];
    $filtro_data = $_POST['filtro_data'];
}

// Constrói a consulta SQL com paginação e filtros
$sql = "SELECT m.ID, c.Nome AS NomeChromebook, m.DataEmprestimo, m.HoraEmprestimo, m.Usuario, m.DataHoraDevolucao 
        FROM cbmovimentacoes m 
        INNER JOIN chromebooks c ON m.ChromebookID = c.ID 
        WHERE 1=1";

if (!empty($filtro_chromebook)) {
    $sql .= " AND c.Nome = '$filtro_chromebook'";
}

if (!empty($filtro_data)) {
    $filtro_data = date('Y-m-d', strtotime($filtro_data));
    $sql .= " AND DATE(m.DataEmprestimo) = '$filtro_data'";
}

// Ordenação por data ascendente
$sql .= " ORDER BY m.DataEmprestimo ASC ";
// Adiciona limit e offset para paginação
$sql .= " LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Movimentações de Ativos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .pagination {
            margin-top: 20px;
        }
        .pagination a {
            display: inline-block;
            padding: 5px 10px;
            border: 1px solid #ddd;
            margin-right: 5px;
            text-decoration: none;
        }
        .pagination a.active {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Registro de Movimentações de chromebooks</h1>

    <!-- Formulário de  filtro -->
    <form method="post">
        <div class="form-group">
            <label for="filtro_chromebook">Filtrar por Chromebook:</label>
            <select id="filtro_chromebook" name="filtro_chromebook">
                <option value="">Selecionar Chromebook</option>
                <?php
                // Consulta para obter os nomes dos chromebooks
                $sql_chromebooks = "SELECT Nome FROM chromebooks";
                $result_chromebooks = $conn->query($sql_chromebooks);
                if ($result_chromebooks->num_rows > 0) {
                    while ($row = $result_chromebooks->fetch_assoc()) {
                        $selected = ($row['Nome'] == $filtro_chromebook) ? 'selected' : '';
                        echo "<option value='".$row['Nome']."' $selected>".$row['Nome']."</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="filtro_data">Filtrar por Data de Empréstimo:</label>
            <input type="date" id="filtro_data" name="filtro_data" value="<?= htmlspecialchars($filtro_data) ?>">
        </div>
        <button type="submit">Filtrar</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Chromebook</th>
                <th>Data do Empréstimo</th>
                <th>Hora do Empréstimo</th>
                <th>Usuário</th>
                <th>Data e Hora da Devolução</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['ID']."</td>";
                    echo "<td>".$row['NomeChromebook']."</td>";
                    echo "<td>".$row['DataEmprestimo']."</td>";
                    echo "<td>".$row['HoraEmprestimo']."</td>";
                    echo "<td>".$row['Usuario']."</td>";
                    echo "<td>".$row['DataHoraDevolucao']."</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Nenhum registro encontrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Adiciona links de paginação -->
    <?php
    $total_pages_sql = "SELECT COUNT(*) AS total FROM cbmovimentacoes";
    $result_total = $conn->query($total_pages_sql);
    $total_rows = $result_total->fetch_assoc()['total'];
    $total_pages = ceil($total_rows / $limit);

    echo "<div class='pagination'>";
    echo "<span>Páginas:</span>";
    for ($i = 1; $i <= $total_pages; $i++) {
        $active_class = ($i == $page) ? 'active' : '';
        echo "<a class='$active_class' href='?page=$i'>$i</a>";
    }
    echo "</div>";
    ?>

</body>
</html>
