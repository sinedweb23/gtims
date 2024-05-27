<?php
include 'db_connect.php';

// Configurações de paginação
$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filtros
$ativo_id = isset($_GET['ativo_id']) ? (int)$_GET['ativo_id'] : null;
$data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : null;
$data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : null;

// Query base
$query = "SELECT d.*, r.data_reserva, r.hora_retirada, r.hora_devolucao 
          FROM devolucoes d 
          JOIN reservas r ON d.reserva_id = r.id 
          WHERE 1=1";

// Adiciona filtros
$params = [];
if ($ativo_id) {
    $query .= " AND d.ChromebookID = :ativo_id";
    $params['ativo_id'] = $ativo_id;
}
if ($data_inicio) {
    $query .= " AND d.DataEmprestimo >= :data_inicio";
    $params['data_inicio'] = $data_inicio;
}
if ($data_fim) {
    $query .= " AND d.DataEmprestimo <= :data_fim";
    $params['data_fim'] = $data_fim;
}

// Conta total de registros
$stmt = $conn_gestao->prepare($query);
$stmt->execute($params);
$total_results = $stmt->rowCount();

// Adiciona limite e offset para paginação
$query .= " ORDER BY d.DataEmprestimo DESC LIMIT $limit OFFSET $offset";

$stmt = $conn_gestao->prepare($query);
$stmt->execute($params);
$devolucoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcula total de páginas
$total_pages = ceil($total_results / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Histórico de Empréstimos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Histórico de Empréstimos</h1>
        <form method="GET" action="cbmovimentacoes.php" class="mt-4">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="ativo_id">Ativo</label>
                    <select id="ativo_id" name="ativo_id" class="form-control">
                        <option value="">Selecione o ativo</option>
                        <?php
                        $stmt = $conn_gestao->query("SELECT ID FROM chromebooks");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $selected = ($ativo_id == $row['ID']) ? 'selected' : '';
                            echo "<option value='{$row['ID']}' $selected>Chromebook {$row['ID']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="data_inicio">Data Início</label>
                    <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="<?= $data_inicio ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="data_fim">Data Fim</label>
                    <input type="date" id="data_fim" name="data_fim" class="form-control" value="<?= $data_fim ?>">
                </div>
                <div class="form-group col-md-2">
                    <button type="submit" class="btn btn-primary mt-4">Filtrar</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reserva ID</th>
                    <th>Chromebook ID</th>
                    <th>Data Empréstimo</th>
                    <th>Hora Empréstimo</th>
                    <th>Email Professor</th>
                    <th>Data Hora Devolução</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($devolucoes as $devolucao): ?>
                    <tr>
                        <td><?= $devolucao['id'] ?></td>
                        <td><?= $devolucao['reserva_id'] ?></td>
                        <td><?= $devolucao['ChromebookID'] ?></td>
                        <td><?= $devolucao['DataEmprestimo'] ?></td>
                        <td><?= $devolucao['HoraEmprestimo'] ?></td>
                        <td><?= $devolucao['email_professor'] ?></td>
                        <td><?= $devolucao['DataHoraDevolucao'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&ativo_id=<?= $ativo_id ?>&data_inicio=<?= $data_inicio ?>&data_fim=<?= $data_fim ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
