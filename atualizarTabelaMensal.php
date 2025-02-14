<?php
include 'conexao.php';

// Receber parâmetros de filtro
$mes = isset($_GET['mes']) ? intval($_GET['mes']) : null;
$ano = isset($_GET['ano']) ? intval($_GET['ano']) : null;

// Montar consulta com filtros
$sql = "SELECT 
            YEAR(data_pagamento) AS ano,
            MONTH(data_pagamento) AS mes,
            SUM(valor_total) AS valor_total 
        FROM RelatoriosFinanceiros
        WHERE 1=1";

if ($mes && $mes > 0) {
    $sql .= " AND MONTH(data_pagamento) = " . $mes;
}

if ($ano && $ano > 0) {
    $sql .= " AND YEAR(data_pagamento) = " . $ano;
}

$sql .= " GROUP BY YEAR(data_pagamento), MONTH(data_pagamento)
          ORDER BY ano DESC, mes DESC";

$result = $conn->query($sql);

// Mapeamento de meses
$meses = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março',
    4 => 'Abril', 5 => 'Maio', 6 => 'Junho',
    7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro',
    10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];

$html = '';
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $html .= "<tr>";
        $html .= "<td>".$meses[$row['mes']]."/".$row['ano']."</td>";
        $html .= "<td>R$ ".number_format($row['valor_total'], 2, ',', '.')."</td>";
        $html .= "</tr>";
    }
} else {
    $html .= "<tr><td colspan='2'>Nenhum dado disponível</td></tr>";
}

$conn->close();
echo $html;
?>
