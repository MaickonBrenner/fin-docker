<?php
session_start();
    include 'db.php';

    $usuario = $_SESSION['usuario'] ?? null;

    if (!$usuario) {
    header('Location: index.html');
    exit;
}

$stmt = $db->prepare("SELECT * FROM usuario WHERE nome = ?");
$stmt->execute([$usuario]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Configurações - fin-docker</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <h2>fin-docker</h2>
      <nav>
        <a href="home.php">🏠 Início</a>
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="categorias.php">📁 Categorias</a>
        <a href="config.php">⚙️ Configurações</a>
        <a href="logout.php">🚪 Sair</a>
      </nav>
    </aside>

    <main class="content">
      <h1>Configurações</h1>

      <form action="api/usuario.php" method="POST" class="form-despesa">
        <label>Nome de exibição</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($dados['nome']); ?>" required>

        <label>Nova senha</label>
        <input type="password" name="senha" placeholder="Deixe em branco para manter atual">

        <label>Receita mensal (R$)</label>
        <input type="number" step="0.01" name="receita_mensal" value="<?php echo $dados['receita_mensal'] ?? ''; ?>" required>

        <button type="submit">Salvar alterações</button>
      </form>
    </main>
  </div>
</body>
</html>
