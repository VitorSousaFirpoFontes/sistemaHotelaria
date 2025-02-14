<?php
include 'conexao.php';

$mes = $_GET['mes'] ?? null;
$ano = $_GET['ano'] ?? null;

// CALCULA RECEITAS
$sqlReceitas = "SELECT SUM(valor_total) AS total FROM RelatoriosFinanceiros WHERE 1";
if ($mes) $sqlReceitas .= " AND MONTH(data_pagamento) = $mes";
if ($ano) $sqlReceitas .= " AND YEAR(data_pagamento) = $ano";
$receitas = $conn->query($sqlReceitas)->fetch_assoc()['total'] ?? 0;

// CALCULA DESPESAS (ajuste o nome da tabela se necessário)
$sqlDespesas = "SELECT SUM(valor) AS total FROM Despesas WHERE 1"; // <<< Nome da tabela aqui
if ($mes) $sqlDespesas .= " AND MONTH(data) = $mes";
if ($ano) $sqlDespesas .= " AND YEAR(data) = $ano";
$despesas = $conn->query($sqlDespesas)->fetch_assoc()['total'] ?? 0;

$lucroLiquido = $receitas - $despesas;
echo number_format($lucroLiquido, 2, ',', '.');

$conn->close();
?>