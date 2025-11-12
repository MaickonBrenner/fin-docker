<?php
include '../db.php';

$usuario = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';
$receita = $_POST['receita_mensal'] ?? 0;

if ($usuario && $senha && $receita !== '') {
  // Verifica se o usuário já existe
  $check = $db->prepare("SELECT COUNT(*) FROM usuario WHERE nome = ?");
  $check->execute([$usuario]);
  if ($check->fetchColumn() > 0) {
    echo "<script>alert('Usuário já existe'); window.location.href='../registro.php';</script>";
    exit;
  }

  // Cria novo usuário
  $stmt = $db->prepare("INSERT INTO usuario (nome, senha, receita_mensal) VALUES (?, ?, ?)");
  $stmt->execute([$usuario, $senha, $receita]);

  echo "<script>alert('Conta criada com sucesso!'); window.location.href='../index.html';</script>";
} else {
  echo "<script>alert('Preencha todos os campos'); window.location.href='../registro.php';</script>";
}
