<?php
session_start();
include '../db.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
if (!$usuarioId) {
  header('Location: ../index.html');
  exit;
}

$novoNome = $_POST['nome'] ?? '';
$novaSenha = $_POST['senha'] ?? '';
$receita = $_POST['receita_mensal'] ?? 0;

if ($novoNome && $receita !== '') {
  if ($novaSenha !== '') {
    $stmt = $db->prepare("UPDATE usuario SET nome = ?, senha = ?, receita_mensal = ? WHERE id = ?");
    $stmt->execute([$novoNome, $novaSenha, $receita, $usuarioId]);
  } else {
    $stmt = $db->prepare("UPDATE usuario SET nome = ?, receita_mensal = ? WHERE id = ?");
    $stmt->execute([$novoNome, $receita, $usuarioId]);
  }

  // Atualiza sessão com novo nome
  $_SESSION['usuario'] = $novoNome;
}

header('Location: ../config.php');
exit;
