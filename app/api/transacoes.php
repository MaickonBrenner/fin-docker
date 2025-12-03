<?php
session_start();
include __DIR__ . '/../db.php';

if (!isset($_SESSION['usuario'])) {
  header('Location: ../index.html');
  exit;
}

// Buscar o ID do usuário logado
$usuario = $_SESSION['usuario'];
$stmt = $db->prepare("SELECT id FROM usuario WHERE nome = ?");
$stmt->execute([$usuario]);
$usuarioId = $stmt->fetchColumn();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
  case 'create':
    // Criar nova transação
    $descricao = $_POST['descricao'] ?? '';
    $valor = $_POST['valor'] ?? 0;
    $categoria = $_POST['categoria'] ?? '';
    $tipoPagamento = $_POST['tipo_pagamento'] ?? '';
    $data = $_POST['data'] ?? date('Y-m-d');

    $stmt = $db->prepare("INSERT INTO transacoes (descricao, valor, categoria, tipo_pagamento, data, usuario_id)
                      VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$descricao, $valor, $categoria, $tipoPagamento, $data, $usuarioId]);

    echo "<script>alert('Despesa adicionada com sucesso!'); window.location.href='../despesas.php';</script>";
    header('Location: ../despesas.php');
    break;

  case 'update':
    // Atualizar transação existente (somente do usuário logado)
    $id = $_POST['id'] ?? 0;
    $descricao = $_POST['descricao'] ?? '';
    $valor = $_POST['valor'] ?? 0;
    $categoria = $_POST['categoria'] ?? '';
    $tipoPagamento = $_POST['tipo_pagamento'] ?? '';
    $data = $_POST['data'] ?? date('Y-m-d');

    $stmt = $db->prepare("UPDATE transacoes 
                      SET descricao = ?, valor = ?, categoria = ?, tipo_pagamento = ?, data = ? 
                      WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$descricao, $valor, $categoria, $tipoPagamento, $data, $id, $usuarioId]);


    header('Location: ../despesas.php');
    break;

  case 'delete':
    // Excluir transação (somente do usuário logado)
    $id = $_GET['id'] ?? 0;
    $stmt = $db->prepare("DELETE FROM transacoes WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$id, $usuarioId]);

    header('Location: ../despesas.php');
    break;

  default:
    echo "Ação inválida.";
}
