<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtendo os dados do formulário
    $id_centro_custos = $_POST["id_centro_custos"];
    $orcamento = $_POST["orcamento"];
    $data_inicio = $_POST["data_inicio"];
    $data_fim = $_POST["data_fim"];

    // Inserindo os dados na tabela orcamento_area
    $sql = "INSERT INTO orcamento_area (id_centro_custos, orcamento, data_inicio, data_fim) VALUES ('$id_centro_custos', '$orcamento', '$data_inicio', '$data_fim')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Orçamento da área definido com sucesso.";
    } else {
        echo "Erro ao definir orçamento da área: " . $conn->error;
    }
}

$conn->close();
?>
