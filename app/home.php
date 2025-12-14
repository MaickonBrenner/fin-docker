<?php
session_start();
include __DIR__ . '/db.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario_id'])) {
  header('Location: index.html');
  exit;
}

$usuario = $_SESSION['usuario'];
$usuarioId = $_SESSION['usuario_id'];

// Receita mensal do usuário
$stmt = $db->prepare("SELECT receita_mensal FROM usuario WHERE id = ?");
$stmt->execute([$usuarioId]);
$receita = $stmt->fetchColumn() ?: 0;

// Tradução manual dos meses
$meses = [
  'January' => 'Janeiro', 'February' => 'Fevereiro', 'March' => 'Março',
  'April' => 'Abril', 'May' => 'Maio', 'June' => 'Junho',
  'July' => 'Julho', 'August' => 'Agosto', 'September' => 'Setembro',
  'October' => 'Outubro', 'November' => 'Novembro', 'December' => 'Dezembro'
];
$mesAtual = $meses[date('F')] . ' de ' . date('Y');

// Gasto atual do mês (filtrado pelo usuário)
$mesFiltro = date('Y-m');
$stmt = $db->prepare("SELECT SUM(valor) FROM transacoes WHERE strftime('%Y-%m', data) = ? AND usuario_id = ?");
$stmt->execute([$mesFiltro, $usuarioId]);
$gastoAtual = $stmt->fetchColumn() ?: 0;

// Últimas 10 despesas do usuário no mês atual
$stmt = $db->prepare("
    SELECT * FROM transacoes 
    WHERE usuario_id = ?
      AND strftime('%m', data) = strftime('%m', 'now')
      AND strftime('%Y', data) = strftime('%Y', 'now')
    ORDER BY data DESC
    LIMIT 20
");
$stmt->execute([$usuarioId]);
$ultimasDespesas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Categorias
$categorias = $db->query("SELECT nome FROM categorias")->fetchAll(PDO::FETCH_COLUMN);

// Gastos por dia para o gráfico (filtrado pelo usuário)
$stmt = $db->prepare("
  SELECT strftime('%d', data) AS dia, SUM(valor) AS total
  FROM transacoes
  WHERE strftime('%Y-%m', data) = ? AND usuario_id = ?
  GROUP BY dia
  ORDER BY dia
");
$stmt->execute([$mesFiltro, $usuarioId]);
$gastosPorDia = $stmt->fetchAll(PDO::FETCH_ASSOC);
$labels = array_column($gastosPorDia, 'dia');
$valores = array_column($gastosPorDia, 'total');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Home - FinDocker</title>
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
      <h1>Olá, <?= htmlspecialchars($usuario); ?>!</h1>
      <p>Estamos em <strong><?= ucfirst($mesAtual); ?></strong></p>

      <!-- Painel resumo -->
      <div class="painel">
        <div class="card">
          <h3>Receita Mensal</h3>
          <p>R$ <?= number_format($receita, 2, ',', '.'); ?></p>
        </div>
        <div class="card">
          <h3>Gasto Atual</h3>
          <p>R$ <?= number_format($gastoAtual, 2, ',', '.'); ?></p>
        </div>
      </div>

      <!-- Últimas despesas -->
      <h2>Últimas despesas</h2>
      <table class="tabela-despesas estilizada">
        <thead>
          <tr>
            <th>Descrição</th>
            <th>Valor</th>
            <th>Categoria</th>
            <th>Data</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ultimasDespesas as $d): ?>
            <?php $classeValor = $d['valor'] >= 500 ? 'alto' : 'baixo'; ?>
            <tr>
              <td><?= htmlspecialchars($d['descricao']) ?></td>
              <td class="<?= $classeValor ?>">R$ <?= number_format($d['valor'], 2, ',', '.') ?></td>
              <td><span class="categoria"><?= htmlspecialchars($d['categoria']) ?></span></td>
              <td><?= date('d/m/Y', strtotime($d['data'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Gráfico -->
      <canvas id="graficoMensal" height="100"></canvas>
    </main>
  </div>

  <!-- Passar dados PHP para JS -->
  <script>
    const labels = <?= json_encode($labels) ?>;
    const valores = <?= json_encode($valores) ?>;
  </script>
  <script src="js/main.js"></script>
  <script src="js/dashboard.js"></script>
</body>
</html>
