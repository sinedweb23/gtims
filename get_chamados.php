<?php
// Inclui o arquivo de configuração do banco de dados
require_once('config.php');

// Consulta o banco de dados para obter os gestao_ti abertos
$sql = "SELECT c.id, c.nome AS solicitante, s.nome AS nome_sala, d.nome AS nome_defeito, d.prioridade, c.observacao, c.status, c.data_abertura
        FROM gestao_ti c
        INNER JOIN salas s ON c.id_sala = s.id
        INNER JOIN defeitos d ON c.id_defeito = d.id
        WHERE c.status = 'Aberto'  -- Verifica se o chamado está aberto
        ORDER BY c.data_abertura DESC";
$result = $conn->query($sql);

// Array para armazenar os gestao_ti
$gestao_ti = [];

// Verifica se a consulta retornou resultados
if ($result->num_rows > 0) {
    // Itera sobre os resultados e armazena os dados dos gestao_ti no array
    while ($row = $result->fetch_assoc()) {
        $chamado = [
            'id' => $row['id'],
            'solicitante' => $row['solicitante'],
            'nome_sala' => $row['nome_sala'],
            'nome_defeito' => $row['nome_defeito'],
            'prioridade' => $row['prioridade'],
            'observacao' => $row['observacao'],
            'status' => $row['status'],
            'data_abertura' => $row['data_abertura']
        ];
        // Adiciona o chamado ao array
        $gestao_ti[] = $chamado;
    }
}

// Retorna os gestao_ti no formato JSON
echo json_encode($gestao_ti);
