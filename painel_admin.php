<?php
session_start(); // Inicia a sessão para acessar dados do usuário logado

// Verifica se o usuário está logado e se é do tipo 'admin'
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'admin') {
    // Se não for administrador, redireciona para o login
    header("Location: index.php");
    exit(); // Encerra o script
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="estilo.css"> <!-- Importa o CSS do sistema -->
</head>
<body>
    <div class="container">
        <!-- Saudação personalizada com o nome do admin -->
        <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
        <p>Você está logado como <strong>Administrador</strong>.</p>

        <!-- Botões de navegação -->
        <a href="usuarios_listar.php" class="btn">Gerenciar Usuários</a>
        <a href="todos_agendamentos.php" class="btn">Ver Todos os Agendamentos</a>
        <a href="relatorio_concluidos.php" class="btn">Relatório de Manutenções Concluídas</a>
        <a href="logout.php" class="btn">Sair</a>
    </div>
</body>
</html>

