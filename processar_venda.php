<?php
include 'config.php';

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperando os dados do formulário
    $produto_id = $_POST['produto'];
    $quantidade_vendida = $_POST['quantidade'];
    $comprador = isset($_POST['comprador']) ? $_POST['comprador'] : '';
    $ra = $_POST['ra'];
    $forma_pagamento = $_POST['forma_pagamento'];
    $observacao = $_POST['observacao'];

    // Verifica se o nome do comprador não está vazio
    if (empty($comprador)) {
        echo "Por favor, forneça o nome do comprador.";
        exit;
    }

    // Iniciando transação
    if (!$conn->begin_transaction()) {
        echo "Erro ao iniciar a transação: " . $conn->error;
        exit;
    }

    // Consulta SQL para obter a quantidade atual do produto
    $sql_select = "SELECT quantidade, numeroserie FROM produtos WHERE id = ?";
    $stmt_select = $conn->prepare($sql_select);
    if (!$stmt_select) {
        echo "Erro ao preparar a consulta: " . $conn->error;
        exit;
    }
    $stmt_select->bind_param("i", $produto_id);
    if (!$stmt_select->execute()) {
        echo "Erro ao executar a consulta: " . $stmt_select->error;
        exit;
    }
    $stmt_select->bind_result($quantidade, $produto_numeroserie);
    $stmt_select->fetch();
    $stmt_select->close();

    // Verifica se há estoque suficiente
    if ($quantidade >= $quantidade_vendida) {
        // Atualiza o estoque na tabela de produtos
        $nova_quantidade = $quantidade - $quantidade_vendida;
        $sql_update = "UPDATE produtos SET quantidade = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        if (!$stmt_update) {
            echo "Erro ao preparar a consulta de atualização: " . $conn->error;
            exit;
        }
        $stmt_update->bind_param("ii", $nova_quantidade, $produto_id);
        if (!$stmt_update->execute()) {
            echo "Erro ao executar a consulta de atualização: " . $stmt_update->error;
            exit;
        }
        $stmt_update->close();

        // Insere os detalhes da venda na tabela de log de vendas
        $sql_insert = "INSERT INTO log_vendas (produto_id, quantidade_vendida, comprador, ra, forma_pagamento, observacao, produto_numeroserie) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        if (!$stmt_insert) {
            echo "Erro ao preparar a consulta de inserção: " . $conn->error;
            exit;
        }
        $stmt_insert->bind_param("iisssss", $produto_id, $quantidade_vendida, $comprador, $ra, $forma_pagamento, $observacao, $produto_numeroserie);
        if (!$stmt_insert->execute()) {
            echo "Erro ao executar a consulta de inserção: " . $stmt_insert->error;
            exit;
        }
        $stmt_insert->close();

        // Confirma a transação
        if (!$conn->commit()) {
            echo "Erro ao confirmar a transação: " . $conn->error;
            exit;
        }

        echo "Venda realizada com sucesso!";
    } else {
        // Rollback em caso de estoque insuficiente
        if (!$conn->rollback()) {
            echo "Erro ao reverter a transação: " . $conn->error;
            exit;
        }
        echo "Estoque insuficiente!";
    }
}

// Fechar conexão
$conn->close();
?>
