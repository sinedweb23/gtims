<?php
include 'db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$ids = $data['ids'];

try {
    $conn_gestao->beginTransaction();
    foreach ($ids as $id) {
        $sql = "SELECT * FROM reservas WHERE id = :id";
        $stmt = $conn_gestao->prepare($sql);
        $stmt->execute(['id' => $id]);
        $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

        // Inserir registro de devolução total
        $sql = "INSERT INTO devolucoes (reserva_id, ChromebookID, DataEmprestimo, HoraEmprestimo, email_professor, DataHoraDevolucao)
                VALUES (:reserva_id, :ChromebookID, :DataEmprestimo, :HoraEmprestimo, :email_professor, NOW())";
        $stmt = $conn_gestao->prepare($sql);
        $stmt->execute([
            'reserva_id' => $id,
            'ChromebookID' => $reserva['ativo_id'],
            'DataEmprestimo' => $reserva['data_reserva'],
            'HoraEmprestimo' => $reserva['hora_retirada'],
            'email_professor' => $reserva['email_professor']
        ]);

        // Atualizar o status da reserva para devolvido
        $sql = "UPDATE reservas SET status = 'devolvido' WHERE id = :id";
        $stmt = $conn_gestao->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
    $conn_gestao->commit();
    echo 'success';
} catch (Exception $e) {
    $conn_gestao->rollBack();
    echo 'error: ' . $e->getMessage();
}
?>
