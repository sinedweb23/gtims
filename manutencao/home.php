<?php
session_start();
include('config1.php'); // Inclua a conexão com o banco de dados

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Contagem de chamados por status
$query_abertos = "SELECT COUNT(*) as total FROM chamados WHERE status = 'aberto'";
$query_atendimento = "SELECT COUNT(*) as total FROM chamados WHERE status = 'em atendimento'";
$query_fechados = "SELECT COUNT(*) as total FROM chamados WHERE status = 'fechado'";

$result_abertos = mysqli_query($conn, $query_abertos);
$result_atendimento = mysqli_query($conn, $query_atendimento);
$result_fechados = mysqli_query($conn, $query_fechados);

$total_abertos = mysqli_fetch_assoc($result_abertos)['total'];
$total_atendimento = mysqli_fetch_assoc($result_atendimento)['total'];
$total_fechados = mysqli_fetch_assoc($result_fechados)['total'];

// Contagem de chamados por defeito
$query_defeitos = "
    SELECT d.nome, COUNT(*) as total 
    FROM chamados c
    JOIN defeitos d ON c.id_defeito = d.id
    GROUP BY d.nome
";
$result_defeitos = mysqli_query($conn, $query_defeitos);

$defeitos = [];
while ($row = mysqli_fetch_assoc($result_defeitos)) {
    $defeitos[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card-clickable {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Dashboard de Chamados</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3 card-clickable" onclick="window.location.href='chamados.php'">
                    <div class="card-header">Chamados Abertos</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $total_abertos; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Chamados em Atendimento</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $total_atendimento; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Chamados Fechados</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $total_fechados; ?></h5>
                    </div>
                </div>
            </div>
        </div>

        <h2>Chamados por Defeito</h2>
        <canvas id="defeitosChart" width="400" height="200"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('defeitosChart').getContext('2d');
        const defeitosChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($defeitos, 'nome')); ?>,
                datasets: [{
                    label: 'Quantidade de Chamados',
                    data: <?php echo json_encode(array_column($defeitos, 'total')); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
