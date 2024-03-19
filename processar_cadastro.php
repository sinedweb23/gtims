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

    // Preparando a consulta SQL para inserir os dados na tabela de log_vendas
    $sql = "INSERT INTO log_vendas (produto_id, quantidade_vendida, numero_serie, observacao) VALUES ((SELECT id FROM produtos WHERE nome = ?), ?, ?, ?)";

    // Preparando a declaração
    $stmt = $conn->prepare($sql);

    // Vinculando parâmetros
    $stmt->bind_param("siss", $nome, $quantidade, $numeroserie, $observacao);

    // Executando a declaração
    if ($stmt->execute()) {
        echo "Venda registrada com sucesso!";
    } else {
        echo "Erro ao registrar a venda: " . $stmt->error;
    }

    // Fechando a declaração
    $stmt->close();
}

// Fechando a conexão
$conn->close();
?>
