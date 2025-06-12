<?php
// Inicia a sessão pra saber quem tá logado
session_start();

// Aqui ele verifica se tem alguém logado e se é do tipo "cliente"
// Se não for, manda de volta pro login (index.php)
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente') {
  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Carro</title>
  <!-- Link do CSS pra deixar a página com o estilo definido -->
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
  <div class="container">
    <h2>Cadastrar Novo Carro</h2>

    <!-- Formulário para cadastrar um carro novo -->
    <!-- O método POST é usado pra enviar as infos de forma mais segura pro servidor -->
    <form action="cadastrar_carro.php" method="POST">
      
      <!-- Campo pro usuário digitar o modelo do carro -->
      <label>Modelo:</label>
      <input type="text" name="modelo" required><br><br>

      <!-- Campo pra digitar a placa do carro -->
      <label>Placa:</label>
      <input type="text" name="placa" required><br><br>

      <!-- Campo pro ano de fabricação do carro -->
      <label>Ano:</label>
      <input type="number" name="ano" required><br><br>

      <!-- Botão que envia os dados pro PHP salvar no banco -->
      <button type="submit">Cadastrar</button>
    </form>

    <!-- Link pra voltar ao painel do cliente -->
    <br><a href="painel_cliente.php">Voltar</a>
  </div>
</body>
</html>