<?php
include 'db_connect.php';

if (isset($_GET['ids'])) {
    $ids = explode(',', $_GET['ids']);
    
    foreach ($ids as $id) {
        // Obter o ativo_id da reserva
        $stmt = $conn_gestao->prepare("SELECT ativo_id FROM reservas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($reserva) {
            $ativo_id = $reserva['ativo_id'];

            // Excluir a reserva
            $stmt = $conn_gestao->prepare("DELETE FROM reservas WHERE id = :id");
            if ($stmt->execute(['id' => $id])) {
                // Atualizar o status do ativo para disponível
                $stmt = $conn_gestao->prepare("UPDATE chromebooks SET status = 'disponivel' WHERE id = :ativo_id");
                $stmt->execute(['ativo_id' => $ativo_id]);
            } else {
                echo 'error';
                exit;
            }
        } else {
            echo 'error';
            exit;
        }
    }

    echo 'success';
} else {
    echo 'error';
}
?>
