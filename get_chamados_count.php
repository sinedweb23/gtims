<?php
// Inclui o arquivo de configuração do banco de dados
require_once('chamado/config1.php');

header('Content-Type: application/json');

// Consulta SQL para contar chamados abertos
$sql = "SELECT COUNT(*) AS count FROM chamados WHERE status = 'Aberto'";

$result = $conn->query($sql);

// Obtém o resultado
$count = 0;
if ($result) {
    $row = $result->fetch_assoc();
    $count = $row['count'];
}

// Retorna o número de chamados abertos em formato JSON
echo json_encode($count);
?>
