<?php
// Inclui o arquivo de configuração do banco de dados
require_once('config1.php');
session_start();

// Verifica se os dados do formulário foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['chamado_id']) && isset($_POST['solucao'])) {
    $chamado_id = $_POST['chamado_id'];
    $solucao = $_POST['solucao'];
    
    // Obtém a data e hora atual
    $data_fechamento = date('Y-m-d H:i:s');
    
    // Obtém o email do usuário logado
    $usuario_fechamento = $_SESSION['email'];
    
    // Atualiza o status do chamado para "Fechado", a data de fechamento, a solução e o usuário que fechou o chamado no banco de dados
    $sql = "UPDATE chamados SET status = 'Fechado', data_fechamento = '$data_fechamento', solucao = '$solucao', usuario = '$usuario_fechamento' WHERE id = $chamado_id";
    if ($conn->query($sql) === TRUE) {
        // Retorna uma resposta JSON indicando sucesso
        echo json_encode(array('success' => true));
    } else {
        // Retorna uma resposta JSON indicando erro
        echo json_encode(array('success' => false, 'error' => 'Erro ao fechar o chamado: ' . $conn->error));
    }
} else {
    // Retorna uma resposta JSON indicando erro de dados do formulário
    echo json_encode(array('success' => false, 'error' => 'Dados do formulário ausentes'));
}
?>
