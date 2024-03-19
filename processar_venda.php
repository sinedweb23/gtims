<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto_id = $_POST['produto'];
    $quantidade = $_POST['quantidade'];
    $comprador = $_POST['comprador'];
    $ra = $_POST['ra'];
    $forma_pagamento = $_POST['forma_pagamento'];
    $observacao = $_POST['observacao'];
    $numeroserie = $_POST['numeroserie']; // Adicionando o número de série do produto

    // Consulta SQL para inserir os dados na tabela de log_vendas
    $sql = "INSERT INTO log_vendas (produto_id, quantidade_vendida, comprador, ra, forma_pagamento, observacao, numeroserie) VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Preparando a declaração
    $stmt = $conn->prepare($sql);

    // Vinculando parâmetros
    $stmt->bind_param("iisssss", $produto_id, $quantidade, $comprador, $ra, $forma_pagamento, $observacao, $numeroserie);

    // Executando a declaração
    if ($stmt->execute()) {
        echo "Venda registrada com sucesso!";
    } else {
        echo "Erro ao registrar a venda: " . $conn->error;
    }

    // Fechando a declaração e a conexão
    $stmt->close();
    $conn->close();
}
?>
