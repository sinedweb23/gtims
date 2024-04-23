<?php
// Inclui o arquivo de configuração do banco de dados
require_once('config.php');

// Consulta o banco de dados para obter os chamados abertos
$sql = "SELECT c.id, c.nome AS solicitante, st.Setor AS nome_setor, d.nome AS nome_defeito, d.prioridade, c.observacao, c.status, c.data_abertura
        FROM chamados c
        INNER JOIN setor st ON c.id_sala = st.SetorID
        INNER JOIN defeitos d ON c.id_defeito = d.id
        WHERE c.status = 'Aberto'  -- Verifica se o chamado está aberto
        ORDER BY c.data_abertura DESC";
$result = $conn->query($sql);

// Array para armazenar os chamados
$chamados = [];

// Verifica se a consulta retornou resultados
if ($result->num_rows > 0) {
    // Itera sobre os resultados e armazena os dados dos chamados no array
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
        $chamados[] = $chamado;
    }
}

// Retorna os chamados no formato JSON
echo json_encode($chamados);
