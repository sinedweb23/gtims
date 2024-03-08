<?php
include 'config.php';

// Consulta SQL para buscar os produtos com estoque baixo
$sql = "SELECT NomeProduto, Estoque
        FROM produto
        WHERE Estoque < 3";

$result = $conn->query($sql);

// Array para armazenar os dados do gráfico
$data = array();

// Loop através dos resultados da consulta
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Fechar conexão com o banco de dados
$conn->close();

// Retornar os dados como JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
