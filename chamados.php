<?php
// Inclui o arquivo de configuração do banco de dados
require_once('config1.php');

session_start();

// Consulta o banco de dados para obter os chamados abertos e em atendimento
$sql_chamados = "SELECT c.id, c.nome AS solicitante, s.nome AS nome_sala, d.nome AS nome_defeito, d.prioridade, c.observacao, c.status, c.data_abertura, c.data_fechamento
                FROM chamados c
                INNER JOIN salas s ON c.id_sala = s.id
                INNER JOIN defeitos d ON c.id_defeito = d.id
                WHERE c.status = 'Aberto' OR c.status = 'Atendendo'
                ORDER BY c.data_abertura DESC";

// Consulta o banco de dados para obter os chamados fechados de hoje
$sql_fechados = "SELECT c.id, c.nome AS solicitante, s.nome AS nome_sala, d.nome AS nome_defeito, d.prioridade, c.observacao, c.status, c.data_abertura, c.data_fechamento
                FROM chamados c
                INNER JOIN salas s ON c.id_sala = s.id
                INNER JOIN defeitos d ON c.id_defeito = d.id
                WHERE c.status = 'Fechado' AND DATE(c.data_fechamento) = CURDATE()
                ORDER BY c.data_fechamento DESC";

// Executa as consultas
$result_chamados = $conn->query($sql_chamados);
$result_fechados = $conn->query($sql_fechados);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chamados</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos omitidos para brevidade */
    </style>
</head>
<body>
    <div class="container-fluid">
        <h5 class="m-2">Chamados</h5>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Solicitante</th>
                        <th>Sala</th>
                        <th>Problema</th>
                        <th>Prioridade</th>
                        <th>Observação</th>
                        <th>Status</th>
                        <th>Data de Abertura</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- As linhas serão preenchidas pelo script abaixo -->
                </tbody>
            </table>
        </div>
        <audio id="notificationSound" src="notification_sound.mp3" preload="auto"></audio>
    </div>

    <script>
        var lastChamadosCount = 0;

        function fetchChamados() {
            fetch('get_chamados.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('.table-responsive table tbody');
                let newHtml = '';
                data.forEach(chamado => {
                    newHtml += `<tr class="${chamado.prioridade} ${chamado.status}">
                                    <td>${chamado.id}</td>
                                    <td>${chamado.solicitante}</td>
                                    <td>${chamado.nome_sala}</td>
                                    <td>${chamado.nome_defeito}</td>
                                    <td>${chamado.prioridade}</td>
                                    <td>${chamado.observacao}</td>
                                    <td>${chamado.status}</td>
                                    <td>${chamado.data_abertura}</td>
                                    <td>
                                        <select class='form-control' onchange='alterarStatus(this, ${chamado.id})'>
                                            <option value='Aberto'${chamado.status == 'Aberto' ? ' selected' : ''}>Aberto</option>
                                            <option value='Atendendo'${chamado.status == 'Atendendo' ? ' selected' : ''}>Em Atendimento</option>
                                            <option value='Fechado'>Fechado</option>
                                        </select>
                                    </td>
                                </tr>`;
                });
                tableBody.innerHTML = newHtml;

                // Toca o som de notificação se a contagem de chamados aumentou
                if (data.length > lastChamadosCount) {
                    document.getElementById('notificationSound').play();
                }
                lastChamadosCount = data.length;
            });
        }

        // Verifica novos chamados a cada 10 segundos
        setInterval(fetchChamados, 10000);
    </script>
</body>
</html>
