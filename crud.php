<?php
include "conexao.php"; // Certifique-se de que este arquivo define a variável $conn (MySQLi)

// Se a requisição for GET, retorna todos os itens em formato JSON
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM itens ORDER BY id DESC";
    $resultado = $conn->query($sql);

    $itens = [];
    while ($row = $resultado->fetch_assoc()) {
        $itens[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($itens);
    exit;
}

// Se a requisição for POST, verifica a ação solicitada
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // A variável 'acao' define qual operação será realizada:
    // 'inserir', 'atualizar' ou 'deletar'
    $acao = $_POST['acao'] ?? '';

    // INSERÇÃO
    if ($acao === 'inserir') {
        $nome      = $_POST['nome'] ?? '';
        $categoria = $_POST['categoria'] ?? '';
        $quantidade= $_POST['quantidade'] ?? 0;

        $sql = "INSERT INTO itens (nome, categoria, quantidade) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro ao preparar a consulta: " . $conn->error);
        }
        $stmt->bind_param("ssi", $nome, $categoria, $quantidade);

        if ($stmt->execute()) {
            echo "inserido";
        } else {
            echo "erro: " . $stmt->error;
        }
        $stmt->close();
    }

    // ATUALIZAÇÃO
    elseif ($acao === 'atualizar') {
        $id        = $_POST['id'] ?? 0;
        $nome      = $_POST['nome'] ?? '';
        $categoria = $_POST['categoria'] ?? '';
        $quantidade= $_POST['quantidade'] ?? 0;

        $sql = "UPDATE itens SET nome = ?, categoria = ?, quantidade = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro ao preparar a consulta: " . $conn->error);
        }
        $stmt->bind_param("ssii", $nome, $categoria, $quantidade, $id);

        if ($stmt->execute()) {
            echo "atualizado";
        } else {
            echo "erro: " . $stmt->error;
        }
        $stmt->close();
    }

    // EXCLUSÃO
    elseif ($acao === 'deletar') {
        $id = $_POST['id'] ?? 0;

        $sql = "DELETE FROM itens WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro ao preparar a consulta: " . $conn->error);
        }
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "deletado";
        } else {
            echo "erro: " . $stmt->error;
        }
        $stmt->close();
    }

    exit;
}
?>
