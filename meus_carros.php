<?php
// Inicia a sessão para poder acessar dados do usuário logado
session_start();

// Verifica se o usuário está logado. Se não estiver, redireciona para a tela de login
if (!isset($_SESSION['id'])) {
  header("Location: index.php");
  exit; // para a execução do script
}

// Inclui o arquivo que faz a conexão com o banco de dados
include("conexao.php");

// Pega o ID do usuário logado através da variável de sessão
$id_usuario = $_SESSION['id'];

// Cria a consulta SQL para buscar todos os carros cadastrados por esse usuário
$sql = "SELECT * FROM carros WHERE id_usuario = $id_usuario";
$result = $conn->query($sql); // Executa a consulta e armazena o resultado
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Meus Carros</title>
  <link rel="stylesheet" href="estilo.css"> <!-- Aplica o CSS do projeto -->
</head>
<body>
<div class="container">
  <h2>Meus Carros Cadastrados</h2>

  <!-- Se o resultado da consulta tiver pelo menos 1 carro -->
  <?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="8">
      <tr>
        <th>ID</th>
        <th>Modelo</th>
        <th>Placa</th>
        <th>Ano</th>
        <th>Ações</th>
      </tr>

      <!-- Para cada carro encontrado no banco, ele cria uma linha na tabela -->
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo $row['id']; ?></td> <!-- Aqui estava faltando o ID, foi corrigido -->
        <td><?php echo $row['modelo']; ?></td>
        <td><?php echo $row['placa']; ?></td>
        <td><?php echo $row['ano']; ?></td>
        <td>
          <!-- Botões de ação: editar e excluir, com o ID do carro passado pela URL -->
          <a href="editar_carro.php?id=<?php echo $row['id']; ?>">Editar</a> |
          <a href="excluir_carro.php?id=<?php echo $row['id']; ?>" 
             onclick="return confirm('Tem certeza que deseja excluir este carro?')">Excluir</a>
        </td>
      </tr>
      <?php endwhile; ?>

    </table>
  <?php else: ?>
    <!-- Caso não tenha nenhum carro cadastrado -->
    <p>Você ainda não cadastrou nenhum carro.</p>
  <?php endif; ?>

  <br><a href="painel_cliente.php">Voltar ao Painel</a>
</div>
</body>
</html>

