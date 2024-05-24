<?php
include 'db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$ids = $data['ids'];

try {
    foreach ($ids as $id) {
        $stmt = $conn_gestao->prepare("UPDATE reservas SET status = 'retirado' WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
    echo 'success';
} catch (PDOException $e) {
    echo 'error';
}
?>
