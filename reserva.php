<?php
include 'db_connect.php';

$reserva_success = false;
$reserva_error = false;
$data_reserva = $hora_retirada = $hora_devolucao = $email_username = $andar_id = $sala_id = '';
$ativos_quantidade = 0;
$ativos_disponiveis = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data_reserva = $_POST['data_reserva'];
    $hora_retirada = $_POST['hora_retirada'];
    $hora_devolucao = $_POST['hora_devolucao'];
    $email_username = $_POST['email_username'];
    $email_professor = $email_username . '@morumbisul.com.br';
    $andar_id = $_POST['andar_id'];
    $sala_id = $_POST['sala_id'];
    $ativos_quantidade = $_POST['ativos_quantidade'];

    // Verificar se andar_id e sala_id são válidos
    $stmt = $conn_chamado->prepare("SELECT COUNT(*) FROM andares WHERE id = :andar_id");
    $stmt->execute(['andar_id' => $andar_id]);
    $andar_existe = $stmt->fetchColumn();

    $stmt = $conn_chamado->prepare("SELECT COUNT(*) FROM salas WHERE id = :sala_id AND id_andar = :andar_id");
    $stmt->execute(['sala_id' => $sala_id, 'andar_id' => $andar_id]);
    $sala_existe = $stmt->fetchColumn();

    if ($andar_existe && $sala_existe) {
        // Buscar quantidade de ativos disponíveis
        $sql = "SELECT COUNT(*) as quantidade FROM chromebooks WHERE ID NOT IN (
                    SELECT ativo_id FROM reservas 
                    WHERE data_reserva = :data_reserva
                    AND (
                        (hora_retirada < :hora_devolucao AND hora_devolucao > :hora_retirada)
                    )
                )";
        $stmt = $conn_gestao->prepare($sql);
        $stmt->execute([
            'data_reserva' => $data_reserva,
            'hora_retirada' => $hora_retirada,
            'hora_devolucao' => $hora_devolucao
        ]);
        $ativos_disponiveis = $stmt->fetchColumn();

        if ($ativos_quantidade <= $ativos_disponiveis) {
            // Buscar ativos disponíveis na sequência
            $sql = "SELECT ID FROM chromebooks WHERE ID NOT IN (
                        SELECT ativo_id FROM reservas 
                        WHERE data_reserva = :data_reserva
                        AND (
                            (hora_retirada < :hora_devolucao AND hora_devolucao > :hora_retirada)
                        )
                    ) LIMIT :quantidade";
            $stmt = $conn_gestao->prepare($sql);
            $stmt->bindParam(':data_reserva', $data_reserva);
            $stmt->bindParam(':hora_retirada', $hora_retirada);
            $stmt->bindParam(':hora_devolucao', $hora_devolucao);
            $stmt->bindParam(':quantidade', $ativos_quantidade, PDO::PARAM_INT);
            $stmt->execute();
            $ativos = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($ativos as $ativo_id) {
                $sql = "INSERT INTO reservas (ativo_id, email_professor, andar_id, sala_id, data_reserva, hora_retirada, hora_devolucao) 
                        VALUES (:ativo_id, :email_professor, :andar_id, :sala_id, :data_reserva, :hora_retirada, :hora_devolucao)";
                $stmt = $conn_gestao->prepare($sql);
                $stmt->execute([
                    'ativo_id' => $ativo_id,
                    'email_professor' => $email_professor,
                    'andar_id' => $andar_id,
                    'sala_id' => $sala_id,
                    'data_reserva' => $data_reserva,
                    'hora_retirada' => $hora_retirada,
                    'hora_devolucao' => $hora_devolucao
                ]);
            }
            $reserva_success = true;
        } else {
            $reserva_error = true;
        }
    } else {
        echo "Erro: Andar ou Sala inválido.";
    }
}
?>

<?php if ($reserva_success): ?>
<script>
    showModal(true, <?= $ativos_quantidade ?>, '<?= $data_reserva ?>', '<?= $hora_retirada ?>', '<?= $hora_devolucao ?>');
</script>
<?php elseif ($reserva_error): ?>
<script>
    showModal(false, 0, '', '', '', 'Quantidade indisponível para a data selecionada. Disponível: <?= $ativos_disponiveis ?> ativos.');
