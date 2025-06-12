<?php
// Começa a sessão pra conseguir pegar os dados do usuário logado
session_start();

// Aqui eu confiro se o usuário tá logado e se ele é mecânico mesmo
// Se não for, já mando de volta pro index (segurança)
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'mecanico') {
    header("Location: index.php");
    exit;
}

// Puxo a conexão com o banco (aquele arquivo separado que conecta no MySQL)
include("conexao.php");

// Faço a consulta no banco pra pegar todos os agendamentos cadastrados
// Também já trago junto o nome do cliente, o modelo e a placa do carro usando JOIN
// - agendamentos: é onde tá marcado o serviço
// - carros: traz o modelo e placa
// - usuarios: é pra pegar o nome do cliente
$sql = "SELECT a.*, u.nome AS cliente, c.modelo, c.placa
        FROM agendamentos a
        JOIN carros c ON a.id_carro = c.id
        JOIN usuarios u ON c.id_usuario = u.id
        ORDER BY a.data DESC";

// Mando essa consulta pro banco e guardo o resultado em $result
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Agendamentos Recebidos</title>
  <!-- CSS do sistema pra deixar o visual mais bonito -->
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
  <h2>Agendamentos Recebidos</h2>

  <!-- Se tiver algum agendamento no banco, ele entra aqui -->
  <?php if ($result->num_rows > 0): ?>
  
    <!-- Monta a tabela pra mostrar os dados -->
    <table border="1" cellpadding="8">
      <tr>
        <th>Cliente</th>
        <th>Carro</th>
        <th>Placa</th>
        <th>Data</th>
        <th>Descrição</th>
        <th>Status</th>
        <th>Ação</th>
      </tr>

      <!-- Aqui começa o loop que vai mostrar linha por linha os dados -->
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo $row["cliente"]; ?></td> <!-- Nome do cliente -->
        <td><?php echo $row["modelo"]; ?></td> <!-- Modelo do carro -->
        <td><?php echo $row["placa"]; ?></td> <!-- Placa -->
        <td><?php echo $row["data"]; ?></td> <!-- Data da manutenção -->
        <td><?php echo $row["descricao"]; ?></td> <!-- O que vai ser feito -->
        <td><?php echo ucfirst($row["status"]); ?></td> <!-- Status atual -->

        <td>
          <!-- Só mostra o botão "Concluir" se ainda tiver pendente -->
          <?php if ($row["status"] === "pendente"): ?>
            <a href="concluir_agendamento.php?id=<?php echo $row['id']; ?>">Concluir</a> |
          <?php endif; ?>

          <!-- Botão pra editar esse agendamento -->
          <a href="editar_manutencao.php?id=<?php echo $row['id']; ?>">Editar</a>
        </td>
      </tr>
      <?php endwhile; ?> <!-- Termina o loop -->
    </table>

  <?php else: ?>
    <!-- Caso não tenha nenhum agendamento no banco -->
    <p>Nenhum agendamento encontrado.</p>
  <?php endif; ?>

  <br>
  <!-- Botão pra voltar pro painel principal do mecânico -->
  <a href="painel_mecanica.php" class="btn">Voltar</a>
</div>
</body>
</html>

