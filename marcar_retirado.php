<?php
include 'db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$ids = $data['ids'];

try {
    $conn_gestao->beginTransaction();
    foreach ($ids as $id) {
        $sql = "UPDATE reservas SET status = 'retirado' WHERE id = :id";
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
