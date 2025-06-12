<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'mecanico') {
    header("Location: index.php");
    exit();
}

include("conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel da Mecânica</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
    <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
    <p>Você está logado como <strong>Mecânica</strong>.</p>

    <a href="agendamentos_recebidos.php" class="btn">Ver Agendamentos</a><br><br>

    <h3>👤 Falar com um cliente</h3>

    <?php
    $id_mecanico = $_SESSION['id'];

    // Lista todos os clientes cadastrados
    $sqlClientes = "SELECT id, nome FROM usuarios WHERE tipo = 'cliente'";
    $resClientes = $conn->query($sqlClientes);

    if ($resClientes->num_rows > 0):
        while ($cliente = $resClientes->fetch_assoc()):
    ?>
        <p>
            <?php echo $cliente['nome']; ?> —
            <a class="btn" href="chat.php?id_cliente=<?php echo $cliente['id']; ?>&id_mecanico=<?php echo $id_mecanico; ?>">
                Conversar
            </a>
        </p>
    <?php
        endwhile;
    else:
        echo "<p style='color: red;'>Nenhum cliente cadastrado no sistema.</p>";
    endif;
    ?>

    <br><a href="logout.php" class="btn">Sair</a>
</div>
</body>
</html>
