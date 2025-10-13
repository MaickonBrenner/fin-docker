<?php
    session_start();
    include 'db.php';

    $categorias = $db->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Categorias - fin-docker</title>
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
      <h1>Categorias</h1>

      <form action="api/categorias.php" method="POST" class="form-despesa">
        <input type="text" name="nova_categoria" placeholder="Nova categoria" required>
        <button type="submit">Adicionar</button>
      </form>

      <ul class="lista-categorias">
        <?php foreach ($categorias as $cat): ?>
          <li>
            <?php echo htmlspecialchars($cat['nome']); ?>
            <form action="api/categorias.php" method="POST" style="display:inline;">
              <input type="hidden" name="remover_categoria" value="<?php echo $cat['id']; ?>">
              <button type="submit">🗑️</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    </main>
  </div>
</body>
</html>
