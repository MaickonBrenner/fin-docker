<?php
session_start();
include 'db.php';

$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) {
  header('Location: index.html');
  exit;
}

// Buscar ID do usuário logado
$stmt = $db->prepare("SELECT id FROM usuario WHERE nome = ?");
$stmt->execute([$usuario]);
$usuarioId = $stmt->fetchColumn();

// Receita mensal
$stmt = $db->prepare("SELECT receita_mensal FROM usuario WHERE id = ?");
$stmt->execute([$usuarioId]);
$receita = $stmt->fetchColumn();

// Filtro de mês
$mesSelecionado = $_GET['mes'] ?? date('Y-m');

// Gastos por categoria do usuário
$stmt = $db->prepare("SELECT categoria, SUM(valor) as total 
                      FROM transacoes 
                      WHERE strftime('%Y-%m', data) = ? AND usuario_id = ?
                      GROUP BY categoria");
$stmt->execute([$mesSelecionado, $usuarioId]);
$gastosPorCategoria = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gasto total
$totalGasto = array_sum(array_column($gastosPorCategoria, 'total'));
$saldo = $receita - $totalGasto;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - FinDocker</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <h2>FinDocker</h2>
      <nav>
        <a href="home.php">🏠 Início</a>
        <a href="despesas.php">💸 Despesas</a>
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
          backgroundColor: [
            '#03dac6', // azul água (original)
            '#ff6b6b', // vermelho suave (original)
            '#ffd54f', // amarelo (original)
            '#64b5f6', // azul claro (original)
            '#81c784', // verde suave (original)

            '#ba68c8', // roxo médio
            '#4db6ac', // verde água
            '#ff8a65', // laranja queimado
            '#9575cd', // roxo pastel
            '#4fc3f7', // azul céu

            '#aed581', // verde limão suave
            '#f06292', // rosa vibrante
            '#7986cb', // azul arroxeado
            '#e57373', // vermelho claro
            '#fff176', // amarelo pastel

            '#90caf9', // azul bebê
            '#a1887f', // marrom acinzentado
            '#ce93d8', // lilás
            '#ffb74d', // laranja suave
            '#69f0ae'  // verde neon suave
          ]
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
