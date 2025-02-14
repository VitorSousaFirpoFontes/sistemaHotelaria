<?php
include 'conexao.php';

$mes = isset($_GET['mes']) ? intval($_GET['mes']) : null;
$ano = isset($_GET['ano']) ? intval($_GET['ano']) : null;

$sql = "SELECT SUM(valor_total) AS valor_total FROM RelatoriosFinanceiros WHERE 1=1";

if ($mes && $mes > 0) {
    $sql .= " AND MONTH(data_pagamento) = " . $mes;
}
if ($ano && $ano > 0) {
    $sql .= " AND YEAR(data_pagamento) = " . $ano;
}

$result = $conn->query($sql);
$valorTotal = 0;
if ($result && $row = $result->fetch_assoc()) {
    $valorTotal = $row['valor_total'];
}
echo number_format($valorTotal, 2, ',', '.');

$conn->close();
?>
