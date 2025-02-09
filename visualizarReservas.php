<?php
include "conexao.php";

// Função para obter o número de dias de um mês específico
function obterDiasNoMes($mes, $ano) {
    return cal_days_in_month(CAL_GREGORIAN, $mes + 1, $ano); // Meses começam do 0
}

// Verifica se o formulário foi enviado para atualizar o mês e ano
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : 9; // Default Outubro (mes 9)
$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : date('Y'); // Ano atual como padrão

$diasNoMes = obterDiasNoMes($mes, $ano);

// Definir mês ajustado (mes + 1)
$mesAjustado = $mes + 1;

// Alterar a consulta SQL para buscar reservas apenas para o mês e ano selecionados, incluindo o valor da diária
$sql = "SELECT nome_cliente, data_checkin, data_checkout, numero_quarto, valor_diaria, valor_total 
        FROM Reservas 
        WHERE MONTH(data_checkin) = ? AND YEAR(data_checkin) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $mesAjustado, $ano);  // Passando as variáveis diretamente agora
$stmt->execute();
$result = $stmt->get_result();

// Organizar reservas em um array
$reservas = [];
while ($row = $result->fetch_assoc()) {
    $reservas[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Reservas</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="estiloPlanilha.css" rel="stylesheet" />
</head>
<body class="container mt-5">
    <h1>Controle de Reservas</h1>

    <div class="filtros">
        <form method="GET" action="">
            <label for="mes">Mês:</label>
            <select id="mes" name="mes" onchange="this.form.submit()">
                <?php
                // Opções de meses
                $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                for ($i = 0; $i < 12; $i++) {
                    $selected = ($mes == $i) ? 'selected' : '';
                    echo "<option value='$i' $selected>{$meses[$i]}</option>";
                }
                ?>
            </select>

            <label for="ano">Ano:</label>
            <select id="ano" name="ano" onchange="this.form.submit()">
                <?php
                // Opções de anos (passados 5 anos e futuro)
                $currentYear = date('Y');
                for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++) {
                    $selected = ($ano == $i) ? 'selected' : '';
                    echo "<option value='$i' $selected>$i</option>";
                }
                ?>
            </select>
        </form>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Quarto</th>
                <?php
                for ($dia = 1; $dia <= $diasNoMes; $dia++) {
                    echo "<th>$dia/" . ($mes + 1) . "</th>";
                }
                ?>
                <th>Diária</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Gerar a tabela de quartos
            for ($quarto = 1; $quarto <= 30; $quarto++) {
                echo "<tr>";
                echo "<td><strong>Quarto $quarto</strong></td>";

                // Preencher os dias do mês para o quarto
                for ($dia = 1; $dia <= $diasNoMes; $dia++) {
                    $reservado = false;
                    $reserva_cliente = '';
                    $valor_diaria = '';
                    foreach ($reservas as $reserva) {
                        $checkin = new DateTime($reserva['data_checkin']);
                        $checkout = new DateTime($reserva['data_checkout']);
                        if ($reserva['numero_quarto'] == $quarto && $dia >= $checkin->format('d') && $dia < $checkout->format('d')) {
                            $reservado = true;
                            $reserva_cliente = $reserva['nome_cliente'];
                            $valor_diaria = $reserva['valor_diaria'];
                            break;
                        }
                    }

                    if ($reservado) {
                        echo "<td style='background-color: #ffd700' class='clickable' data-toggle='modal' data-target='#modalReserva' data-nome='$reserva_cliente' data-checkin='{$checkin->format('d/m/Y')}' data-checkout='{$checkout->format('d/m/Y')}' data-quarto='$quarto' data-valor-diaria='$valor_diaria' data-valor-total='{$reserva['valor_total']}'>$reserva_cliente</td>";
                    } else {
                        echo "<td></td>";
                    }
                }
                // Exibe o valor da diária ao final da linha
                echo "<td><strong>R$ $valor_diaria</strong></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="modalReserva" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Informações da Reserva</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Nome do Cliente:</strong> <span id="modalNome"></span></p>
                    <p><strong>Check-in:</strong> <span id="modalCheckin"></span></p>
                    <p><strong>Check-out:</strong> <span id="modalCheckout"></span></p>
                    <p><strong>Quarto:</strong> <span id="modalQuarto"></span></p>
                    <p><strong>Valor da Diária:</strong> <span id="modalValorDiaria"></span></p>
                    <p><strong>Valor Total:</strong> <span id="modalValorTotal"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="confirmCheckout">Confirmar Check-out</button>
                </div>
            </div>
        </div>
    </div>

    <a href="menu.php" class="btn btn-primary">Ir para tela inicial</a>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    $('#modalReserva').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var nome = button.data('nome');
        var checkin = button.data('checkin');
        var checkout = button.data('checkout');
        var quarto = button.data('quarto');
        var valorDiaria = button.data('valor-diaria');
        var valorTotal = button.data('valor-total');

        var modal = $(this);
        modal.find('#modalNome').text(nome);
        modal.find('#modalCheckin').text(checkin);
        modal.find('#modalCheckout').text(checkout);
        modal.find('#modalQuarto').text(quarto);
        modal.find('#modalValorDiaria').text('R$ ' + valorDiaria);
        modal.find('#modalValorTotal').text('R$ ' + valorTotal);
    });

    $('#confirmCheckout').click(function() {
        var valorTotal = $('#modalValorTotal').text().replace('R$', '').trim(); // Remove 'R$' e espaços
        valorTotal = parseFloat(valorTotal.replace(',', '.')); // Converte para float
        $.ajax({
            url: 'meuCaixa.php',
            type: 'POST',
            data: { valorTotal: valorTotal },
            success: function(response) {
                alert('Check-out confirmado e valor adicionado ao caixa!');
                location.reload();
            }
        });
    });
</script>

</body>
</html>
