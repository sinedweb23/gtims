<?php
// Inclui o arquivo de configuração do banco de dados
require_once('config1.php');

session_start();

// Consulta o banco de dados para obter os chamados abertos e em atendimento
$sql_chamados = "SELECT c.id, c.nome AS solicitante, s.nome AS nome_sala, d.nome AS nome_defeito, c.observacao, c.status, c.data_abertura, c.data_fechamento
                FROM chamados c
                INNER JOIN salas s ON c.id_sala = s.id
                INNER JOIN defeitos d ON c.id_defeito = d.id
                WHERE c.status = 'Aberto' OR c.status = 'Em Atendimento'
                ORDER BY c.data_abertura DESC";

// Consulta o banco de dados para obter os chamados fechados de hoje
$sql_fechados = "SELECT c.id, c.nome AS solicitante, s.nome AS nome_sala, d.nome AS nome_defeito, c.observacao, c.status, c.data_abertura, c.data_fechamento
                FROM chamados c
                INNER JOIN salas s ON c.id_sala = s.id
                INNER JOIN defeitos d ON c.id_defeito = d.id
                WHERE c.status = 'Fechado' AND DATE(c.data_fechamento) = CURDATE()
                ORDER BY c.data_abertura DESC";

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
    <!-- Link para o Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Remover margens e padding */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        .container-fluid {
            padding: 0;
            height: 100%;
            overflow: auto; /* Permite que a página cresça conforme necessário */
        }
        .table-responsive {
            height: 50%; /* Ajustar altura para exibir metade da tela */
            overflow-y: auto;
        }
        .table {
            margin: 0;
        }
        .table thead th {
            position: sticky;
            top: 0;
            background: #f8f9fa;
        }
        /* Estilo para chamados abertos */
        .status-aberto {
            background-color: #f8d7da !important; /* Vermelho claro */
        }
        /* Estilo para chamados em atendimento */
        .status-em-atendimento {
            background-color: #fff3cd !important; /* Amarelo claro */
        }
        /* Estilo para chamados fechados */
        .status-fechado {
            background-color: #d4edda !important; /* Verde claro */
        }
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
                        <th>Observação</th>
                        <th>Status</th>
                        <th>Data de Abertura</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody id="chamadosTable">
                    <?php
                    if ($result_chamados && $result_chamados->num_rows > 0) {
                        while($row = $result_chamados->fetch_assoc()) {
                            $status_class = '';
                            if ($row["status"] == 'Aberto') {
                                $status_class = 'status-aberto';
                            } elseif ($row["status"] == 'Em Atendimento') {
                                $status_class = 'status-em-atendimento';
                            } elseif ($row["status"] == 'Fechado') {
                                $status_class = 'status-fechado';
                            }

                            echo "<tr class='".$status_class."'>";
                            echo "<td>".$row["id"]."</td>";
                            echo "<td>".$row["solicitante"]."</td>";
                            echo "<td>".$row["nome_sala"]."</td>";
                            echo "<td>".$row["nome_defeito"]."</td>";
                            echo "<td>".$row["observacao"]."</td>";
                            echo "<td>".$row["status"]."</td>";
                            echo "<td>".$row["data_abertura"]."</td>";
                            echo "<td>
                                    <select class='form-control' onchange='alterarStatus(this, ".$row["id"].")'>
                                        <option value='Aberto'".($row["status"] == 'Aberto' ? ' selected' : '').">Aberto</option>
                                        <option value='Em Atendimento'".($row["status"] == 'Em Atendimento' ? ' selected' : '').">Em Atendimento</option>
                                        <option value='Fechado'>Fechado</option>
                                    </select>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Nenhum chamado encontrado.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h5 class="m-2">Chamados Fechados Hoje</h5>
        <div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Solicitante</th>
                        <th>Sala</th>
                        <th>Problema</th>
                        <th>Observação</th>
                        <th>Status</th>
                        <th>Data de Abertura</th>
                        <th>Data de Fechamento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_fechados && $result_fechados->num_rows > 0) {
                        while($row = $result_fechados->fetch_assoc()) {
                            echo "<tr class='status-fechado'>";
                            echo "<td>".$row["id"]."</td>";
                            echo "<td>".$row["solicitante"]."</td>";
                            echo "<td>".$row["nome_sala"]."</td>";
                            echo "<td>".$row["nome_defeito"]."</td>";
                            echo "<td>".$row["observacao"]."</td>";
                            echo "<td>".$row["status"]."</td>";
                            echo "<td>".$row["data_abertura"]."</td>";
                            echo "<td>".$row["data_fechamento"]."</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>Nenhum chamado fechado hoje.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script para alterar o status do chamado -->
    <script>
        function alterarStatus(selectElement, id) {
            var status = selectElement.value;
            var solucao = "";

            if (status === 'Fechado') {
                solucao = prompt("Por favor, descreva a solução adotada:");
                if (solucao === null || solucao.trim() === "") {
                    // Se não houver entrada ou for uma string vazia, resetar para o status anterior e sair da função
                    selectElement.value = selectElement.querySelector("option[selected]").value;
                    return;
                }
            }

            fetch('alterar_status_chamado.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    chamado_id: id,
                    status: status,
                    solucao: solucao
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert("Erro ao alterar o status do chamado: " + data.error);
                }
            })
            .catch(error => console.error('Erro ao alterar o status do chamado:', error));
        }

        // Função para atualizar a tabela de chamados automaticamente
        function updateChamadosTable() {
            fetch('chamados.php')
            .then(response => response.text())
            .then(data => {
                var parser = new DOMParser();
                var doc = parser.parseFromString(data, 'text/html');
                var novosChamados = doc.querySelector('#chamadosTable').innerHTML;
                document.querySelector('#chamadosTable').innerHTML = novosChamados;
            })
            .catch(error => console.error('Erro ao atualizar a tabela de chamados:', error));
        }

        // Atualiza a tabela de chamados a cada 10 segundos
        setInterval(updateChamadosTable, 10000);
    </script>
</body>
</html>
