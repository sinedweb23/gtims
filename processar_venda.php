<?php
include 'config.php';

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperando os dados do formulário
    $produto_id = $_POST['produto'];
    $quantidade_vendida = $_POST['quantidade']; // Nova variável para a quantidade vendida
    $comprador = isset($_POST['comprador']) ? $_POST['comprador'] : ''; // Verifica se o comprador foi enviado
    $ra = $_POST['ra'];
    $forma_pagamento = $_POST['forma_pagamento'];
    $observacao = $_POST['observacao'];

    // Verifica se o nome do comprador não está vazio
    if (empty($comprador)) {
        echo "Por favor, forneça o nome do comprador.";
        exit; // Encerra o script se o nome do comprador estiver vazio
    }

    // Iniciando transação
    $conn->begin_transaction();

    // Consulta SQL para obter a quantidade atual do produto
    $sql_select = "SELECT quantidade FROM produtos WHERE id = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $produto_id);
    $stmt_select->execute();
    $stmt_select->bind_result($quantidade);
    $stmt_select->fetch();
    $stmt_select->close();

    // Verifica se há estoque suficiente
    if ($quantidade >= $quantidade_vendida) {
        // Atualiza o estoque na tabela de produtos
        $nova_quantidade = $quantidade - $quantidade_vendida; // Reduz a quantidade em quantidade_vendida
        $sql_update = "UPDATE produtos SET quantidade = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ii", $nova_quantidade, $produto_id,);
        $stmt_update->execute();
        $stmt_update->close();

        // Insere os detalhes da venda na tabela de log de vendas
        $sql_insert = "INSERT INTO log_vendas (produto_id, quantidade_vendida, comprador, ra, forma_pagamento, observacao, produto_numeroserie) VALUES (?, ?, ?, ?, ?, ?,?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iissss", $produto_id, $quantidade_vendida, $comprador, $ra, $forma_pagamento, $observacao, $produto_numeroserie); // Corrigido o tipo de dado para comprador
        $stmt_insert->execute();
        $stmt_insert->close();

        // Confirma a transação
        $conn->commit();

        echo "Venda realizada com sucesso!";
    } else {
        // Rollback em caso de estoque insuficiente
        $conn->rollback();
        echo "Estoque insuficiente!";
    }
}

// Fechar conexão
$conn->close();

