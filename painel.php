<?php
// Inicia a sessão pra poder acessar os dados do usuário logado
session_start();

// Verifica se o usuário está logado. Se não estiver, redireciona pro login
if (!isset($_SESSION['id'])) {
  header("Location: index.php");
  exit; // Para o código aqui
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel</title>
  <!-- Estilo externo do painel -->
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
  <!-- Saudação personalizada com o nome do usuário -->
  <h2>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h2>

  <!-- Mostra o tipo de conta (cliente, mecanico, admin) -->
  <p>Tipo de usuário: <?php echo $_SESSION['tipo']; ?></p>

  <!-- Lista de funcionalidades acessíveis ao cliente -->
  <ul>
    <!-- Formulário para cadastrar um novo carro -->
    <li><a href="form_carro.php">Cadastrar Carro</a></li>

    <!-- Agendar uma nova manutenção para um dos carros -->
    <li><a href="form_agendar.php">Agendar Manutenção</a></li>

    <!-- Ver todos os carros já cadastrados -->
    <li><a href="meus_carros.php">Ver Meus Carros</a></li>

    <!-- Ver todos os agendamentos (pendentes, concluídos etc.) -->
    <li><a href="meus_agendamentos.php">Ver Agendamentos</a></li>

    <!-- Relatório com histórico de manutenções já concluídas -->
    <li><a href="relatorio_concluidos.php">Ver Manutenções Concluídas</a></li>

    <!-- Encerra a sessão e volta para o login -->
    <li><a href="logout.php">Sair</a></li>
  </ul>
</div>
</body>
</html>


