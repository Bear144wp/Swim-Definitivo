<?php
// Inicia a sessão. Isso é necessário pra acessar os dados do usuário logado (como ID e tipo)
session_start();

// Verifica se o usuário está logado. Se não tiver, manda pra tela de login
if (!isset($_SESSION['id'])) {
  header("Location: index.php");
  exit; // para tudo aqui mesmo, não deixa continuar
}

// Conecta com o banco de dados
include("conexao.php");

// Pegamos o ID do usuário que está logado usando a sessão
$id_usuario = $_SESSION['id'];

// Monta a consulta SQL pra buscar todos os agendamentos que estão relacionados com os carros do usuário
// Estamos juntando a tabela 'agendamentos' com 'carros' pra puxar também o modelo e placa do carro
$sql = "SELECT a.*, c.modelo, c.placa 
        FROM agendamentos a
        JOIN carros c ON a.id_carro = c.id
        WHERE c.id_usuario = $id_usuario
        ORDER BY a.data DESC";

// Executa a consulta no banco
$result = $conn->query($sql);
?>

<!-- Parte visual da página -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Meus Agendamentos</title>
  <link rel="stylesheet" href="estilo.css"> <!-- linka com o CSS -->
</head>
<body>
<div class="container">
  <h2>Minhas Manutenções Agendadas</h2>

  <?php if ($result->num_rows > 0): ?> <!-- Verifica se veio algum agendamento do banco -->
    <table border="1" cellpadding="8">
      <tr>
        <th>Carro</th>
        <th>Placa</th>
        <th>Data</th>
        <th>Descrição</th>
        <th>Status</th>
      </tr>

      <!-- Agora começa a exibir cada linha (agendamento) da tabela -->
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo $row["modelo"]; ?></td>
        <td><?php echo $row["placa"]; ?></td>
        <td><?php echo $row["data"]; ?></td>
        <td><?php echo $row["descricao"]; ?></td>
        <td>
<?php
  // Se o status for pendente, mostra a palavra em laranja + botão de editar
  if ($row["status"] === "pendente") {
    echo "<span style='color: orange; font-weight: bold;'>Pendente</span>";
    echo " | <a href='editar_agendamento.php?id={$row['id']}'>Editar</a>";
  } 
  // Se estiver concluído, mostra em verde
  elseif ($row["status"] === "concluido") {
    echo "<span style='color: green; font-weight: bold;'>Concluído</span>";
  }

  // Sempre mostra a opção de excluir com confirmação
  echo " | <a href='excluir_agendamento.php?id={$row['id']}' onclick=\"return confirm('Tem certeza que deseja excluir este agendamento?');\">Excluir</a>";

  // Se ainda não foi pago, mostra botão de pagamento
  if ($row["pago"] === "nao") {
    echo " | <a href='pagar_agendamento.php?id={$row['id']}'>Pagar</a>";
  } else {
    echo " | <span style='color: blue;'>Pago</span>";
  }

  // Se a manutenção já foi concluída e ainda não foi avaliada, mostra botão de avaliação
  if ($row["status"] === "concluido" && empty($row["avaliacao"])) {
    echo " | <a href='avaliar_mecanico.php?id={$row['id']}'>Avaliar</a>";
  } 
  // Senão, mostra que já foi avaliado
  elseif (!empty($row["avaliacao"])) {
    echo " | <span style='color: purple;'>Avaliado</span>";
  }
?>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>Você ainda não agendou nenhuma manutenção.</p>
  <?php endif; ?>

  <br><a href="painel_cliente.php">Voltar ao Painel</a>
</div>
</body>
</html>
