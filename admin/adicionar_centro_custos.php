<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtendo os dados do formulário
    $nome = $_POST["nome"];
    $responsavel = $_POST["responsavel"];
    $descricao = $_POST["descricao"];

    // Inserindo os dados na tabela centro_custos
    $sql = "INSERT INTO centro_custos (nome, responsavel, descricao) VALUES ('$nome', '$responsavel', '$descricao')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Centro de custos adicionado com sucesso.";
    } else {
        echo "Erro ao adicionar centro de custos: " . $conn->error;
    }
}

$conn->close();
?>
