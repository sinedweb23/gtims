<?php
include('config1.php'); // Inclua a conexão com o banco de dados

// Consulta para contar o número de chamados com status "Aguardando Material"
$sql = "SELECT COUNT(*) as count FROM chamados WHERE status = 'Aguardando Material'";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    echo json_encode(['count' => $row['count']]);
} else {
    echo json_encode(['count' => 0]);
}

$conn->close();
?>
