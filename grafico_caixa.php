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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Gestão de Caixa - Hotel</h1>

        <!-- Seletor de Mês -->
        <div class="row mb-4">
            <div class="col-md-3">
                <label for="mesSelect">Selecione o Mês:</label>
                <select class="form-select" id="mesSelect">
                    <option value="1">Janeiro</option>
                    <option value="2" selected>Fevereiro</option>
                    <option value="3">Março</option>
                    <option value="4">Abril</option>
                    <option value="5">Maio</option>
                    <option value="6">Junho</option>
                    <option value="7">Julho</option>
                    <option value="8">Agosto</option>
                    <option value="9">Setembro</option>
                    <option value="10">Outubro</option>
                    <option value="11">Novembro</option>
                    <option value="12">Dezembro</option>
                </select>
            </div>
        </div>

        <!-- Cards superiores (mantidos da versão anterior) -->
        <!-- ... manter os cards existentes ... -->

        <!-- Novo gráfico -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>Gráfico Estatístico Mensal</h5>
            </div>
            <div class="card-body">
                <canvas id="graficoMensal"></canvas>
            </div>
        </div>

        <!-- Restante do conteúdo mantido -->
        <!-- ... manter as outras seções ... -->

    </div>

    <script>
        // Configuração inicial do gráfico
        let chart = null;

        function carregarGrafico(mes) {
            $.ajax({
                url: 'carregar_dados_grafico.php',
                method: 'GET',
                data: { mes: mes },
                dataType: 'json',
                success: function(response) {
                    if (chart) {
                        chart.destroy();
                    }
                    
                    const ctx = document.getElementById('graficoMensal').getContext('2d');
                    chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: response.labels,
                            datasets: [{
                                label: 'Valor por Dia (R$)',
                                data: response.valores,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return 'R$ ' + value.toLocaleString('pt-BR');
                                        }
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'R$ ' + context.parsed.y.toLocaleString('pt-BR');
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
        }

        // Carregar gráfico inicial (Fevereiro)
        $(document).ready(function() {
            carregarGrafico(2);
        });

        // Atualizar gráfico ao mudar o mês
        $('#mesSelect').change(function() {
            const mesSelecionado = $(this).val();
            carregarGrafico(mesSelecionado);
        });

        // Manter funções existentes de atualização
        // ... manter as funções existentes de atualizarValor e atualizarTransacoes ...
    </script>
</body>
</html>