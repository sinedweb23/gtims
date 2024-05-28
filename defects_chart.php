<?php
include 'conexao.php';

// Consulta para obter a quantidade de defeitos
$sql = "SELECT defeitos.nome AS NomeDefeito, COUNT(chamados.id_defeito) AS Quantidade
        FROM chamados
        INNER JOIN defeitos ON chamados.id_defeito = defeitos.id
        GROUP BY chamados.id_defeito";
$result = $conn2->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$conn2->close();
?>
