<?php
// Inclui o arquivo de configuração do banco de dados
require_once('config1.php');

header('Content-Type: application/json');

$sql = "SELECT c.id, c.nome AS solicitante, s.nome AS nome_sala, d.nome AS nome_defeito, d.prioridade, c.observacao, c.status, c.data_abertura
        FROM chamados c
        INNER JOIN salas s ON c.id_sala = s.id
        INNER JOIN defeitos d ON c.id_defeito = d.id
        WHERE c.status = 'Aberto' OR c.status = 'Atendendo'
        ORDER BY c.data_abertura DESC";

$result = $conn->query($sql);

$chamados = [];
while ($row = $result->fetch_assoc()) {
    $chamados[] = $row;
}

echo json_encode($chamados);
?>
