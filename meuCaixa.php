<?php
include 'conexao.php';


$mesesNomes = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril', 
    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto', 
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];


// CONSULTA INICIAL (sem filtro – exibe o total de todos os meses)
$sqlTotalGeral = "SELECT SUM(valor_total) AS valor_total FROM RelatoriosFinanceiros";
$resultTotalGeral = $conn->query($sqlTotalGeral);
$valorTotal = ($resultTotalGeral && $row = $resultTotalGeral->fetch_assoc()) ? $row['valor_total'] : 0.00;

// CONSULTA 2: ANOS DISPONÍVEIS PARA FILTRO
$sqlAnos = "SELECT DISTINCT YEAR(data_pagamento) AS ano FROM RelatoriosFinanceiros ORDER BY ano DESC";
$resultAnos = $conn->query($sqlAnos);
$anosUnicos = [];
if ($resultAnos->num_rows > 0) {
    while ($row = $resultAnos->fetch_assoc()) {
        $anosUnicos[] = $row['ano'];
    }
}

// CONSULTA INICIAL PARA TABELA (sem filtro)
$sqlMensal = "SELECT 
                YEAR(data_pagamento) AS ano,
                MONTH(data_pagamento) AS mes,
                SUM(valor_total) AS valor_total 
              FROM RelatoriosFinanceiros
              GROUP BY YEAR(data_pagamento), MONTH(data_pagamento)
              ORDER BY ano DESC, mes DESC";
$resultMensal = $conn->query($sqlMensal);

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

   <!-- Valores por Mês -->
<div class="card mt-4">
  <div class="card-header">
    <h5>Valores por Mês</h5>
  </div>
  <div class="card-body">
    <div class="row g-2"> <!-- Container para os selects -->
      <div class="col-md-6">
        <select id="selectMes" class="form-select">
          <option value="">Todos os Meses</option>
          <?php
          foreach ($mesesNomes as $num => $nome) {
            echo "<option value='$num'>$nome</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-6">
        <select id="selectAno" class="form-select">
          <option value="">Todos os Anos</option>
          <?php
          foreach ($anosUnicos as $ano) {
            echo "<option value='$ano'>$ano</option>";
          }
          ?>
        </select>
      </div>
    </div>
  </div>
</div>
    <!-- Primeira Linha de Métricas -->
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h5>Despesas Totais</h5>
          </div>
          <div class="card-body">
            <h3 class="text-center">indisponível</h3>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h5>Lucro Líquido</h5>
          </div>
          <div class="card-body">
            <h3 class="text-center" id="lucroLiquido">indisponível</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Segunda Linha de Métricas -->
    <div class="row mt-4">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h5>Reservas Realizadas</h5>
          </div>
          <div class="card-body">
            <h3 class="text-center">indisponível</h3>
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
                <h4 class="text-center">0</h4>
              </div>
              <div class="col-6">
                <h6>Check-outs Hoje</h6>
                <h4 class="text-center">0</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Transações Recentes -->
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

    <!-- Botão Adicionar Despesa NO FINAL -->
    <div class="row mb-4">
      <div class="col-12 text-center">
        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalDespesa">
          Adicionar Despesa
        </button>
      </div>
    </div>

    <!-- Modal Despesa -->
    <div class="modal fade" id="modalDespesa" tabindex="-1" aria-labelledby="modalDespesaLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalDespesaLabel">Nova Despesa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <form id="formDespesa">
              <div class="mb-3">
                <label class="form-label">Descrição</label>
                <input type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Valor</label>
                <input type="number" step="0.01" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Data</label>
                <input type="date" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-success w-100">Salvar</button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>




  <script>
    // Função para obter os filtros selecionados (mes e ano)
    function getFiltros() {
      return {
        mes: $('#selectMes').val(),
        ano: $('#selectAno').val()
      };
    }

    // Atualiza o Valor Total conforme os filtros
    function atualizarValor() {
      const filtros = getFiltros();
      $.ajax({
        url: 'atualizarValor.php',
        type: 'GET',
        data: filtros,
        success: function(response) {
          $('#valorTotal').text('R$ ' + response);
        }
      });
    }

    // Atualiza as Transações Recentes conforme os filtros
    function atualizarTransacoes() {
      const filtros = getFiltros();
      $.ajax({
        url: 'atualizarTransacoes.php',
        type: 'GET',
        data: filtros,
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

    // Atualiza o Lucro Líquido conforme os filtros
    function atualizarLucro() {
      const filtros = getFiltros();
      $.ajax({
        url: 'atualizarLucro.php',
        type: 'GET',
        data: filtros,
        success: function(response) {
          $('#lucroLiquido').text('R$ ' + response);
        }
      });
    }

    // Atualiza a tabela de Valores por Mês conforme os filtros
    function atualizarTabelaMensal() {
      const filtros = getFiltros();
      $.ajax({
        url: 'atualizarTabelaMensal.php',
        type: 'GET',
        data: filtros,
        success: function(response) {
          $('#tabelaMensal tbody').html(response);
        }
      });
    }

    // Quando o usuário alterar os filtros, atualize todas as informações
    $('#selectMes, #selectAno').change(function(){
      atualizarValor();
      atualizarTransacoes();
      atualizarLucro();
      atualizarTabelaMensal();
    });

    // Atualizações automáticas a cada 10 segundos
    setInterval(atualizarValor, 10000);
    setInterval(atualizarTransacoes, 10000);
    setInterval(atualizarLucro, 10000);
    setInterval(atualizarTabelaMensal, 10000);
    
    // Carregamento inicial dos dados filtrados
    atualizarValor();
    atualizarTransacoes();
    atualizarLucro();
  
  </script>
</body>
</html>