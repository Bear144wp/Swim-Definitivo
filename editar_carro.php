<?php
// Inicia a sessão pra poder acessar os dados de quem está logado
session_start();

// Se o usuário não estiver logado, manda de volta pro index
if (!isset($_SESSION['id'])) {
  header("Location: index.php");
  exit;
}

// Conecta com o banco de dados
include("conexao.php");

// Verifica se o ID do carro foi passado pela URL (via GET)
// Ex: editar_carro.php?id=3
if (!isset($_GET['id'])) {
    echo "ID do carro não informado!";
    exit;
}

// Converte o ID para número (segurança básica contra códigos maliciosos)
$id_carro = intval($_GET['id']);

// Busca no banco os dados desse carro
// Só vai trazer o carro se ele for do usuário logado (id_usuario = $_SESSION['id'])
$sql = "SELECT * FROM carros WHERE id = $id_carro AND id_usuario = " . $_SESSION['id'];
$result = $conn->query($sql);

// Se não encontrou o carro, ou não for do usuário, mostra erro
if ($result->num_rows == 0) {
    echo "Carro não encontrado ou você não tem permissão!";
    exit;
}

// Se achou, pega os dados do carro
$carro = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Carro</title>
  <link rel="stylesheet" href="estilo.css"> <!-- Puxa o estilo visual -->
</head>
<body>
  <div class="container">
    <h2>Editar Carro</h2>

    <!-- Formulário que envia os dados editados para atualizar_carro.php -->
    <!-- Usamos POST aqui porque estamos enviando dados sensíveis (modelo, placa, ano) que vão alterar o banco -->
    <form action="atualizar_carro.php" method="POST">
      <!-- Campo escondido com o ID do carro (pra saber qual carro será editado) -->
      <input type="hidden" name="id" value="<?php echo $carro['id']; ?>">

      <!-- Campo do modelo do carro, já preenchido com o valor atual -->
      <label>Modelo:</label><br>
      <input type="text" name="modelo" value="<?php echo $carro['modelo']; ?>" required><br><br>

      <!-- Campo da placa -->
      <label>Placa:</label><br>
      <input type="text" name="placa" value="<?php echo $carro['placa']; ?>" required><br><br>

      <!-- Campo do ano -->
      <label>Ano:</label><br>
      <input type="number" name="ano" value="<?php echo $carro['ano']; ?>" required><br><br>

      <!-- Botão pra salvar as alterações -->
      <button type="submit">Salvar Alterações</button>
    </form>

    <!-- Link pra voltar à listagem de carros -->
    <br><a href="meus_carros.php">Voltar</a>
  </div>
</body>
</html>
