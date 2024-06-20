<?php
session_start();
include('config1.php'); // Inclua a conexão com o banco de dados

if ($_SESSION['permissao'] != 'admin') {
    header("Location: erro_permissao.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sla_hours = intval($_POST['sla_hours']);

    $query = "UPDATE configuracoes SET sla_hours = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $sla_hours);
    $stmt->execute();
    $stmt->close();
}

// Obter o valor atual do SLA
$query = "SELECT sla_hours FROM configuracoes LIMIT 1";
$result = mysqli_query($conn, $query);
$config = mysqli_fetch_assoc($result);
$sla_hours = $config['sla_hours'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar SLA</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Configurar Tempo do SLA</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="configurar_sla.php">
                            <div class="form-group">
                                <label for="sla_hours">Tempo do SLA (em horas):</label>
                                <input type="number" class="form-control" id="sla_hours" name="sla_hours" value="<?php echo $sla_hours; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
