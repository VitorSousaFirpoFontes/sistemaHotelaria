<?php
include 'conexao.php';

$mes = isset($_GET['mes']) ? intval($_GET['mes']) : null;
$ano = isset($_GET['ano']) ? intval($_GET['ano']) : null;

$sql = "SELECT SUM(despesas) AS total_despesas FROM RelatoriosFinanceiros WHERE 1=1";

if ($mes) $sql .= " AND MONTH(data_pagamento) = $mes";
if ($ano) $sql .= " AND YEAR(data_pagamento) = $ano";

$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    echo number_format($row['total_despesas'] ?? 0, 2, ',', '.');
} else {
    echo "0,00";
}

$conn->close();
?>