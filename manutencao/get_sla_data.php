<?php
include('config1.php');

function calculateSLA($start_date, $end_date, $sla_hours) {
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $interval = $start->diff($end);
    $total_hours = ($interval->days * 24) + $interval->h + ($interval->i / 60);
    $sla_percentage = max(0, min(100, (1 - ($total_hours / $sla_hours)) * 100));
    return ['hours' => round($total_hours, 2), 'percentage' => round($sla_percentage, 2)];
}

try {
    // Verifica se a conexão foi estabelecida corretamente
    if ($conn->connect_error) {
        throw new Exception("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Obter o valor do SLA configurado
    $sql = "SELECT sla_hours FROM configuracoes LIMIT 1";
    $result = $conn->query($sql);
    $config = $result->fetch_assoc();
    $sla_hours = $config['sla_hours'];

    $sql = "SELECT data_abertura, data_fechamento FROM chamados WHERE status = 'fechado' ORDER BY data_fechamento DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sla = calculateSLA($row['data_abertura'], $row['data_fechamento'], $sla_hours);
    } else {
        $sla = ['hours' => 0, 'percentage' => 0];
    }

    echo json_encode(['sla' => $sla]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
