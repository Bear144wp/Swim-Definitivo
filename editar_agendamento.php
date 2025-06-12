<?php
// Inicia a sessão pra saber quem está logado
session_start();

// Se o usuário não estiver logado, redireciona pra página de login
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

// Conecta com o banco de dados
include("conexao.php");

// Verifica se foi passado um ID pela URL (ex: editar_agendamento.php?id=3)
if (!isset($_GET['id'])) {
    echo "ID do agendamento não informado.";
    exit;
}

// Pega o ID vindo da URL e converte pra número com intval() (por segurança)
$id_agendamento = intval($_GET['id']);

// Pega o ID do usuário logado (cliente)
$id_usuario = $_SESSION['id'];

// Consulta no banco para buscar os dados do agendamento
// Fazemos um JOIN com a tabela de carros pra poder mostrar o modelo e a placa também
// E ainda garantimos que o carro pertence ao cliente logado
$sql = "SELECT a.*, c.modelo, c.placa
        FROM agendamentos a
        JOIN carros c ON a.id_carro = c.id
        WHERE a.id = $id_agendamento AND c.id_usuario = $id_usuario";

$result = $conn->query($sql);

// Se não achou nada, ou o agendamento não for do usuário, bloqueia
if ($result->num_rows === 0) {
    echo "Agendamento não encontrado ou não pertence a você.";
    exit;
}

// Pega os dados do agendamento como array associativo
$agendamento = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Agendamento</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <h2>Editar Agendamento</h2>

        <!-- Exibe o carro que será editado -->
        <p><strong>Carro:</strong> <?php echo $agendamento['modelo']; ?> - <?php echo $agendamento['placa']; ?></p>

        <!-- Formulário que envia os dados pra atualizar_agendamento.php -->
        <!-- Aqui usamos POST porque estamos enviando dados que vão alterar o banco -->
        <form action="atualizar_agendamento.php" method="POST">
            <!-- Campo escondido com o ID do agendamento que está sendo editado -->
            <input type="hidden" name="id" value="<?php echo $agendamento['id']; ?>">

            <!-- Campo de data (já preenchido com a data atual do agendamento) -->
            <label>Data:</label><br>
            <input type="date" name="data" value="<?php echo $agendamento['data']; ?>" required><br><br>

            <!-- Campo de descrição (também já preenchido) -->
            <label>Descrição:</label><br>
            <textarea name="descricao" required><?php echo $agendamento['descricao']; ?></textarea><br><br>

            <!-- Botão pra salvar -->
            <button type="submit">Salvar Alterações</button>
        </form>

        <!-- Link pra voltar à página de agendamentos -->
        <br><a href="meus_agendamentos.php" class="btn">Cancelar / Voltar</a>
    </div>
</body>
</html>
