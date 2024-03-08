<?php
include 'config.php';

// Consulta SQL para buscar os dados necessários
$sql = "SELECT setor.Nome AS NomeSetor, COUNT(*) AS QuantidadeMovimentacao
        FROM movimentacao
        INNER JOIN setor ON movimentacao.SetorID = setor.ID
        GROUP BY movimentacao.SetorID";

$result = $conn->query($sql);

// Array para armazenar os dados do gráfico
$data = array();

// Loop através dos resultados da consulta
while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['NomeSetor'];
    $data['data'][] = $row['QuantidadeMovimentacao'];
}

// Fechar conexão com o banco de dados
$conn->close();

// Retornar os dados como JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
