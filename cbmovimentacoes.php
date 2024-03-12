<?php
include 'config.php';

$filtro_chromebook = isset($_POST['filtro_chromebook']) ? $_POST['filtro_chromebook'] : '';
$filtro_data = isset($_POST['filtro_data']) ? $_POST['filtro_data'] : '';

// Processa o formulário de filtro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $filtro_chromebook = $_POST['filtro_chromebook'];
    $filtro_data = $_POST['filtro_data'];

    // Ajusta o formato da data para corresponder ao formato armazenado no banco de dados
    $filtro_data = date('Y-m-d', strtotime($filtro_data));

    $sql = "SELECT m.ID, c.Nome AS NomeChromebook, m.DataEmprestimo, m.HoraEmprestimo, m.Usuario, m.DataHoraDevolucao 
            FROM cbmovimentacoes m 
            INNER JOIN chromebooks c ON m.ChromebookID = c.ID 
            WHERE c.Nome LIKE '%$filtro_chromebook%' AND DATE(m.DataEmprestimo) = '$filtro_data'";
} else {
    // Seleciona todos os registros se nenhum filtro for aplicado
    $sql = "SELECT m.ID, c.Nome AS NomeChromebook, m.DataEmprestimo, m.HoraEmprestimo, m.Usuario, m.DataHoraDevolucao 
            FROM cbmovimentacoes m 
            INNER JOIN chromebooks c ON m.ChromebookID = c.ID";
}

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
    </style>
</head>
<body>
    <h1>Registro de Movimentações de chromebooks</h1>

    <!-- Formulário de filtro -->
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
</body>
</html>
