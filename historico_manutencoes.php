<?php
// Inicia a sessão pra acessar os dados do usuário logado
session_start();

// Inclui a conexão com o banco de dados
include("conexao.php");

// Pega o tipo de usuário (cliente, mecanico ou admin) e o ID dele
$tipo = $_SESSION['tipo'];
$id_usuario = $_SESSION['id'];

// Inicializa a variável da consulta
$sql = "";

// Se for cliente, mostra só o histórico dele (das manutenções concluídas dos carros que ele cadastrou)
if ($tipo === 'cliente') {
    $sql = "SELECT a.*, c.modelo, c.placa 
            FROM agendamentos a
            JOIN carros c ON a.id_carro = c.id
            WHERE c.id_usuario = $id_usuario AND a.status = 'concluido'";
}
// Se for mecânico, mostra todas as manutenções concluídas, incluindo nome do cliente
elseif ($tipo === 'mecanico') {
    $sql = "SELECT a.*, c.modelo, c.placa, u.nome AS cliente_nome 
            FROM agendamentos a
            JOIN carros c ON a.id_carro = c.id
            JOIN usuarios u ON c.id_usuario = u.id
            WHERE a.status = 'concluido'";
}
// Se for admin, mostra tudo também (igual ao mecânico)
elseif ($tipo === 'admin') {
    $sql = "SELECT a.*, c.modelo, c.placa, u.nome AS cliente_nome 
            FROM agendamentos a
            JOIN carros c ON a.id_carro = c.id
            JOIN usuarios u ON c.id_usuario = u.id
            WHERE a.status = 'concluido'";
}

// Executa a consulta e guarda o resultado
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Manutenções</title>
    <!-- CSS do sistema -->
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
    <h2>Histórico de Manutenções</h2>

    <!-- Se tiver resultados, monta a tabela -->
    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>Modelo</th>
                <th>Placa</th>
                <th>Data</th>
                <th>Descrição</th>
                <!-- Só mostra o nome do cliente se for mecânico ou admin -->
                <?php if ($tipo !== 'cliente') echo "<th>Cliente</th>"; ?>
            </tr>

            <!-- Loop pra mostrar cada linha com os dados -->
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row["modelo"]; ?></td>
                <td><?php echo $row["placa"]; ?></td>
                <td><?php echo $row["data"]; ?></td>
                <td><?php echo $row["descricao"]; ?></td>
                <?php if ($tipo !== 'cliente') echo "<td>" . $row["cliente_nome"] . "</td>"; ?>
            </tr>
            <?php endwhile; ?>
        </table>
    
    <!-- Se não tiver nenhum resultado -->
    <?php else: ?>
        <p>Nenhuma manutenção concluída encontrada.</p>
    <?php endif; ?>

    <!-- Link pra voltar pro painel correspondente (cliente, mecanico ou admin) -->
    <br><a href="painel_<?php echo $tipo; ?>.php">Voltar ao painel</a>
</div>
</body>
</html>

