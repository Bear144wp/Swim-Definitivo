<?php
// Inicia a sessão para acessar dados do usuário logado
session_start();

// Se o usuário não estiver logado, redireciona para o login
if (!isset($_SESSION['id'])) {
  header("Location: index.php");
  exit;
}

// Conecta ao banco de dados
include("conexao.php");

// Verifica o tipo de usuário logado
// Se for administrador, mostra todas as manutenções concluídas do sistema
if ($_SESSION['tipo'] == 'admin') {
    $sql = "SELECT a.*, u.nome AS cliente, c.modelo, c.placa
            FROM agendamentos a
            JOIN carros c ON a.id_carro = c.id
            JOIN usuarios u ON c.id_usuario = u.id
            WHERE a.status = 'concluido'
            ORDER BY a.data DESC";
} else {
    // Se for cliente, mostra apenas suas próprias manutenções concluídas
    $id_usuario = $_SESSION['id'];
    $sql = "SELECT a.*, c.modelo, c.placa
            FROM agendamentos a
            JOIN carros c ON a.id_carro = c.id
            WHERE c.id_usuario = $id_usuario AND a.status = 'concluido'
            ORDER BY a.data DESC";
}

// Executa a consulta e armazena o resultado
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Relatório de Manutenções Concluídas</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
  <h2>Relatório de Manutenções Concluídas</h2>

  <!-- Verifica se há registros retornados pela consulta -->
  <?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="8">
      <tr>
        <!-- Mostra a coluna "Cliente" apenas se o usuário for admin -->
        <?php if ($_SESSION['tipo'] == 'admin'): ?>
            <th>Cliente</th>
        <?php endif; ?>
        <th>Carro</th>
        <th>Placa</th>
        <th>Data</th>
        <th>Descrição</th>
      </tr>

      <!-- Laço para exibir todas as manutenções concluídas -->
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <!-- Mostra o nome do cliente apenas para admin -->
        <?php if ($_SESSION['tipo'] == 'admin'): ?>
            <td><?php echo $row["cliente"]; ?></td>
        <?php endif; ?>
        <td><?php echo $row["modelo"]; ?></td>
        <td><?php echo $row["placa"]; ?></td>
        <td><?php echo $row["data"]; ?></td>
        <td><?php echo $row["descricao"]; ?></td>
      </tr>
      <?php endwhile; ?>
    </table>

  <!-- Caso não haja manutenções concluídas -->
  <?php else: ?>
    <p>
      <?php 
      echo $_SESSION['tipo'] == 'admin' 
        ? 'Nenhuma manutenção concluída encontrada.' 
        : 'Você ainda não tem manutenções concluídas.'; 
      ?>
    </p>
  <?php endif; ?>

  <!-- Botão de voltar ao painel, adaptado ao tipo do usuário -->
  <br>
  <a href="<?php echo ($_SESSION['tipo'] == 'admin') ? 'painel_admin.php' : 'painel_cliente.php'; ?>" class="btn">Voltar ao Painel</a>
</div>
</body>
</html>

