<?php
require_once('config1.php');

$sql = "SELECT COUNT(*) as count FROM chamados WHERE status = 'Aberto'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo json_encode(['count' => $row['count']]);
?>
