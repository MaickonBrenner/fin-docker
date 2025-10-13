<?php
session_start();
include '../db.php';

$usuario = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';

if ($usuario && $senha) {
  $stmt = $db->prepare("SELECT * FROM usuario WHERE nome = ? AND senha = ?");
  $stmt->execute([$usuario, $senha]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    $_SESSION['usuario'] = $user['nome'];
    header('Location: ../home.php');
    exit;
  } else {
    echo "<script>alert('Usuário ou senha inválidos'); window.location.href='../index.html';</script>";
  }
} else {
  echo "<script>alert('Preencha todos os campos'); window.location.href='../index.html';</script>";
}
?>
