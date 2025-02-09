<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Caixa - Hotel</title>
    <!-- Link para o Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #007bff; /* Azul */
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
            <!-- Card de Lucro Total -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Lucro Total</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="text-center">R$ 50.000,00</h3>
                    </div>
                </div>
            </div>

            <!-- Card de Despesas -->
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

            <!-- Card de Lucro Líquido -->
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
            <!-- Card de Reservas -->
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

            <!-- Card de Check-ins e Check-outs -->
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

        <!-- Tabela de Transações -->
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

    <!-- Scripts do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
