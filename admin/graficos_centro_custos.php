<?php


// Consulta para obter os dados de todos os centros de custos
$sql_all_centers = "SELECT c.id, c.nome, SUM(d.valor) AS total_despesas, o.orcamento FROM centro_custos c LEFT JOIN despesas d ON c.id = d.id_centro_custos LEFT JOIN orcamento_area o ON c.id = o.id_centro_custos GROUP BY c.id";
$result_all_centers = $conn->query($sql_all_centers);
$center_data = [];
if ($result_all_centers->num_rows > 0) {
    while ($row = $result_all_centers->fetch_assoc()) {
        $center_data[] = [
            'nome' => $row['nome'],
            'despesas' => floatval($row['total_despesas']),
            'orcamento' => floatval($row['orcamento']),
            'saldo' => floatval($row['orcamento']) - floatval($row['total_despesas'])
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráficos dos Centros de Custos</title>
    <!-- Inclua a biblioteca Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Gráficos dos Centros de Custos</h1>

    <!-- Div para o gráfico de todos os centros de custos -->
    <div style="width: 800px; margin: 20px auto;">
        <canvas id="allCentersChart"></canvas>
    </div>

    <script>
        // Obtém os dados do PHP
        var centerData = <?php echo json_encode($center_data); ?>;

        // Extrai os nomes dos centros de custos
        var centerNames = centerData.map(function(item) {
            return item.nome;
        });

        // Extrai as despesas de cada centro de custo
        var despesas = centerData.map(function(item) {
            return item.despesas;
        });

        // Extrai os orçamentos de cada centro de custo
        var orcamentos = centerData.map(function(item) {
            return item.orcamento;
        });

        // Extrai os saldos de cada centro de custo
        var saldos = centerData.map(function(item) {
            return item.saldo;
        });

        // Configuração do gráfico de todos os centros de custos
        var ctx = document.getElementById('allCentersChart').getContext('2d');
        var allCentersChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: centerNames,
                datasets: [{
                    label: 'Despesas',
                    data: despesas,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Orçamento',
                    data: orcamentos,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: 'Saldo',
                    data: saldos,
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
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

</body>
</html>
