document.addEventListener('DOMContentLoaded', function () {
    // Requisição AJAX para obter os dados do PHP
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'data.php', true);
    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            // Dados recebidos com sucesso
            var data = JSON.parse(xhr.responseText);
            renderChart(data);
        } else {
            console.error('Erro ao obter dados: ' + xhr.statusText);
        }
    };
    xhr.onerror = function () {
        console.error('Erro de rede');
    };
    xhr.send();

    // Função para renderizar o gráfico
    function renderChart(data) {
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Utilização da Peça',
                    data: data.data,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
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
    }
});
