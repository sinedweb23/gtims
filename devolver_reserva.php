<?php
include 'db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$ids = $data['ids'];
$status = $data['status'];
$ativos = $data['ativos'] ?? [];

try {
    $conn_gestao->beginTransaction();
    if ($status === 'total') {
        foreach ($ids as $id) {
            // Inserir registro de devolução total antes de atualizar
            $sql = "INSERT INTO devolucoes (reserva_id, tipo, quantidade, observacao) VALUES (:reserva_id, 'total', 1, 'Devolução total dos ativos')";
            $stmt = $conn_gestao->prepare($sql);
            $stmt->execute(['reserva_id' => $id]);

            // Atualizar status da reserva para devolvido
            $sql = "UPDATE reservas SET status = 'devolvido', hora_devolucao = NOW() WHERE id = :id";
            $stmt = $conn_gestao->prepare($sql);
            $stmt->execute(['id' => $id]);
        }
    } else {
        foreach ($ativos as $ativo_id) {
            // Inserir registro de devolução parcial antes de atualizar
            $sql = "INSERT INTO devolucoes (reserva_id, tipo, quantidade, observacao) VALUES (:reserva_id, 'parcial', 1, 'Devolução parcial dos ativos')";
            $stmt = $conn_gestao->prepare($sql);
            $stmt->execute(['reserva_id' => $ativo_id]);

            // Atualizar status da reserva para parcial
            $sql = "UPDATE reservas SET status = 'parcial' WHERE id = :id";
            $stmt = $conn_gestao->prepare($sql);
            $stmt->execute(['id' => $ativo_id]);
        }
    }
    $conn_gestao->commit();
    echo 'success';
} catch (Exception $e) {
    $conn_gestao->rollBack();
    echo 'error: ' . $e->getMessage();
}
?>
