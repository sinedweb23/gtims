<?php
include '../config.php';

// Verifica se um centro de custo foi selecionado
if (!isset($_GET['id_centro_custos'])) {
    echo "Nenhum centro de custos selecionado.";
    exit();
}

$id_centro_custos = $_GET['id_centro_custos'];

// Consulta para obter as informações do centro de custo selecionado
$sql_centro_custos = "SELECT * FROM centro_custos WHERE id = $id_centro_custos";
$result_centro_custos = $conn->query($sql_centro_custos);
$centro_custos = $result_centro_custos->fetch_assoc();

// Consulta para obter as despesas do centro de custo
$sql_despesas = "SELECT SUM(valor) AS total_despesas FROM despesas WHERE id_centro_custos = $id_centro_custos";
$result_despesas = $conn->query($sql_despesas);
$total_despesas = $result_despesas->fetch_assoc()['total_despesas'];

// Consulta para obter o orçamento do centro de custo
$sql_orcamento = "SELECT orcamento FROM orcamento_area WHERE id_centro_custos = $id_centro_custos";
$result_orcamento = $conn->query($sql_orcamento);
$orcamento = $result_orcamento->fetch_assoc()['orcamento'];

// Calcula o saldo
$saldo = $orcamento - $total_despesas;

// Consulta para obter a lista de todos os centros de custo
$sql_centro_custos_dropdown = "SELECT id, nome FROM centro_custos";
$result_centro_custos_dropdown = $conn->query($sql_centro_custos_dropdown);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações do Centro de Custos</title>
    <!-- Inclua a biblioteca Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
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
        canvas {
            margin: 0 auto;
            display: block;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <h1>Informações do Centro de Custos</h1>

    <!-- Formulário suspenso para selecionar outro centro de custo -->
    <form action="centro_custos_info.php" method="get">
        <label for="id_centro_custos">Selecione um Centro de Custos:</label>
        <select name="id_centro_custos" id="id_centro_custos">
            <?php
            if ($result_centro_custos_dropdown->num_rows > 0) {
                while($row = $result_centro_custos_dropdown->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'";
                    if ($row["id"] == $id_centro_custos) {
                        echo " selected"; // Seleciona o centro de custo atual
                    }
                    echo ">" . $row["nome"] . "</option>";
                }
            } else {
                echo "<option value=''>Nenhum centro de custos encontrado</option>";
            }
            ?>
        </select>
        <button type="submit">Selecionar</button>
    </form>

    <h2><?php echo $centro_custos['nome']; ?></h2>
    <p><strong>Responsável:</strong> <?php echo $centro_custos['responsavel']; ?></p>
    <p><strong>Descrição:</strong> <?php echo $centro_custos['descricao']; ?></p>
    <h3>Orçamento: R$ <?php echo number_format($orcamento, 2, ',', '.'); ?></h3>
    <h3>Total de Despesas: R$ <?php echo number_format($total_despesas, 2, ',', '.'); ?></h3>
    <h3>Saldo: R$ <?php echo number_format($saldo, 2, ',', '.'); ?></h3>

    <!-- Gráfico de pizza para mostrar as informações -->
    <canvas id="myPieChart" width="400" height="400"></canvas>

    <script>
        // Obtém os dados do PHP
        var orcamento = <?php echo $orcamento; ?>;
        var totalDespesas = <?php echo $total_despesas; ?>;
        var saldo = <?php echo $saldo; ?>;

        // Configuração do gráfico de pizza
        var ctx = document.getElementById('myPieChart').getContext('2d');
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Orçamento', 'Despesas', 'Saldo'],
                datasets: [{
                    label: 'Valores',
                    data: [orcamento, totalDespesas, saldo],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>

    <!-- Tabela com a lista de despesas do centro de custo -->
    <h2>Despesas</h2>
    <table>
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql_despesas_list = "SELECT * FROM despesas WHERE id_centro_custos = $id_centro_custos";
            $result_despesas_list = $conn->query($sql_despesas_list);
            if ($result_despesas_list->num_rows > 0) {
                while ($row = $result_despesas_list->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["descricao"] . "</td>";
                    echo "<td>R$ " . number_format($row["valor"], 2, ',', '.') . "</td>";
                    echo "<td>" . $row["data"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Nenhuma despesa registrada.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <p><a href="dashboard.php">Voltar para o Painel Administrativo</a></p>
</body>
</html>
