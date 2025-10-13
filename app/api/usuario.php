<?php
    session_start();
    include '../db.php';

    $usuario = $_SESSION['usuario'] ?? null;
    if (!$usuario) {
    header('Location: ../index.html');
    exit;
    }

    $novoNome = $_POST['nome'] ?? '';
    $novaSenha = $_POST['senha'] ?? '';
    $receita = $_POST['receita_mensal'] ?? 0;

    if ($novoNome && $receita !== '') {
    if ($novaSenha !== '') {
        $stmt = $db->prepare("UPDATE usuario SET nome = ?, senha = ?, receita_mensal = ? WHERE nome = ?");
        $stmt->execute([$novoNome, $novaSenha, $receita, $usuario]);
    } else {
        $stmt = $db->prepare("UPDATE usuario SET nome = ?, receita_mensal = ? WHERE nome = ?");
        $stmt->execute([$novoNome, $receita, $usuario]);
    }

    $_SESSION['usuario'] = $novoNome;
    }

    header('Location: ../config.php');
    exit;