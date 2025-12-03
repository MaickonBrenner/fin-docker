<?php
session_start();
include __DIR__ . '/db.php';

if (!isset($_SESSION['usuario'])) {
  header('Location: index.html');
  exit;
}

$usuario = $_SESSION['usuario'];

// Buscar ID do usuário logado
$stmt = $db->prepare("SELECT id FROM usuario WHERE nome = ?");
$stmt->execute([$usuario]);
$usuarioId = $stmt->fetchColumn();

// Buscar todas as despesas do usuário
$stmt = $db->prepare("SELECT * FROM transacoes WHERE usuario_id = ? ORDER BY data DESC");
$stmt->execute([$usuarioId]);
$transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar categorias
$categorias = $db->query("SELECT nome FROM categorias")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Despesas - FinDocker</title>
  <link rel="stylesheet" href="css/style.css">
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
      <h1>Despesas de <?= htmlspecialchars($usuario); ?></h1>

      <!-- Formulário de criação -->
      <form action="api/transacoes.php?action=create" method="POST" class="form-despesa">
        <input type="text" name="descricao" placeholder="Descrição da despesa" required>
        <input type="number" step="0.01" name="valor" placeholder="Valor (R$)" required>
        
        <!-- Categoria -->
        <select name="categoria" required>
          <?php foreach ($categorias as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
          <?php endforeach; ?>
        </select>

        <!-- Tipo de pagamento -->
        <select name="tipo_pagamento" required>
          <option value="Dinheiro">Dinheiro</option>
          <option value="Cartão">Cartão</option>
          <option value="Pix">Pix</option>
          <option value="Boleto">Boleto</option>
        </select>

        <input type="date" name="data" required>
        <button type="submit">Adicionar despesa</button>
      </form>

      <!-- Tabela de despesas -->
      <table class="tabela-despesas">
        <thead>
          <tr>
            <th>Descrição</th>
            <th>Valor</th>
            <th>Categoria</th>
            <th>Tipo de Pagamento</th> 
            <th>Data</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($transacoes as $t): ?>
            <tr>
              <td><?= htmlspecialchars($t['descricao']) ?></td>
              <td>R$ <?= number_format($t['valor'], 2, ',', '.') ?></td>
              <td><?= htmlspecialchars($t['categoria']) ?></td>
              <td><?= htmlspecialchars($t['tipo_pagamento']) ?></td>
              <td><?= date('d/m/Y', strtotime($t['data'])) ?></td>
              <td>
                <form action="api/transacoes.php?action=update" method="POST" style="display:inline;">
                  <input type="hidden" name="id" value="<?= $t['id'] ?>">
                  <input type="text" name="descricao" value="<?= htmlspecialchars($t['descricao']) ?>">
                  <input type="number" step="0.01" name="valor" value="<?= $t['valor'] ?>">
                  <input type="text" name="categoria" value="<?= htmlspecialchars($t['categoria']) ?>">
                  <select name="tipo_pagamento">
                    <option value="Dinheiro" <?= $t['tipo_pagamento']=='Dinheiro'?'selected':'' ?>>Dinheiro</option>
                    <option value="Cartão" <?= $t['tipo_pagamento']=='Cartão'?'selected':'' ?>>Cartão</option>
                    <option value="Pix" <?= $t['tipo_pagamento']=='Pix'?'selected':'' ?>>Pix</option>
                    <option value="Boleto" <?= $t['tipo_pagamento']=='Boleto'?'selected':'' ?>>Boleto</option>
                  </select>
                  <input type="date" name="data" value="<?= $t['data'] ?>">
                  <button type="submit">Salvar</button>
                </form>
                <a href="api/transacoes.php?action=delete&id=<?= $t['id'] ?>" onclick="return confirm('Excluir esta despesa?')">🗑️</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </main>
  </div>
</body>
</html>
