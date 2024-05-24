<?php
include 'db_connect.php';

$data_reserva = $_GET['data_reserva'];
$hora_retirada = $_GET['hora_retirada'];
$hora_devolucao = $_GET['hora_devolucao'];

$sql = "SELECT COUNT(*) as quantidade FROM chromebooks WHERE ID NOT IN (
            SELECT ativo_id FROM reservas 
            WHERE data_reserva = :data_reserva
            AND (
                (hora_retirada < :hora_devolucao AND hora_devolucao > :hora_retirada)
            )
        )";
$stmt = $conn_gestao->prepare($sql);
$stmt->execute([
    'data_reserva' => $data_reserva,
    'hora_retirada' => $hora_retirada,
    'hora_devolucao' => $hora_devolucao
]);
$quantidade = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($quantidade);
?>
