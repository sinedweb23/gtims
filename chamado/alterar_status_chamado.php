<?php
require_once('config1.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['chamado_id'];
    $status = $_POST['status'];

    if ($status == 'Fechado') {
        $sql = "UPDATE chamados SET status = ?, data_fechamento = NOW() WHERE id = ?";
    } else {
        $sql = "UPDATE chamados SET status = ?, data_fechamento = NULL WHERE id = ?";
    }

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $status, $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
?>
