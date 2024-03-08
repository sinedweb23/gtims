<?php
include 'config.php';

// Consulta SQL para contar o número de movimentações por setor
$sql = "SELECT setor.Setor AS NomeSetor, COUNT(movimentacao.SetorID) AS NumMovimentacoes
        FROM movimentacao
        INNER JOIN setor ON movimentacao.SetorID = setor.SetorID
        GROUP BY movimentacao.SetorID";

$result = $conn->query($sql);

// Array para armazenar os dados do gráfico
$data = array();

// Loop através dos resultados da consulta
while ($row = $result->fetch_assoc()) {
    $data[$row['NomeSetor']] = $row['NumMovimentacoes'];
}

// Fechar conexão com o banco de dados
$conn->close();

// Retornar os dados como JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
