<?php
include 'conexao.php';

$sql = "SELECT data_pagamento, CONCAT('Pagamento de reserva') AS descricao, valor_total 
        FROM RelatoriosFinanceiros 
        ORDER BY data_pagamento DESC 
        LIMIT 10";

$result = $conn->query($sql);

$transacoes = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $transacoes[] = [
            'data' => date('d/m/Y', strtotime($row['data_pagamento'])),
            'descricao' => $row['descricao'],
            'valor' => 'R$ ' . number_format($row['valor_total'], 2, ',', '.')
        ];
    }
}

$conn->close();

echo json_encode($transacoes);
?>
