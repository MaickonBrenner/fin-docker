<?php
session_start();
include 'db.php';

$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) {
  header('Location: index.html');
  exit;
}

// Receita mensal
$stmt = $db->prepare("SELECT receita_mensal FROM usuario WHERE nome = ?");
$stmt->execute([$usuario]);
$receita = $stmt->fetchColumn();

// Filtro de mês
$mesSelecionado = $_GET['mes'] ?? date('Y-m');
$stmt = $db->prepare("SELECT categoria, SUM(valor) as total FROM transacoes WHERE strftime('%Y-%m', data) = ? GROUP BY categoria");
$stmt->execute([$mesSelecionado]);
$gastosPorCategoria = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gasto total
$totalGasto = array_sum(array_column($gastosPorCategoria, 'total'));
$saldo = $receita - $totalGasto;
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
      <h1>Dashboard</h1>

      <form method="GET" style="margin-bottom: 2rem;">
        <label>Filtrar por mês:</label>
        <input type="month" name="mes" value="<?php echo $mesSelecionado; ?>">
        <button type="submit">Filtrar</button>
      </form>

      <div class="painel">
        <div class="card">
          <h3>Receita Mensal</h3>
          <p>R$ <?php echo number_format($receita, 2, ',', '.'); ?></p>
        </div>
        <div class="card">
          <h3>Gasto Total</h3>
          <p>R$ <?php echo number_format($totalGasto, 2, ',', '.'); ?></p>
        </div>
        <div class="card">
          <h3>Saldo Restante</h3>
          <p>R$ <?php echo number_format($saldo, 2, ',', '.'); ?></p>
        </div>
      </div>

      <canvas id="graficoCategorias" height="100"></canvas>
    </main>
  </div>

  <script>
    const ctx = document.getElementById('graficoCategorias').getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: <?php echo json_encode(array_column($gastosPorCategoria, 'categoria')); ?>,
        datasets: [{
          data: <?php echo json_encode(array_column($gastosPorCategoria, 'total')); ?>,
          backgroundColor: ['#03dac6', '#ff6b6b', '#ffd54f', '#64b5f6', '#81c784']
        }]
      },
      options: {
        plugins: {
          legend: {
            labels: { color: '#e0e0e0' }
          }
        }
      }
    });
  </script>
  <script src="js/main.js"></script>
</body>
</html>
