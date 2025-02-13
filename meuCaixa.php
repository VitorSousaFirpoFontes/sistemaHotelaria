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
    <link rel="stylesheet" href="estilocaixa.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   
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
                        <h3 class="text-center">indisponivel</h3>
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
                        <h3 class="text-center">indisponivel</h3>
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
                    <tbody id="transacoesRecentes">
                        <!-- Transações serão carregadas aqui -->
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

        function atualizarTransacoes() {
            $.ajax({
                url: 'atualizarTransacoes.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let tabela = '';
                    response.forEach(function(transacao) {
                        tabela += `<tr>
                            <td>${transacao.data}</td>
                            <td>${transacao.descricao}</td>
                            <td>${transacao.valor}</td>
                        </tr>`;
                    });
                    $('#transacoesRecentes').html(tabela);
                }
            });
        }

        setInterval(atualizarValor, 10000);
        setInterval(atualizarTransacoes, 10000);
        atualizarTransacoes(); // Carrega ao abrir a página
    </script>
</body>
</html>
