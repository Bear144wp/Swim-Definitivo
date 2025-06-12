<?php
// Inicia a sessão pra poder acessar os dados do usuário logado
session_start();

// Verifica se o usuário está logado. Se não estiver, manda de volta pro login
if (!isset($_SESSION['id'])) {
  header("Location: index.php");
  exit;
}

// Conecta com o banco de dados (arquivo com os dados de conexão)
include("conexao.php");

// Pega o ID do usuário que está logado
$id_usuario = $_SESSION['id'];

// Monta a consulta SQL para buscar os carros desse usuário
$sql = "SELECT id, modelo, placa FROM carros WHERE id_usuario = $id_usuario";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Agendar Manutenção</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
  <h2>Agendar Manutenção</h2>

  <!-- Formulário que envia os dados com o método POST -->
  <!-- A gente usa POST aqui porque é mais seguro pra enviar dados que vão pro banco -->
  <form action="agendar_salvar.php" method="POST">
    
    <!-- Dropdown com os carros cadastrados pelo usuário -->
    <label>Selecione o carro:</label><br>
    <select name="id_carro" required>
      <option value="">-- Selecione --</option>

      <!-- Faz um loop pra mostrar cada carro do usuário como uma opção -->
      <?php while ($row = $result->fetch_assoc()): ?>
        <option value="<?php echo $row['id']; ?>">
          <?php echo $row['modelo'] . " - " . $row['placa']; ?>
        </option>
      <?php endwhile; ?>
    </select><br><br>

    <!-- Campo para escolher a data da manutenção -->
    <label>Data da manutenção:</label><br>
    <input type="date" name="data" required><br><br>

    <!-- Campo para descrever o problema ou o serviço que precisa ser feito -->
    <label>Descrição:</label><br>
    <textarea name="descricao" rows="4" cols="40" required></textarea><br><br>

    <!-- Botão que envia o formulário para o agendar_salvar.php -->
    <button type="submit">Agendar</button>
  </form>

  <br><a href="painel.php">Voltar ao Painel</a>
</div>
</body>
</html>
