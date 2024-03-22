<?php
include 'config.php';

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperando os dados do formulário
    $nome = $_POST['nome'];
    $valor = $_POST['valor'];
    $quantidade = $_POST['quantidade'];
    $link = $_POST['link_img'];
    $numeroserie = $_POST['numeroserie'];
    $observacao = $_POST['observacao'];

    // Preparando a consulta SQL para inserir os dados na tabela de produtos
    $sql = "INSERT INTO produtos (nome, quantidade, link_img, valor, observacao, numeroserie) VALUES (?, ?, ?, ?, ?, ?)";

    // Preparando a declaração
    $stmt = $conn->prepare($sql);

    // Vinculando parâmetros
    $stmt->bind_param("sisdss", $nome, $quantidade, $link, $valor, $observacao, $numeroserie);

    // Executando a declaração
    if ($stmt->execute()) {
        echo "Produto cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar o produto: " . $stmt->error;
    }

    // Fechando a declaração e a conexão
    $stmt->close();
    $conn->close();
}
?>
