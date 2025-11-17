<?php
session_start();
include __DIR__ . '/../db.php';

if (!isset($_SESSION['usuario'])) {
  header('Location: ../index.html');
  exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
  case 'create':
    // Criar nova transação
    $descricao = $_POST['descricao'] ?? '';
    $valor = $_POST['valor'] ?? 0;
    $categoria = $_POST['categoria'] ?? '';
    $data = $_POST['data'] ?? date('Y-m-d');

    $stmt = $db->prepare("INSERT INTO transacoes (descricao, valor, categoria, data) VALUES (?, ?, ?, ?)");
    $stmt->execute([$descricao, $valor, $categoria, $data]);

    header('Location: ../despesas.php');
    break;

  case 'update':
    // Atualizar transação existente
    $id = $_POST['id'] ?? 0;
    $descricao = $_POST['descricao'] ?? '';
    $valor = $_POST['valor'] ?? 0;
    $categoria = $_POST['categoria'] ?? '';
    $data = $_POST['data'] ?? date('Y-m-d');

    $stmt = $db->prepare("UPDATE transacoes SET descricao = ?, valor = ?, categoria = ?, data = ? WHERE id = ?");
    $stmt->execute([$descricao, $valor, $categoria, $data, $id]);

    header('Location: ../despesas.php');
    break;

  case 'delete':
    // Excluir transação
    $id = $_GET['id'] ?? 0;
    $stmt = $db->prepare("DELETE FROM transacoes WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: ../despesas.php');
    break;

  default:
    echo "Ação inválida.";
}
