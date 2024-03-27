<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1 class="mt-4 mb-4 text-center">Dashboard</h1>

        <div class="row">
            <!-- Dashboard de Produtos com Estoque Baixo -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h2 class="card-title">Produtos com Estoque Baixo</h2>
                    </div>
                    <div class="card-body">
                        <canvas id="lowStockChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>

            <!-- Dashboard de Movimentações por Setor -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h2 class="card-title">Movimentações por Setor</h2>
                    </div>
                    <div class="card-body">
                        <canvas id="movementsBySectorChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>

            <!-- Novo Dashboard: chromebooks emprestados por mais de 1 dia sem devolução. -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h2 class="card-title">Ativos emprestados por mais de 1 dia sem devolução</h2>
                    </div>
                    <div class="card-body">
                        <canvas id="overduechromebooksChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function getData(url, callback) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', url, true);
                xhr.onload = function () {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        var data = JSON.parse(xhr.responseText);
                        callback(data);
                    } else {
                        console.error('Erro ao obter dados: ' + xhr.statusText);
                    }
                };
                xhr.onerror = function () {
                    console.error('Erro de rede');
                };
                xhr.send();
            }

            function renderChart(ctx, labels, data, label) {
                var chart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: label,
                            data: data,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(153, 102, 255, 0.6)',
                                'rgba(255, 159, 64, 0.6)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
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
            }

            getData('low_stock.php', function (data) {
                var ctx = document.getElementById('lowStockChart').getContext('2d');
                var labels = data.map(function (product) {
                    return product.NomeProduto;
                });
                var stock = data.map(function (product) {
                    return product.Estoque;
                });
                renderChart(ctx, labels, stock, 'Estoque Baixo');
            });

            getData('movements_by_sector.php', function (data) {
                var ctx = document.getElementById('movementsBySectorChart').getContext('2d');
                var labels = Object.keys(data);
                var counts = Object.values(data);
                renderChart(ctx, labels, counts, 'Movimentações por Setor');
            });

            getData('overdue_chromebooks.php', function (data) {
                var ctx = document.getElementById('overduechromebooksChart').getContext('2d');
                var labels = data.map(function (chromebook) {
                    return chromebook.NomeChromebook + ' (Usuário: ' + chromebook.Usuario + ')';
                });
                var daysLate = data.map(function (chromebook) {
                    return chromebook.DiasAtraso;
                });
                renderChart(ctx, labels, daysLate, 'Dias de Atraso');
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
