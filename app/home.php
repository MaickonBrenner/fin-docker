<?php
    session_start();
    include 'db.php';

    // Simulação de login (substituir por validação real depois)
    if (!isset($_SESSION['usuario'])) {
    $_SESSION['usuario'] = 'maickon'; // temporário
    }

    // Obter mês atual
    setlocale(LC_TIME, 'pt_BR.UTF-8');
    $mesAtual = strftime('%B de %Y');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - fin-docker</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      <h1>Olá, <?php echo $_SESSION['usuario']; ?>!</h1>
      <p>Estamos em <strong><?php echo ucfirst($mesAtual); ?></strong></p>

      <button class="btn-add">+ Adicionar Despesa</button>

      <form action="api/transacoes.php" method="POST" class="form-despesa">
        <input type="text" name="descricao" placeholder="Descrição da despesa" required>
        <input type="number" step="0.01" name="valor" placeholder="Valor (R$)" required>
        <select name="categoria">
            <?php
            $categorias = $db->query("SELECT nome FROM categorias")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($categorias as $cat) {
                echo "<option value='$cat'>$cat</option>";
            }
            ?>
        </select>
        <button type="submit">Salvar despesa</button>
    </form>


      <div class="painel">
        <div class="card">
          <h3>Receita Mensal</h3>
          <p>R$ 3.000,00</p> <!-- Substituir por valor real -->
        </div>
        <div class="card">
          <h3>Gasto Atual</h3>
          <p>R$ 1.250,00</p> <!-- Substituir por cálculo real -->
        </div>
      </div>

      <canvas id="graficoMensal" height="100"></canvas>
    </main>
  </div>
  <script src="js/main.js"></script>
  <script src="js/dashboard.js"></script>
</body>
</html>