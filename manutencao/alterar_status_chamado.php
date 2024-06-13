<?php
// Inclui o arquivo de configuração do banco de dados
require_once('config1.php');

session_start();

// Verifica se os dados foram recebidos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os parâmetros do POST
    $chamado_id = $_POST['chamado_id'];
    $status = $_POST['status'];
    $observacao = isset($_POST['observacao']) ? $_POST['observacao'] : null;
    $requisicoes = isset($_POST['requisicoes']) ? $_POST['requisicoes'] : null;

    // Sanitiza os dados recebidos
    $chamado_id = mysqli_real_escape_string($conn, $chamado_id);
    $status = mysqli_real_escape_string($conn, $status);
    $observacao = mysqli_real_escape_string($conn, $observacao);
    $requisicoes = mysqli_real_escape_string($conn, $requisicoes);

    // Lógica de atualização de status
    if ($status == 'Fechado') {
        $sql = "UPDATE chamados SET status = '$status', solucao = '$observacao', requisicoes = NULL WHERE id = '$chamado_id'";
    } elseif ($status == 'Aguardando Material') {
        $sql = "UPDATE chamados SET status = '$status', requisicoes = '$requisicoes' WHERE id = '$chamado_id'";
    } elseif ($status == 'Aguardando Aprovação') {
        $sql = "UPDATE chamados SET status = '$status' WHERE id = '$chamado_id'";
    } elseif ($status == 'Comprado') {
        $sql = "UPDATE chamados SET status = 'Aberto', requisicoes = NULL WHERE id = '$chamado_id'";
    } elseif ($status == 'Reprovado') {
        $sql = "UPDATE chamados SET status = '$status', observacao = '$observacao' WHERE id = '$chamado_id'";
    } else {
        $sql = "UPDATE chamados SET status = '$status', solucao = '$observacao' WHERE id = '$chamado_id'";
    }

    if ($conn->query($sql) === TRUE) {
        // Retorna uma resposta JSON de sucesso
        echo json_encode(array('success' => true));
    } else {
        // Retorna uma resposta JSON de erro, se houver
        echo json_encode(array('success' => false, 'error' => $conn->error));
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
}
?>
