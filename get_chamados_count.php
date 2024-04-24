<?php
// Include do arquivo de configuração para conexão com o banco de dados
include 'admin/config1.php';

// Consulta SQL para contar o número de chamados abertos
$sql = "SELECT COUNT(*) AS count FROM chamados WHERE status = 'aberto'";
$result = $conn->query($sql);

// Verifica se a consulta foi bem-sucedida e se há pelo menos um resultado
if ($result && $result->num_rows > 0) {
    // Obtém o número de chamados abertos e retorna como resposta
    $row = $result->fetch_assoc();
    echo $row['count'];
} else {
    echo '0'; // Retorna 0 se não houver chamados abertos
}
?>
