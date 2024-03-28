<?php

// Incluir arquivo de configuração do banco de dados
include 'config.php';

// Consulta SQL para obter produtos com estoque igual ou abaixo do estoque mínimo
$sql = "SELECT ProdutoID, NomeProduto, Estoque, estoque_min FROM produto WHERE Estoque <= estoque_min";
$result = $conn->query($sql);

$products = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    // Se não houver produtos com estoque baixo, retornar uma lista vazia
    $products = array();
}

// Fechar a conexão com o banco de dados
$conn->close();

// Retornar os produtos em formato JSON
echo json_encode($products);
?>
