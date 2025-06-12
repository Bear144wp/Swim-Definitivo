<?php
session_start();

/*
Verificação de segurança:
Aqui a gente checa se a pessoa que entrou nessa página realmente está logada 
(ou seja, tem o 'id' na sessão) e se ela é do tipo 'mecanico'.

Isso evita que pessoas não autorizadas (tipo um cliente ou até alguém que nem logou) 
acessem a área da mecânica digitando o link direto no navegador.
*/
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'mecanico') {
    // Se não passou na verificação, redireciona pra página inicial (index.php)
    header("Location: index.php");
    exit(); // Encerra o script pra garantir que o resto da página não carregue
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel da Mecânica</title>
    <link rel="stylesheet" href="estilo.css"> <!-- Link do CSS pro estilo da página -->
</head>
<body>
    <div class="container">
        <!-- Mostra uma mensagem de boas-vindas com o nome do mecânico logado -->
        <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
        <p>Você está logado como <strong>Mecânica</strong>.</p>

        <!-- Botão que leva pra página onde o mecânico vê os agendamentos recebidos -->
        <a href="agendamentos_recebidos.php" class="btn">Ver Agendamentos</a><br><br>

        <!-- Botão de exemplo para conversar com um cliente específico (ID 11 no caso) -->
        <a href="chat.php?cliente=11" class="btn">Falar com Cliente</a><br><br>

        <!-- Botão para sair do sistema e encerrar a sessão -->
        <a href="logout.php" class="btn">Sair</a>
    </div>
</body>
</html>