</script>
<?php endif; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Reserva de Chromebook</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dataReservaInput = document.getElementById('data_reserva');
            var horaRetiradaInput = document.getElementById('hora_retirada');
            var horaDevolucaoInput = document.getElementById('hora_devolucao');
            var errorMessageDiv = document.getElementById('error_message');

            // Definir a data atual como padrão
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2);
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var today = now.getFullYear() + '-' + month + '-' + day;
            dataReservaInput.value = today;

            // Desativar datas passadas
            dataReservaInput.setAttribute('min', today);

            function updateHoraRetirada() {
                var now = new Date();
                var currentTime = now.toTimeString().substr(0, 5);

                if (dataReservaInput.value === today) {
                    horaRetiradaInput.min = currentTime;
                } else {
                    horaRetiradaInput.min = "07:00";
                }
                horaDevolucaoInput.value = '';
                horaDevolucaoInput.min = '';
            }

            function updateHoraDevolucao() {
                var horaRetiradaValue = horaRetiradaInput.value;
                horaDevolucaoInput.min = horaRetiradaValue;
            }

            dataReservaInput.addEventListener('change', function() {
                updateHoraRetirada();
                horaRetiradaInput.value = '';
                horaDevolucaoInput.value = '';
            });

            horaRetiradaInput.addEventListener('change', updateHoraDevolucao);

            updateHoraRetirada();

            // Função para limitar o ano a 4 dígitos
            function limitYearLength(input) {
                if (input.value.length > 10) {
                    input.value = input.value.slice(0, 10);
                }
            }

            // Evento de input para limitar o ano enquanto o usuário digita
            dataReservaInput.addEventListener('input', function() {
                limitYearLength(this);
            });
        });

        function fetchAvailableAtivos() {
            var data_reserva = document.getElementById('data_reserva').value;
            var hora_retirada = document.getElementById('hora_retirada').value;
            var hora_devolucao = document.getElementById('hora_devolucao').value;
            
            if (data_reserva && hora_retirada && hora_devolucao) {
                fetch('get_quantidade_ativos_disponiveis.php?data_reserva=' + data_reserva + '&hora_retirada=' + hora_retirada + '&hora_devolucao=' + hora_devolucao)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('ativos_disponiveis').innerText = data.quantidade;
                        document.getElementById('ativos_quantidade').max = data.quantidade;
                    });
            }
        }

        function showModal(success, qtdAtivos, data, horaRetirada, horaDevolucao, errorMessage = null) {
            if (success) {
                document.getElementById('modalBody').innerHTML = `
                    <p>Reserva de CB realizada com sucesso</p>
                    <p>Quantidade de ativos reservados: ${qtdAtivos}</p>
                    <p>Data da reserva: ${data}</p>
                    <p>Hora da retirada: ${horaRetirada}</p>
                    <p>Hora da devolução: ${horaDevolucao}</p>
                `;
            } else {
                document.getElementById('modalBody').innerHTML = `
                    <p>${errorMessage}</p>
                `;
            }
            $('#successModal').modal('show');
        }

        function resetForm() {
            document.getElementById('reservaForm').reset();
            document.getElementById('ativos_disponiveis').innerText = '';
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="text-center">
            <img src="logo.png" alt="Logo" class="img-fluid my-3">
        </div>
        <h1 class="text-center mt-3">Reserva de Chromebook</h1>
        <div id="error_message" class="text-danger text-center"></div>
        <form id="reservaForm" method="POST" action="reserva.php" class="mt-4">
            <div class="form-group">
                <label for="data_reserva">Data da Reserva</label>
                <input type="date" id="data_reserva" name="data_reserva" class="form-control" required onchange="fetchAvailableAtivos()">
            </div>
            <div class="form-group">
                <label for="hora_retirada">Hora da Retirada</label>
                <input type="time" id="hora_retirada" name="hora_retirada" class="form-control" required onchange="fetchAvailableAtivos()">
            </div>
            <div class="form-group">
                <label for="hora_devolucao">Hora da Devolução</label>
                <input type="time" id="hora_devolucao" name="hora_devolucao" class="form-control" required onchange="fetchAvailableAtivos()">
            </div>
            <div class="form-group">
                <label for="email_username">Email do Professor</label>
                <div class="input-group">
                    <input type="text" id="email_username" name="email_username" class="form-control" required>
                    <div class="input-group-append">
                        <span class="input-group-text">@morumbisul.com.br</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="andar">Andar</label>
                <select name="andar_id" id="andar" class="form-control" required>
                    <option value="">Selecione o andar</option>
                    <?php
                    $stmt = $conn_chamado->query("SELECT * FROM andares");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="sala">Sala</label>
                <select name="sala_id" id="sala" class="form-control" required>
                    <option value="">Selecione a sala</option>
                </select>
            </div>
            <div class="form-group">
                <label>Quantidade de Chromebook Disponíveis: <span id="ativos_disponiveis"></span></label>
                <input placeholder="Digite a quantidade que deseja." type="number" name="ativos_quantidade" class="form-control" required min="1" id="ativos_quantidade">
            </div>
            <button type="submit" class="btn btn-primary">Reservar</button>
        </form>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reserva Realizada</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="resetForm()">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function updateHoraRetirada() {
            var dataReservaInput = document.getElementById('data_reserva');
            var horaRetiradaInput = document.getElementById('hora_retirada');
            var horaDevolucaoInput = document.getElementById('hora_devolucao');
            var now = new Date();
            var currentTime = now.toTimeString().substr(0, 5);

            if (dataReservaInput.value === new Date().toISOString().split('T')[0]) {
                horaRetiradaInput.min = currentTime;
            } else {
                horaRetiradaInput.min = "07:00";
            }
            horaRetiradaInput.value = "";
            horaDevolucaoInput.value = "";
            updateHoraDevolucao();
        }

        function updateHoraDevolucao() {
            var horaRetiradaInput = document.getElementById('hora_retirada');
            var horaDevolucaoInput = document.getElementById('hora_devolucao');
            horaDevolucaoInput.min = horaRetiradaInput.value;
        }

        document.getElementById('andar').addEventListener('change', function() {
            var andarId = this.value;
            var salaSelect = document.getElementById('sala');
            salaSelect.innerHTML = '<option value="">Selecione a sala</option>';
            if (andarId) {
                fetch('get_salas.php?andar_id=' + andarId)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(sala => {
                            var option = document.createElement('option');
                            option.value = sala.id;
                            option.text = sala.nome;
                            salaSelect.appendChild(option);
                        });
                    });
            }
        });

        <?php if ($reserva_success): ?>
        showModal(true, <?= $ativos_quantidade ?>, '<?= $data_reserva ?>', '<?= $hora_retirada ?>', '<?= $hora_devolucao ?>');
        <?php elseif ($reserva_error): ?>
        showModal(false, 0, '', '', '', 'Quantidade indisponível para a data selecionada. Disponível: <?= $ativos_disponiveis ?> ativos.');
        <?php endif; ?>
    </script>
</body>
</html>
