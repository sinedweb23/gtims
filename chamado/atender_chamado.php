<?php
require_once('config1.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $chamado_id = $_POST['chamado_id'];

    // Verifica se o ID do chamado foi fornecido
    if (isset($chamado_id)) {
        // Atualiza o status do chamado para "Atendendo"
        $sql = "UPDATE chamados SET status = 'Atendendo' WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $chamado_id);
            if ($stmt->execute()) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "Erro ao atualizar o status do chamado."]);
            }
            $stmt->close();
        } else {
            echo json_encode(["success" => false, "error" => "Erro ao preparar a consulta SQL."]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "ID do chamado não fornecido."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Método de solicitação inválido."]);
}

$conn->close();
?>
