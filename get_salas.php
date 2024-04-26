<?php
// Inclua o arquivo de configuração do banco de dados
require_once('config.php');

// Verifica se o ID do andar foi enviado
if (isset($_GET['id_andar']) && isset($_GET['andar_nome'])) {
    $id_andar = $_GET['id_andar'];
    $andar_nome = $_GET['andar_nome'];
    
    // Consulta o banco de dados para obter as salas do andar especificado
    $sql = "SELECT id, nome FROM salas WHERE id_andar = $id_andar";
    $result = $conn->query($sql);

    $salas = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $salas[] = array('id' => $row['id'], 'nome' => $andar_nome . ' - ' . $row['nome']); // Adiciona o nome do andar à frente do nome da sala
        }
    }

    // Retorna as salas em formato JSON
    header('Content-Type: application/json');
    echo json_encode($salas);
} else {
    // Se o ID do andar não foi enviado, retorna um erro
    header('HTTP/1.1 400 Bad Request');
    echo 'ID do andar ou nome do andar não fornecido.';
}
?>
