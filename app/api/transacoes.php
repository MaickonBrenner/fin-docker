<?php
session_start();
include '../db.php';

$descricao = $_POST['descricao'] ?? '';
$valor = $_POST['valor'] ?? 0;
$categoria = $_POST['categoria'] ?? '';

if ($descricao && $valor && $categoria) {
  $stmt = $db->prepare("INSERT INTO transacoes (descricao, valor, tipo, data) VALUES (?, ?, ?, ?)");
  $stmt->execute([$descricao, $valor, $categoria, date('Y-m-d H:i:s')]);
  header('Location: ../home.php');
  exit;
} else {
  echo "<script>alert('Preencha todos os campos'); window.location.href='../home.php';</script>";
}
?>
