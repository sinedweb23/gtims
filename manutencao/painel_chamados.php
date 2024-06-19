<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Chamados</title>
    <!-- Incluindo Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Incluindo Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Estilos CSS customizados -->
    <link rel="icon" type="image/png" href="fav.png">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .logo {
            max-width: 100px;
            height: auto;
        }
        .container {
            max-width: 100%;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .card-body {
            padding: 15px;
        }
        .card-status {
            min-height: 150px;
        }
        .card-aberto {
            background-color: #ffcccc;
        }
        .card-fechado {
            background-color: #ccffcc;
        }
        .card-em-atendimento {
            background-color: #ffffcc;
        }
        .card-aguardando-material {
            background-color: #cce5ff;
        }
        .card-reprovado {
            background-color: #e6ccff;
        }
        .icon {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-4">
            <img src="logo.png" alt="Logo da Empresa" class="logo">
        </div>
        
        
        <?php
        // Inclui o arquivo de configuração do banco de dados
        include 'config1.php';

        // Consulta para contar os chamados por status
        $status_counts = [];
        $statuses = ['Aberto', 'Fechado', 'Em Atendimento', 'Aguardando Material', 'Reprovado'];
        
        foreach ($statuses as $status) {
            $sql_count = "SELECT COUNT(*) AS count FROM chamados WHERE status = '$status'";
            $result_count = $conn->query($sql_count);
            if ($result_count->num_rows > 0) {
                $row_count = $result_count->fetch_assoc();
                $status_counts[$status] = $row_count['count'];
            } else {
                $status_counts[$status] = 0;
            }
        }
        ?>
        
        <div class="row mb-4">
            <?php foreach ($status_counts as $status => $count): ?>
                <?php 
                $class = '';
                switch ($status) {
                    case 'Aberto':
                        $class = 'card-aberto';
                        break;
                    case 'Fechado':
                        $class = 'card-fechado';
                        break;
                    case 'Em Atendimento':
                        $class = 'card-em-atendimento';
                        break;
                    case 'Aguardando Material':
                        $class = 'card-aguardando-material';
                        break;
                    case 'Reprovado':
                        $class = 'card-reprovado';
                        break;
                }
                ?>
                <div class="col-md-2">
                    <div class="card card-status <?= $class ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= $status ?></h5>
                            <p class="card-text"><?= $count ?> chamados</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <form method="GET" class="mb-4">
            <div class="form-group row">
                <label for="status" class="col-sm-2 col-form-label">Filtrar por Status:</label>
                <div class="col-sm-10">
                    <select class="form-control" id="status" name="status">
                        <option value="">Todos</option>
                        <option value="Aberto">Aberto</option>
                        <option value="Em Atendimento">Em Atendimento</option>
                        <option value="Fechado">Fechado</option>
                        <option value="Aguardando Material">Aguardando Material</option>
                        <option value="Reprovado">Reprovado</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
        
        <div class="row">
            <?php
            // Inicializa a variável para a consulta SQL base
            $sql_chamados = "SELECT s.nome AS nome_sala, d.nome AS nome_defeito, c.observacao, c.status, c.data_abertura, c.data_fechamento, 
                            CASE
                                WHEN c.status = 'Fechado' THEN c.solucao
                                WHEN c.status = 'Aguardando Material' THEN c.requisicoes
                                ELSE ''
                            END AS solucao_requisicoes
                            FROM chamados c
                            INNER JOIN salas s ON c.id_sala = s.id
                            INNER JOIN defeitos d ON c.id_defeito = d.id";

            // Verifica se foi enviado um filtro por status via GET
            if (isset($_GET['status']) && !empty($_GET['status'])) {
                $status = $_GET['status'];
                $sql_chamados .= " WHERE c.status = '$status'";
            }

            $sql_chamados .= " ORDER BY c.data_abertura DESC";

            $result = $conn->query($sql_chamados);

            // Verifica se há resultados.
            if ($result->num_rows > 0) {
                // Exibe os dados encontrados em cards responsivos
                while($row = $result->fetch_assoc()) {
                    $data_abertura = date("d/m/Y - H:i", strtotime($row['data_abertura']));
                    $data_fechamento = $row['data_fechamento'] ? date("d/m/Y - H:i", strtotime($row['data_fechamento'])) : '';
                    echo '<div class="col-md-6">';
                    echo '<div class="card">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row['nome_sala'] . '</h5>';
                    echo '<h6 class="card-subtitle mb-2 text-muted">' . $row['nome_defeito'] . '</h6>';
                    echo '<p class="card-text">Observação: ' . $row['observacao'] . '</p>';
                    echo '<p class="card-text"><i class="fas fa-check-circle icon"></i>Status: ' . $row['status'] . '</p>';
                    echo '<p class="card-text"><i class="fas fa-calendar-alt icon"></i>Data de Abertura: ' . $data_abertura . '</p>';
                    if ($row['status'] == 'Fechado') {
                        echo '<p class="card-text"><i class="fas fa-check icon"></i>Solução: ' . $row['solucao_requisicoes'] . '</p>';
                        echo '<p class="card-text"><i class="fas fa-calendar-alt icon"></i>Data de Fechamento: ' . $data_fechamento . '</p>';
                    } elseif ($row['status'] == 'Aguardando Material') {
                        echo '<p class="card-text"><i class="fas fa-box icon"></i>Requisições: ' . $row['solucao_requisicoes'] . '</p>';
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<div class='col-12'><p>Nenhum chamado encontrado.</p></div>";
            }

            // Fecha a conexão com o banco de dados
            $conn->close();
            ?>
        </div>
    </div>
    <!-- Incluindo Bootstrap JS e jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>
