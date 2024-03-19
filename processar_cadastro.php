<?php
include 'config.php';

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperando os dados do formulário
    $nome = $_POST['nome'];
    $valor = $_POST['valor'];
    $quantidade = $_POST['quantidade'];
    $numeroserie = $_POST['numeroserie'];
    $observacao = $_POST['observacao'];

    // Preparando a consulta SQL para inserir os dados na tabela de produtos
    $sql_produto = "INSERT INTO produtos (nome, quantidade, valor, observacao, numeroserie) VALUES (?, ?, ?, ?, ?)";

    // Preparando a declaração para inserir o produto
    $stmt_produto = $conn->prepare($sql_produto);

    // Vinculando parâmetros para inserir o produto
    $stmt_produto->bind_param("sidsis", $nome, $quantidade, $valor, $observacao, $numeroserie);

    // Executando a declaração para inserir o produto
    if ($stmt_produto->execute()) {
        echo "Produto cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar o produto: " . $stmt_produto->error;
    }

    // Fechando a declaração para inserir o produto
    $stmt_produto->close();

    // Fechando a conexão
    $conn->close();
}
?>
