<?php
include "conexao.php";

header('Content-Type: application/json');

// CREATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    try {
        $stmt = $pdo->prepare("INSERT INTO itens (nome, categoria, quantidade) VALUES (?, ?, ?)");
        $stmt->execute([
            htmlspecialchars($_POST['nome']),
            htmlspecialchars($_POST['categoria']),
            intval($_POST['quantidade'])
        ]);
        echo json_encode(['success' => true, 'message' => 'Item adicionado com sucesso!']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao adicionar item: ' . $e->getMessage()]);
    }
    exit;
}

// READ
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'read') {
    try {
        $stmt = $pdo->query("SELECT * FROM itens ORDER BY id DESC");
        $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $itens]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao carregar itens: ' . $e->getMessage()]);
    }
    exit;
}

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    try {
        $stmt = $pdo->prepare("UPDATE itens SET nome = ?, categoria = ?, quantidade = ? WHERE id = ?");
        $stmt->execute([
            htmlspecialchars($_POST['nome']),
            htmlspecialchars($_POST['categoria']),
            intval($_POST['quantidade']),
            intval($_POST['id'])
        ]);
        echo json_encode(['success' => true, 'message' => 'Item atualizado com sucesso!']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar item: ' . $e->getMessage()]);
    }
    exit;
}

// DELETE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    try {
        $stmt = $pdo->prepare("DELETE FROM itens WHERE id = ?");
        $stmt->execute([intval($_POST['id'])]);
        echo json_encode(['success' => true, 'message' => 'Item excluído com sucesso!']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao excluir item: ' . $e->getMessage()]);
    }
    exit;
}
?>