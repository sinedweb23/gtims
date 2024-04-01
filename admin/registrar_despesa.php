<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtendo os dados do formulário
    $id_centro_custos = $_POST["id_centro_custos_despesa"];
    $descricao = $_POST["descricao_despesa"];
    $valor = $_POST["valor_despesa"];
    $data = $_POST["data_despesa"];

    // Inserindo os dados na tabela despesas
    $sql = "INSERT INTO despesas (id_centro_custos, descricao, valor, data) VALUES ('$id_centro_custos', '$descricao', '$valor', '$data')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Despesa registrada com sucesso.";
    } else {
        echo "Erro ao registrar despesa: " . $conn->error;
    }
}

$conn->close();
?>
