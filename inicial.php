<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
           font-family: Arial, sans-serif;
        }
        canvas {
            display: block;
            margin: 0 auto;
            width: 45%; /* Defina a largura desejada aqui */
        }
    </style>
</head>
<body>
    <h1>Dashboard</h1>

    <h2>Produtos com Estoque Baixo</h2>
    <div style="margin: 20px auto;">
        <canvas id="lowStockChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'low_stock.php', true);
            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    var data = JSON.parse(xhr.responseText);
                    renderLowStockChart(data);
                } else {
                    console.error('Erro ao obter dados: ' + xhr.statusText);
                }
            };
            xhr.onerror = function () {
                console.error('Erro de rede');
            };
            xhr.send();

            function renderLowStockChart(data) {
                var ctx = document.getElementById('lowStockChart').getContext('2d');
                var labels = data.map(function (product) {
                    return product.NomeProduto;
                });
                var stock = data.map(function (product) {
                    return product.Estoque;
                });

                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Estoque Baixo',
                            data: stock,
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgba(255, 99, 132, 1)',
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
        });
    </script>

    <h2>Movimentações por Setor</h2>
    <div style="margin: 20px auto;">
        <canvas id="movementsBySectorChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'movements_by_sector.php', true);
            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    var data = JSON.parse(xhr.responseText);
                    renderMovementsBySectorChart(data);
                } else {
                    console.error('Erro ao obter dados: ' + xhr.statusText);
                }
            };
            xhr.onerror = function () {
                console.error('Erro de rede');
            };
            xhr.send();

            function renderMovementsBySectorChart(data) {
                var ctx = document.getElementById('movementsBySectorChart').getContext('2d');
                var labels = Object.keys(data);
                var counts = Object.values(data);

                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Movimentações por Setor',
                            data: counts,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
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
        });
    </script>
</body>
</html>
