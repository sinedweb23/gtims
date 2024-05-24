<?php
include 'db_connect.php';

$andar_id = $_GET['andar_id'];
$stmt = $conn_chamado->prepare("SELECT * FROM salas WHERE id_andar = :andar_id");
$stmt->execute(['andar_id' => $andar_id]);
$salas = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($salas);
?>
