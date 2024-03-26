<?php
include 'config.php';

// Verifica se o formulário de devolução foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['emprestimoID'])) {
    $emprestimoID = $_POST['emprestimoID'];
    
    // Obtém os IDs dos chromebooks emprestados
    $sql_chromebooks_ids = "SELECT ChromebookID, DataEmprestimo, HoraEmprestimo, Usuario FROM emprestimos WHERE ID = $emprestimoID";
    $result = $conn->query($sql_chromebooks_ids);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $chromebookID = $row['ChromebookID'];
            $dataEmprestimo = $row['DataEmprestimo'];
            $horaEmprestimo = $row['HoraEmprestimo'];
            $usuario = $row['Usuario'];
            
            // Inicia a transação
            $conn->begin_transaction();
            
            // Atualiza a disponibilidade do Chromebook para disponível
            $sql_update = "UPDATE chromebooks SET Disponivel = 1 WHERE ID = $chromebookID";
            if ($conn->query($sql_update) !== TRUE) {
                echo "Erro ao atualizar disponibilidade do chromebook: " . $conn->error;
                $conn->rollback(); // Desfaz a transação em caso de erro
                exit;
            }
            
            // Move o registro para a tabela cblmovimentacoes
            $sql_move = "INSERT INTO cbmovimentacoes (ChromebookID, DataEmprestimo, HoraEmprestimo, Usuario, DataHoraDevolucao) 
                         VALUES ('$chromebookID', '$dataEmprestimo', '$horaEmprestimo', '$usuario', NOW())";
            if ($conn->query($sql_move) !== TRUE) {
                echo "Erro ao mover registro para cblmovimentacoes: " . $conn->error;
                $conn->rollback(); // Desfaz a transação em caso de erro
                exit;
            }
            
            // Remove o registro da tabela emprestimos
            $sql_delete = "DELETE FROM emprestimos WHERE ID = $emprestimoID";
            if ($conn->query($sql_delete) !== TRUE) {
                echo "Erro ao remover empréstimo: " . $conn->error;
                $conn->rollback(); // Desfaz a transação em caso de erro
                exit;
            }
            
            // Confirma a transação
            $conn->commit();
            
            echo "Ativo devolvido com sucesso!";
            exit; // Encerra o script após a devolução
        }
    } else {
        echo "Erro ao obter ID dos chromebooks emprestados";
    }
}

// Verifica se o formulário de empréstimo foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['chromebookIDs'])) {
    $chromebookIDs = $_POST['chromebookIDs'];
    $nomeUsuario = $_POST['nome'];
    $dataEmprestimo = $_POST['data'];
    $horaEmprestimo = $_POST['hora'];

    // Inicia a transação
    $conn->begin_transaction();
    
    // Insere os dados do empréstimo na tabela emprestimos
    foreach ($chromebookIDs as $chromebookID) {
        $sql_insert = "INSERT INTO emprestimos (ChromebookID, DataEmprestimo, HoraEmprestimo, Usuario) 
                       VALUES ('$chromebookID', '$dataEmprestimo', '$horaEmprestimo', '$nomeUsuario')";
        if ($conn->query($sql_insert) !== TRUE) {
            echo "Erro ao emprestar os chromebooks: " . $conn->error;
            $conn->rollback(); // Desfaz a transação em caso de erro
            exit;
        }
        
        // Atualiza a disponibilidade do Chromebook para indisponível
        $sql_update = "UPDATE chromebooks SET Disponivel = 0 WHERE ID = $chromebookID";
        if ($conn->query($sql_update) !== TRUE) {
            echo "Erro ao atualizar disponibilidade do Chromebook: " . $conn->error;
            $conn->rollback(); // Desfaz a transação em caso de erro
            exit;
        }
    }
    
    // Confirma a transação
    $conn->commit();
    
    echo "chromebooks emprestados com sucesso!";
    exit; // Encerra o script após o empréstimo
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Ativos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1, h2 {
            margin-bottom: 10px;
        }
        form {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .column {
            float: left;
            width: 12.5%;
        }
        .row:after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <h1>Gerenciamento de Ativos</h1>

    <h2>Empréstimo de Ativos</h2>
    <form action="" method="post">
        <label for="chromebookIDs">Selecionar Ativo:</label><br>
        <div class="row">
            <?php
            // Seleciona os chromebooks disponíveis
            $sql = "SELECT * FROM chromebooks WHERE Disponivel = 1";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $count = 0;
                while($row = $result->fetch_assoc()) {
                    if ($count % 8 == 0 && $count != 0) {
                        echo "</div><div class='row'>";
                    }
                    echo "<div class='column'>";
                    echo "<input type='checkbox' name='chromebookIDs[]' value='".$row['ID']."'>".$row['Nome']."<br>";
                    echo "</div>";
                    $count++;
                }
            }
            ?>
        </div>
        <label for="nome">Nome do Usuário:</label>
        <input type="text" id="nome" name="nome" required><br>
        <label for="data">Data do Empréstimo:</label>
        <input type="date" id="data" name="data" required><br>
        <label for="hora">Hora do Empréstimo:</label>
        <input type="time" id="hora" name="hora" required><br>
        <button type="submit">Emprestar</button>
    </form>

    <h2>Ativos Emprestados</h2>
    <table>
        <thead>
            <tr>
                <th>Ativo</th>
                <th>Data do Empréstimo</th>
                <th>Hora do Empréstimo</th>
                <th>Nome do Usuário</th>
                <th>Devolução</th>
            </tr>
        </thead>
        <tbody id="chromebooksLoanBody">
            <?php
            // Seleciona os chromebooks emprestados.
            $sql = "SELECT chromebooks.Nome AS NomeChromebook, emprestimos.* FROM chromebooks INNER JOIN emprestimos ON chromebooks.ID = emprestimos.ChromebookID";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['NomeChromebook']."</td>";
                    echo "<td>".$row['DataEmprestimo']."</td>";
                    echo "<td>".$row['HoraEmprestimo']."</td>";
                    echo "<td>".$row['Usuario']."</td>";
                    echo "<td><button onclick='returnChromebook(".$row['ID'].")'>Devolver</button></td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>

    <script>
        function returnChromebook(emprestimoID) {
            if (confirm('Tem certeza que deseja devolver este Chromebook?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'emprestimo.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        alert(xhr.responseText);
                        location.reload(); // Atualiza a página após devolver o Chromebook
                    } else {
                        console.error('Erro ao devolver Chromebook: ' + xhr.statusText);
                    }
                };
                xhr.onerror = function () {
                    console.error('Erro de rede');
                };
                xhr.send('emprestimoID=' + emprestimoID);
            }
        }
    </script>
</body>
</html>
