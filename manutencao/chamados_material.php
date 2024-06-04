<?php
session_start();
include('config1.php'); // Inclua a conexão com o banco de dados

if ($_SESSION['permissao'] != 'admin') {
    header("Location: erro_permissao.php");
    exit;
}

// Consulta o banco de dados para obter os chamados aguardando material
$sql_chamados = "SELECT c.id, c.nome AS solicitante, s.nome AS nome_sala, d.nome AS nome_defeito, c.observacao, c.status, c.data_abertura, c.requisicoes
                FROM chamados c
                INNER JOIN salas s ON c.id_sala = s.id
                INNER JOIN defeitos d ON c.id_defeito = d.id
                WHERE c.status = 'Aguardando Material' OR c.status = 'Aguardando Aprovação'
                ORDER BY c.data_abertura DESC";

// Executa a consulta
$result_chamados = $conn->query($sql_chamados);

if (!$result_chamados) {
    die("Erro na consulta SQL de chamados: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chamados Aguardando Material</title>
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
        .status-aguardando-aprovacao .card-body {
            background-color: #fff3cd !important; /* Amarelo claro */
        }
        .status-aguardando-material .card-body {
            background-color: #cfe2ff !important; /* Azul claro */
        }
        .status-aberto .card-body {
            background-color: #f8d7da !important; /* Vermelho claro */
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
        <h5 class="m-2">Chamados Aguardando Material</h5>
        <div class="row" id="chamadosAguardandoMaterial">
            <?php
            if ($result_chamados && $result_chamados->num_rows > 0) {
                while($row = $result_chamados->fetch_assoc()) {
                    $status_class = '';
                    if ($row["status"] == 'Aguardando Material') {
                        $status_class = 'status-aguardando-material';
                    } elseif ($row["status"] == 'Aguardando Aprovação') {
                        $status_class = 'status-aguardando-aprovacao';
                    } elseif ($row["status"] == 'Aberto') {
                        $status_class = 'status-aberto';
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
                        <p><strong>Materiais Necessários:</strong> <?php echo $row["requisicoes"]; ?></p>
                        <p>
                            <select class="form-control" onchange="alterarStatus(this, <?php echo $row['id']; ?>)">
                                <option value="Aguardando Aprovação" <?php echo $row["status"] == 'Aguardando Aprovação' ? 'selected' : ''; ?>>Aguardando Aprovação</option>
                                <option value="Comprado">Comprado</option>
                            </select>
                        </p>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<div class='col-12'><p>Nenhum chamado aguardando material.</p></div>";
            }
            ?>
        </div>
    </div>

    <!-- Script para alterar o status do chamado -->
    <script>
        function alterarStatus(selectElement, id) {
            var status = selectElement.value;

            fetch('alterar_status_chamado.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    chamado_id: id,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateChamados();
                } else {
                    alert("Erro ao alterar o status do chamado: " + data.error);
                }
            })
            .catch(error => console.error('Erro ao alterar o status do chamado:', error));
        }

        // Função para atualizar a tabela de chamados automaticamente
        function updateChamados() {
            fetch('chamados_material.php')
            .then(response => response.text())
            .then(data => {
                var parser = new DOMParser();
                var doc = parser.parseFromString(data, 'text/html');
                var novosChamados = doc.querySelector('#chamadosAguardandoMaterial').innerHTML;
                document.querySelector('#chamadosAguardandoMaterial').innerHTML = novosChamados;
            })
            .catch(error => console.error('Erro ao atualizar a tabela de chamados:', error));
        }

        // Atualiza a tabela de chamados a cada 10 segundos
        setInterval(updateChamados, 10000);
    </script>
</body>
</html>
