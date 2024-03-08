<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se é um empréstimo ou uma devolução
    if (isset($_POST['chromebookID']) && isset($_POST['nome']) && isset($_POST['data']) && isset($_POST['hora'])) {
        // Emprestar Chromebook
        $chromebookID = $_POST['chromebookID'];
        $nomeUsuario = $_POST['nome'];
        $dataEmprestimo = $_POST['data'];
        $horaEmprestimo = $_POST['hora'];

        $sql = "UPDATE Chromebooks SET Disponivel = false WHERE ID = $chromebookID";
        $conn->query($sql);

        $sql = "INSERT INTO emprestimos (ChromebookID, NomeUsuario, DataEmprestimo, HoraEmprestimo) VALUES ($chromebookID, '$nomeUsuario', '$dataEmprestimo', '$horaEmprestimo')";
        $conn->query($sql);

        echo "Chromebook emprestado com sucesso!";
    } elseif (isset($_POST['emprestimoID'])) {
        // Devolver Chromebook
        $emprestimoID = $_POST['emprestimoID'];

        $sql = "UPDATE emprestimos SET DataDevolucao = CURRENT_DATE, HoraDevolucao = CURRENT_TIME WHERE ID = $emprestimoID";
        $conn->query($sql);

        // Registra na tabela LogMovimentacoes
        $sql = "INSERT INTO LogMovimentacoes (ChromebookID, DataHoraEmprestimo, DataHoraDevolucao) SELECT ChromebookID, CONCAT(DataEmprestimo, ' ', HoraEmprestimo), NOW() FROM emprestimos WHERE ID = $emprestimoID";
        $conn->query($sql);

        echo "Chromebook devolvido com sucesso!";
    }
}
?>
