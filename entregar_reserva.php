<?php
include 'db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$ids = $data['ids'];
$entregue_para = $data['entregue_para'];

try {
    $conn_gestao->beginTransaction();
    foreach ($ids as $id) {
        // Atualizar status da reserva
        $sql = "UPDATE reservas SET status = 'entregue' WHERE id = :id";
        $stmt = $conn_gestao->prepare($sql);
        $stmt->execute(['id' => $id]);

        // Inserir registro de entrega
        $sql = "INSERT INTO entregas (reserva_id, entregue_para) VALUES (:reserva_id, :entregue_para)";
        $stmt = $conn_gestao->prepare($sql);
        $stmt->execute(['reserva_id' => $id, 'entregue_para' => $entregue_para]);
    }
    $conn_gestao->commit();
    echo 'success';
} catch (Exception $e) {
    $conn_gestao->rollBack();
    echo 'error: ' . $e->getMessage();
}
?>
