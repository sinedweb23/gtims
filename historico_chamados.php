<?php
require_once('config.php');

// Definição dos parâmetros de paginação
$porPagina = 10;
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$offset = ($pagina - 1) * $porPagina;

// Consulta SQL base
$sql = "SELECT c.id, s.nome AS nome_sala, d.nome AS nome_defeito, d.prioridade, c.observacao, c.status, c.data_abertura, c.data_fechamento, c.solucao
        FROM chamados c
        INNER JOIN salas s ON c.id_sala = s.id
        INNER JOIN defeitos d ON c.id_defeito = d.id
        WHERE c.status = 'Fechado'";

// Aplicação de filtros
if (isset($_GET['local']) && !empty($_GET['local'])) {
    $local = $_GET['local'];
    $sql .= " AND s.nome = '$local'";
}
if (isset($_GET['problema']) && !empty($_GET['problema'])) {
    $problema = $_GET['problema'];
    $sql .= " AND d.nome = '$problema'";
}
if (isset($_GET['data']) && !empty($_GET['data'])) {
    $data = $_GET['data'];
    // Ajuste para o formato de data utilizado no seu banco de dados
    $data_formatada = date('Y-m-d', strtotime($data));
    $sql .= " AND c.data_abertura = '$data_formatada'";
}

// Ordenação
$sql .= " ORDER BY c.data_abertura DESC";

// Paginação
$sqlPaginacao = $sql . " LIMIT $porPagina OFFSET $offset";
$result = $conn->query($sqlPaginacao);

// Total de resultados para paginação
$totalResultados = $conn->query($sql)->num_rows;
$totalPaginas = ceil($totalResultados / $porPagina);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Chamados</title>
    <!-- Link para o Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Histórico de Chamados Fechados</h2>
        <!-- Formulário de Filtro -->
        <form method="GET" action="">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="local">Local:</label>
                    <input type="text" class="form-control" id="local" name="local" value="<?php echo isset($_GET['local']) ? $_GET['local'] : ''; ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="problema">Problema:</label>
                    <input type="text" class="form-control" id="problema" name="problema" value="<?php echo isset($_GET['problema']) ? $_GET['problema'] : ''; ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="data">Data:</label>
                    <input type="date" class="form-control" id="data" name="data" value="<?php echo isset($_GET['data']) ? $_GET['data'] : ''; ?>">
                </div>
                <div class="form-group col-md-3">
                    <button type="submit" class="btn btn-primary mt-4">Filtrar</button>
                </div>
            </div>
        </form>

        <!-- Tabela de Chamados -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Local</th>
                    <th>Problema</th>
                    <th>Prioridade</th>
                    <th>Observação</th>
                    <th>Status</th>
                    <th>Data de Abertura</th>
                    <th>Data de Fechamento</th>
                    <th>Solução</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verifica se a consulta retornou resultados
                if ($result->num_rows > 0) {
                    // Exibe os chamados fechados em uma tabela
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row["id"]."</td>";
                        echo "<td>".$row["nome_sala"]."</td>";
                        echo "<td>".$row["nome_defeito"]."</td>";
                        echo "<td>".$row["prioridade"]."</td>";
                        echo "<td>".$row["observacao"]."</td>";
                        echo "<td>".$row["status"]."</td>";
                        echo "<td>".$row["data_abertura"]."</td>";
                        echo "<td>".$row["data_fechamento"]."</td>";
                        echo "<td>".$row["solucao"]."</td>";
                        echo "</tr>";
                    }
                } else {
                    // Se não houver chamados fechados, exibe uma mensagem
                    echo "<tr><td colspan='9'>Nenhum chamado fechado.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                    <li class="page-item <?php echo $pagina == $i ? 'active' : ''; ?>"><a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</body>
</html>
