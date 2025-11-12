<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro - fin-docker</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
    <h1>Cadastro</h1>
    <form action="api/registro.php" method="POST">
      <input type="text" name="usuario" placeholder="Nome de usuário" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <input type="number" step="0.01" name="receita_mensal" placeholder="Receita mensal (R$)" required>
      <button type="submit">Criar conta</button>
    </form>
    <p style="text-align:center; margin-top:1rem;">
      <a href="index.html" style="color:#03dac6;">Já tem conta? Entrar</a>
    </p>
  </div>
</body>
</html>
