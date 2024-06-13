<?php
// Inclui o arquivo de configuração do banco de dados
require_once('config1.php');

session_start();

// Definir o fuso horário
date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d');

// Consulta o banco de dados para obter os chamados abertos, em atendimento e aguardando material
$sql_chamados = "SELECT c.id, c.nome AS solicitante, s.nome AS nome_sala, d.nome AS nome_defeito, c.observacao, c.status, c.data_abertura, c.data_fechamento, c.requisicoes
                FROM chamados c
                INNER JOIN salas s ON c.id_sala = s.id
                INNER JOIN defeitos d ON c.id_defeito = d.id
                WHERE c.status = 'Aberto'
                ORDER BY c.data_abertura DESC";

// Consulta o banco de dados para obter os chamados fechados de hoje
$sql_fechados = "SELECT c.id, c.nome AS solicitante, s.nome AS nome_sala, d.nome AS nome_defeito, c.observacao, c.status, c.data_abertura, c.data_fechamento
                FROM chamados c
                INNER JOIN salas s ON c.id_sala = s.id
                INNER JOIN defeitos d ON c.id_defeito = d.id
                WHERE c.status = 'Fechado' AND DATE(c.data_fechamento) = '$hoje'
                ORDER BY c.data_abertura DESC";

// Executa as consultas
$result_chamados = $conn->query($sql_chamados);
$result_fechados = $conn->query($sql_fechados);

if (!$result_chamados) {
    die("Erro na consulta SQL de chamados: " . $conn->error);
}

if (!$result_fechados) {
    die("Erro na consulta SQL de fechados: " . $conn->error);
}
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
            overflow-x: hidden;
        }
        .container-fluid {
            padding: 0;
            height: 100%;
            overflow-y: auto;
        }
        .card {
            margin: 10px 0;
            border: none; /* Remove border */
        }
        .status-aberto .card-body {
            background-color: #f8d7da !important; /* Vermelho claro */
        }
        .status-em-atendimento .card-body {
            background-color: #fff3cd !important; /* Amarelo claro */
        }
        .status-fechado .card-body {
            background-color: #d4edda !important; /* Verde claro */
        }
        .status-aguardando-material .card-body {
            background-color: #cfe2ff !important; /* Azul claro */
        }
        .card-body p {
            margin: 0.5rem 0;
        }
        @media (max-width: 767.98px) {
            .card {
                margin: 10px 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h5 class="m-2">Chamados</h5>
        <div class="row" id="chamadosAbertos">
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
                    } elseif ($row["status"] == 'Aguardando Material') {
                        $status_class = 'status-aguardando-material';
                    }
            ?>
            <div class="col-12">
                <div class="card <?php echo $status_class; ?>">
                    <div class="card-body">
                        <p><strong>ID:</strong> <?php echo $row["id"]; ?></p>
                        <p><strong>Solicitante:</strong> <?php echo $row["solicitante"]; ?></p>
                        <p><strong>Sala:</strong> <?php echo $row["nome_sala"]; ?></p>
                        <p><strong>Problema:</strong> <?php echo $row["nome_defeito"]; ?></p>
                        <p><strong>Observação:</strong> <?php echo $row["observacao"]; ?></p>
                        <p><strong>Status:</strong> <?php echo $row["status"]; ?></p>
                        <p><strong>Data de Abertura:</strong> <?php echo $row["data_abertura"]; ?></p>
                        <?php if ($row["status"] == 'Aguardando Material'): ?>
                            <p><strong>Peças Necessárias:</strong> <?php echo $row["requisicoes"]; ?></p>
                        <?php endif; ?>
                        <p>
                            <select class="form-control" onchange="alterarStatus(this, <?php echo $row['id']; ?>)">
                                <option value="Aberto" <?php echo $row["status"] == 'Aberto' ? 'selected' : ''; ?>>Aberto</option>
                                <option value="Em Atendimento" <?php echo $row["status"] == 'Em Atendimento' ? 'selected' : ''; ?>>Em Atendimento</option>
                                <option value="Aguardando Material" <?php echo $row["status"] == 'Aguardando Material' ? 'selected' : ''; ?>>Aguardando Material</option>
                                <option value="Fechado" <?php echo $row["status"] == 'Fechado' ? 'selected' : ''; ?>>Fechado</option>
                                <option value="Reprovado" <?php echo $row["status"] == 'Reprovado' ? 'selected' : ''; ?>>Reprovado</option>
                            </select>
                        </p>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<div class='col-12'><p>Nenhum chamado encontrado.</p></div>";
            }
            ?>
        </div>

        <h5 class="m-2">Chamados Fechados Hoje</h5>
        <div class="row" id="chamadosFechados">
            <?php
            if ($result_fechados && $result_fechados->num_rows > 0) {
                while($row = $result_fechados->fetch_assoc()) {
            ?>
            <div class="col-12">
                <div class="card status-fechado">
                    <div class="card-body">
                        <p><strong>ID:</strong> <?php echo $row["id"]; ?></p>
                        <p><strong>Solicitante:</strong> <?php echo $row["solicitante"]; ?></p>
                        <p><strong>Sala:</strong> <?php echo $row["nome_sala"]; ?></p>
                        <p><strong>Problema:</strong> <?php echo $row["nome_defeito"]; ?></p>
                        <p><strong>Observação:</strong> <?php echo $row["observacao"]; ?></p>
                        <p><strong>Status:</strong> <?php echo $row["status"]; ?></p>
                        <p><strong>Data de Abertura:</strong> <?php echo $row["data_abertura"]; ?></p>
                        <p><strong>Data de Fechamento:</strong> <?php echo $row["data_fechamento"]; ?></p>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<div class='col-12'><p>Nenhum chamado fechado hoje.</p></div>";
            }
            ?>
        </div>
    </div>

    <!-- Script para alterar o status do chamado -->
    <script>
        function alterarStatus(selectElement, id) {
            var status = selectElement.value;
            var solucao = "";
            var requisicoes = "";

            if (status === 'Fechado' || status === 'Reprovado') {
                solucao = prompt("Por favor, descreva a solução adotada:");
                if (solucao === null || solucao.trim() === "") {
                    // Se não houver entrada ou for uma string vazia, resetar para o status anterior e sair da função
                    selectElement.value = selectElement.querySelector("option[selected]").value;
                    return;
                }
            } else if (status === 'Aguardando Material') {
                requisicoes = prompt("Por favor, digite o nome das peças necessárias:");
                if (requisicoes === null || requisicoes.trim() === "") {
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
                    solucao: solucao,
                    requisicoes: requisicoes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateTables();
                } else {
                    alert("Erro ao alterar o status do chamado: " + data.error);
                }
            })
            .catch(error => console.error('Erro ao alterar o status do chamado:', error));
        }

        // Função para atualizar a tabela de chamados automaticamente
        function updateTables() {
            fetch('chamados.php')
            .then(response => response.text())
            .then(data => {
                var parser = new DOMParser();
                var doc = parser.parseFromString(data, 'text/html');
                var novosChamados = doc.querySelector('#chamadosAbertos').innerHTML;
                var novosFechados = doc.querySelector('#chamadosFechados').innerHTML;
                document.querySelector('#chamadosAbertos').innerHTML = novosChamados;
                document.querySelector('#chamadosFechados').innerHTML = novosFechados;
            })
            .catch(error => console.error('Erro ao atualizar a tabela de chamados:', error));
        }

        // Atualiza a tabela de chamados a cada 10 segundos
        setInterval(updateTables, 10000);
    </script>
</body>
</html>
