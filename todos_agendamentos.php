<?php
// Inicia a sessão para acessar os dados do usuário logado
session_start();

// Verifica se o usuário está logado e se é um administrador
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'admin') {
    // Se não estiver logado ou não for admin, redireciona para a tela de login
    header("Location: index.php");
    exit;
}

// Inclui a conexão com o banco de dados
include("conexao.php");

// Consulta SQL que busca todos os agendamentos, incluindo:
// nome do cliente, modelo e placa do carro, ordenados pela data (do mais recente pro mais antigo)
$sql = "SELECT a.*, u.nome AS cliente, c.modelo, c.placa
        FROM agendamentos a
        JOIN carros c ON a.id_carro = c.id
        JOIN usuarios u ON c.id_usuario = u.id
        ORDER BY a.data DESC";

// Executa a consulta
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Todos os Agendamentos</title>
    <!-- Link para o CSS externo -->
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
    <h2>Todos os Agendamentos do Sistema</h2>

    <!-- Verifica se a consulta retornou resultados -->
    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>Cliente</th>
                <th>Carro</th>
                <th>Placa</th>
                <th>Data</th>
                <th>Descrição</th>
                <th>Status</th>
            </tr>
            <!-- Percorre todos os resultados e exibe na tabela -->
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["cliente"]; ?></td>
                    <td><?php echo $row["modelo"]; ?></td>
                    <td><?php echo $row["placa"]; ?></td>
                    <td><?php echo $row["data"]; ?></td>
                    <td><?php echo $row["descricao"]; ?></td>
                    <td><?php echo ucfirst($row["status"]); ?></td> <!-- ucfirst coloca a primeira letra em maiúscula -->
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Nenhum agendamento encontrado.</p>
    <?php endif; ?>

    <!-- Link para voltar ao painel do administrador -->
    <br><a href="painel_admin.php" class="btn">Voltar ao Painel</a>
</div>
</body>
</html>
