<?php
// Inclui o arquivo de configuração do banco de dados
require_once('config1.php');

// Definindo a quantidade de chamados por página
$porPagina = 12;

// Definindo a página atual
$paginaAtual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

// Calculando o offset para consulta SQL
$offset = ($paginaAtual - 1) * $porPagina;

// Construindo a consulta SQL base
$sqlBase = "SELECT c.id, s.nome AS nome_sala, d.nome AS nome_defeito, d.prioridade, c.observacao, c.status, DATE_FORMAT(c.data_abertura, '%d/%m/%y %H:%i') AS data_abertura_formatada, DATE_FORMAT(c.data_fechamento, '%d/%m/%y %H:%i') AS data_fechamento_formatada, c.solucao
            FROM chamados c
            INNER JOIN salas s ON c.id_sala = s.id
            INNER JOIN defeitos d ON c.id_defeito = d.id
            WHERE c.status = 'Fechado'";

// Aplicando filtros, se fornecidos
if (isset($_GET['local']) && !empty($_GET['local'])) {
    $local = $_GET['local'];
    $sqlBase .= " AND s.nome LIKE '%$local%'";
}
if (isset($_GET['problema']) && !empty($_GET['problema'])) {
    $problema = $_GET['problema'];
    $sqlBase .= " AND d.nome LIKE '%$problema%'";
}
if (isset($_GET['data']) && !empty($_GET['data'])) {
    $data = $_GET['data'];
    $sqlBase .= " AND DATE(c.data_abertura) = '$data'";
}

// Contando o total de chamados fechados para a paginação
$sqlCount = "SELECT COUNT(*) AS total FROM (" . $sqlBase . ") AS subquery";
$resultCount = $conn->query($sqlCount);
$rowCount = $resultCount->fetch_assoc();
$totalChamados = $rowCount['total'];

// Calculando o número total de páginas
$totalPaginas = ceil($totalChamados / $porPagina);

// Construindo a consulta SQL final com ordenação e limite
$sqlFinal = $sqlBase . " ORDER BY c.data_abertura DESC LIMIT $offset, $porPagina";

// Executando a consulta final
$result = $conn->query($sqlFinal);
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
        <h5 class="mb-4">Histórico de Chamados Fechados</h5>

        <!-- Formulário de Filtros -->
        <form method="GET">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="local">Local</label>
                    <select class="form-control" id="local" name="local">
                        <option value="">Selecione o Local</option>
                        <?php
                        // Consulta SQL para obter os locais disponíveis
                        $sqlLocais = "SELECT DISTINCT nome FROM salas";
                        $resultLocais = $conn->query($sqlLocais);
                        if ($resultLocais->num_rows > 0) {
                            while($rowLocal = $resultLocais->fetch_assoc()) {
                                echo "<option value='".$rowLocal['nome']."'>".$rowLocal['nome']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="problema">Problema</label>
                    <select class="form-control" id="problema" name="problema">
                        <option value="">Selecione o Problema</option>
                        <?php
                        // Consulta SQL para obter os problemas disponíveis
                        $sqlProblemas = "SELECT DISTINCT nome FROM defeitos";
                        $resultProblemas = $conn->query($sqlProblemas);
                        if ($resultProblemas->num_rows > 0) {
                            while($rowProblema = $resultProblemas->fetch_assoc()) {
                                echo "<option value='".$rowProblema['nome']."'>".$rowProblema['nome']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="data">Data</label>
                    <input type="date" class="form-control" id="data" name="data">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>

        <!-- Tabela de Chamados -->
        <table class="table mt-4">
            <thead>
                <tr>
                    
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
                       
                        echo "<td>".$row["nome_sala"]."</td>";
                        echo "<td>".$row["nome_defeito"]."</td>";
                        echo "<td>".$row["prioridade"]."</td>";
                        echo "<td>".$row["observacao"]."</td>";
                        echo "<td>".$row["status"]."</td>";
                        echo "<td>".$row["data_abertura_formatada"]."</td>";
                        echo "<td>".$row["data_fechamento_formatada"]."</td>";
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
        <nav aria-label="Paginação">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                    <li class="page-item <?php if ($paginaAtual == $i) echo 'active'; ?>">
                        <a class="page-link" href="?pagina=<?php echo $i; ?>&local=<?php echo isset($_GET['local']) ? $_GET['local'] : ''; ?>&problema=<?php echo isset($_GET['problema']) ? $_GET['problema'] : ''; ?>&data=<?php echo isset($_GET['data']) ? $_GET['data'] : ''; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</body>
</html>
