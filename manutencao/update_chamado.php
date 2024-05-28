<?php
require_once('config1.php'); // Garanta que este arquivo inclua a conexão com o banco de dados

$chamado_id = $_POST['chamado_id'];
$status = $_POST['status'];
$solucao = $_POST['solucao'] ?? ''; // Usar operador de coalescência nula para lidar com casos onde solução não é enviada

$response = [];

if ($status === 'Fechado' && trim($solucao) === '') {
    $response['success'] = false;
    $response['error'] = 'A solução não pode estar vazia.';
} else {
    $data_fechamento = null;
    if ($status === 'Fechado') {
        $data_fechamento = date('Y-m-d H:i:s'); // Pega a data e hora atual
    }

    $sql = "UPDATE chamados SET status = ?, solucao = ?, data_fechamento = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $status, $solucao, $data_fechamento, $chamado_id);
    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['error'] = 'Falha ao atualizar o chamado.';
    }
    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);
?>
