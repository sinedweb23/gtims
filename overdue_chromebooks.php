<?php
// Inclua o arquivo de configuração do banco de dados
include 'config.php';

// Consulta para buscar empréstimos com mais de 1 dia de atraso
$sql = "SELECT c.Nome AS NomeChromebook, e.Usuario, DATEDIFF(NOW(), e.DataEmprestimo) AS DiasAtraso
        FROM emprestimos e
        INNER JOIN chromebooks c ON e.ChromebookID = c.ID
        WHERE DATEDIFF(NOW(), e.DataEmprestimo) > 1";

$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        // Array para armazenar os resultados
        $emprestimosAtrasados = array();

        // Retornar os resultados como JSON
        while($row = $result->fetch_assoc()) {
            $emprestimosAtrasados[] = $row;
        }

        echo json_encode($emprestimosAtrasados);
    } else {
        echo "Nenhum empréstimo em atraso encontrado.";
    }
} else {
    echo "Erro na consulta SQL: " . $conn->error;
}

$conn->close();
?>
