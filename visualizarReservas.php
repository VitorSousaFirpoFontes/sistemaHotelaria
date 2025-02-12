<?php
include "conexao.php";

// Função para obter o número de dias de um mês específico
function obterDiasNoMes($mes, $ano) {
    return cal_days_in_month(CAL_GREGORIAN, $mes + 1, $ano);
}

// Verifica se o formulário foi enviado para atualizar o mês e ano
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : 9;
$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : date('Y');

$diasNoMes = obterDiasNoMes($mes, $ano);
$mesAjustado = $mes + 1;

$sql = "SELECT id, nome_cliente, data_checkin, data_checkout, numero_quarto, valor_diaria, valor_total 
        FROM Reservas 
        WHERE MONTH(data_checkin) = ? AND YEAR(data_checkin) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $mesAjustado, $ano);
$stmt->execute();
$result = $stmt->get_result();

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

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Quarto</th>
                <?php for ($dia = 1; $dia <= $diasNoMes; $dia++) {
                    echo "<th>$dia/" . ($mes + 1) . "</th>";
                } ?>
                <th>Diária</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($quarto = 1; $quarto <= 30; $quarto++) {
                echo "<tr><td><strong>Quarto $quarto</strong></td>";
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
                    echo $reservado ? "<td style='background-color: #ffd700' class='clickable' data-toggle='modal' data-target='#modalReserva' data-nome='$reserva_cliente' data-checkin='{$checkin->format('d/m/Y')}' data-checkout='{$checkout->format('d/m/Y')}' data-quarto='$quarto' data-valor-diaria='$valor_diaria' data-valor-total='{$reserva['valor_total']}' data-id='{$reserva['id']}'>$reserva_cliente</td>" : "<td></td>";
                }
                echo "<td><strong>R$ $valor_diaria</strong></td></tr>";
            } ?>
        </tbody>
    </table>

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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
   $('#modalReserva').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    $('#modalNome').text(button.data('nome'));
    $('#modalCheckin').text(button.data('checkin'));
    $('#modalCheckout').text(button.data('checkout'));
    $('#modalQuarto').text(button.data('quarto'));
    $('#modalValorDiaria').text('R$ ' + button.data('valor-diaria'));
    $('#modalValorTotal').text('R$ ' + button.data('valor-total'));

    // Passando os dados para o modal
    $('#confirmCheckout').data('id', button.data('id'));
    $('#confirmCheckout').data('valor-diaria', button.data('valor-diaria'));
    $('#confirmCheckout').data('valor-total', button.data('valor-total'));
});

$('#confirmCheckout').click(function() {
    // Formatação numérica correta para o padrão brasileiro
    var valorTotal = $('#modalValorTotal').text().replace('R$', '').trim();
    valorTotal = parseFloat(valorTotal.replace(/\./g, '').replace(',', '.')); 

    var valorDiaria = $('#modalValorDiaria').text().replace('R$', '').trim();
    valorDiaria = parseFloat(valorDiaria.replace(/\./g, '').replace(',', '.'));

    var id = $(this).data('id');
    
    $.ajax({
        url: 'registrar_pagamento.php',
        type: 'POST',
        data: {
            id: id,
            valor_diaria: valorDiaria, // Mantém o nome do parâmetro
            valor_total: valorTotal,
            data_pagamento: new Date().toISOString().slice(0, 10)
        },
        success: function(response) {
            alert(response); // Mostra a resposta do servidor
            location.reload();
        },
        error: function(xhr) {
            alert("Erro: " + xhr.responseText);
        }
    });
});
    </script>
</body>
</html>
