<?php
include 'config.php';

$chromebookID = $_POST['chromebookID'];

$sql = "UPDATE chromebooks SET Disponivel = true WHERE ID = $chromebookID";
$conn->query($sql);

$sql = "SELECT * FROM emprestimos WHERE chromebookID = $chromebookID AND DataDevolucao IS NULL";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $emprestimoID = $row['ID'];
    $sql = "UPDATE emprestimos SET DataDevolucao = CURRENT_DATE, HoraDevolucao = CURRENT_TIME WHERE ID = $emprestimoID";
    $conn->query($sql);

    // Registra na tabela LogMovimentacoes
    $sql = "INSERT INTO LogMovimentacoes (ChromebookID, DataHoraEmprestimo, DataHoraDevolucao) VALUES ($chromebookID, '{$row['DataEmprestimo']} {$row['HoraEmprestimo']}', NOW())";
    $conn->query($sql);
}

echo "Chromebook devolvido com sucesso!";
?>
