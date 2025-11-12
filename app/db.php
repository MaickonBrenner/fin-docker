<?php
# $db = new PDO('sqlite:/var/www/html/data/financas.db');
$db = new PDO('sqlite:/app_data/financas.db');

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS transacoes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  descricao TEXT,
  valor REAL,
  tipo TEXT,
  data TEXT
)");

$db->exec("CREATE TABLE IF NOT EXISTS categorias (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nome TEXT
)");

$db->exec("CREATE TABLE IF NOT EXISTS usuario (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nome TEXT UNIQUE NOT NULL,
  senha TEXT NOT NULL,
  receita_mensal REAL DEFAULT 0
)");
?>
