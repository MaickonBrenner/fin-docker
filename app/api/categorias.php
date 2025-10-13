<?php
session_start();
include '../db.php';

if (isset($_POST['nova_categoria'])) {
  $nova = trim($_POST['nova_categoria']);
  if ($nova !== '') {
    $stmt = $db->prepare("INSERT INTO categorias (nome) VALUES (?)");
    $stmt->execute([$nova]);
  }
}

if (isset($_POST['remover_categoria'])) {
  $id = intval($_POST['remover_categoria']);
  $stmt = $db->prepare("DELETE FROM categorias WHERE id = ?");
  $stmt->execute([$id]);
}

header('Location: ../categorias.php');
exit;
