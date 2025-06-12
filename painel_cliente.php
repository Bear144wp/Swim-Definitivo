<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente') {
    header("Location: index.php");
    exit();
}

include("conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Cliente</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
    <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
    <p>Você está logado como <strong>Cliente</strong>.</p>

    <?php
    $id_usuario = $_SESSION['id'];
    $avisos = "";

    // Consulta 1: próxima manutenção
    $sqlProxima = "SELECT * FROM agendamentos a
                   JOIN carros c ON a.id_carro = c.id
                   WHERE c.id_usuario = $id_usuario AND a.data >= CURDATE()
                   ORDER BY a.data ASC LIMIT 1";
    $resProxima = $conn->query($sqlProxima);
    if ($resProxima->num_rows > 0) {
        $prox = $resProxima->fetch_assoc();
        $avisos .= "<p style='color: orange; font-weight: bold;'>📅 Próxima manutenção agendada: " . date('d/m/Y', strtotime($prox['data'])) . "</p>";
    }

    echo $avisos;
    ?>

    <a href="form_carro.php" class="btn">Cadastrar Carro</a><br><br>
    <a href="meus_carros.php" class="btn">Ver Meus Carros</a><br><br>
    <a href="form_agendar.php" class="btn">Agendar Manutenção</a><br><br>
    <a href="meus_agendamentos.php" class="btn">Ver Meus Agendamentos</a><br><br>
    <a href="relatorio_concluidos.php" class="btn">Manutenções Concluídas</a><br><br>
    <a href="historico_manutencoes.php" class="btn">Histórico de Manutenções</a><br><br>

    <h3>🛠️ Falar com um mecânico</h3>

    <?php
    // Lista todos os mecânicos do sistema
    $sqlMecanicos = "SELECT id, nome FROM usuarios WHERE tipo = 'mecanico'";
    $resMec = $conn->query($sqlMecanicos);

    if ($resMec->num_rows > 0):
        while ($mecanico = $resMec->fetch_assoc()):
    ?>
        <p>
            <?php echo $mecanico['nome']; ?> —
            <a class="btn" href="chat.php?id_cliente=<?php echo $id_usuario; ?>&id_mecanico=<?php echo $mecanico['id']; ?>">
                Conversar
            </a>
        </p>
    <?php
        endwhile;
    else:
        echo "<p style='color: red;'>Nenhum mecânico cadastrado no sistema.</p>";
    endif;
    ?>

    <br><a href="logout.php" class="btn">Sair</a>
</div>
</body>
</html>
