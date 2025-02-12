<?php
include 'conexao.php';

// Busca o valor total diretamente do banco de dados
$sql = "SELECT SUM(valor_total) AS valor_total FROM RelatoriosFinanceiros";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $valorTotal = $row['valor_total'] ? $row['valor_total'] : 0.00;
} else {
    $valorTotal = 0.00;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Caixa - Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background-color: #007bff;
        }
        .card {
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #6c757d;
            color: white;
        }
        .card-body {
            background-color: #ffffff;
        }
        .container {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Gestão de Caixa - Hotel</h1>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Valor Total</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="text-center" id="valorTotal">R$ <?= number_format($valorTotal, 2, ',', '.') ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Despesas Totais</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="text-center">R$ 25.000,00</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Lucro Líquido</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="text-center">R$ 25.000,00</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Reservas Realizadas</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="text-center">350 Reservas</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Check-ins e Check-outs</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h6>Check-ins Hoje</h6>
                                <h4 class="text-center">50</h4>
                            </div>
                            <div class="col-6">
                                <h6>Check-outs Hoje</h6>
                                <h4 class="text-center">30</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>Transações Recentes</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>05/02/2025</td>
                            <td>Pagamento de reserva</td>
                            <td>R$ 500,00</td>
                        </tr>
                        <tr>
                            <td>04/02/2025</td>
                            <td>Pagamento de reserva</td>
                            <td>R$ 600,00</td>
                        </tr>
                        <tr>
                            <td>03/02/2025</td>
                            <td>Pagamento de despesa</td>
                            <td>-R$ 1.000,00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function atualizarValor() {
            $.ajax({
                url: 'atualizarValor.php',
                type: 'GET',
                success: function(response) {
                    $('#valorTotal').text('R$ ' + response);
                }
            });
        }

        setInterval(atualizarValor, 10000);
    </script>
</body>
</html>
