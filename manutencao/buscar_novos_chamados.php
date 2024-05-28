<?php
// Simulação de busca de novos chamados (substitua por sua lógica real de busca)
$novosChamados = array(
    array(
        'id' => 1,
        'solicitante' => 'João',
        'nome_sala' => 'Sala 1',
        'nome_defeito' => 'Defeito 1',
        'prioridade' => 'Alta',
        'observacao' => 'Observação 1',
        'status' => 'Aberto',
        'data_abertura' => '2024-04-23 10:00:00'
    ),
    array(
        'id' => 2,
        'solicitante' => 'Maria',
        'nome_sala' => 'Sala 2',
        'nome_defeito' => 'Defeito 2',
        'prioridade' => 'Baixa',
        'observacao' => 'Observação 2',
        'status' => 'Aberto',
        'data_abertura' => '2024-04-23 10:30:00'
    )
);

// Retorna os dados dos novos chamados em formato JSON
echo json_encode($novosChamados);
?>
