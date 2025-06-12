<?php
// Inicia a sessão pra saber quem está logado
session_start();

// Conecta com o banco de dados
include("conexao.php");

// Verifica se o usuário está logado E se é do tipo 'mecanico'
// Se não for, redireciona pro login
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'mecanico') {
    header("Location: index.php");
    exit();
}

// Pega o ID do agendamento que foi passado na URL (ex: editar_manutencao.php?id=3)
// Aqui usamos $_GET porque o ID vem pela URL
$id = $_GET['id'];

// Faz uma consulta no banco pra pegar os dados desse agendamento
// JOIN com a tabela de carros só pra conseguir pegar modelo e placa e exibir no título
$sql = "SELECT a.*, c.modelo, c.placa FROM agendamentos a
        JOIN carros c ON a.id_carro = c.id
        WHERE a.id = $id";

// Executa a consulta
$result = $conn->query($sql);

// Transforma o resultado em array associativo pra acessar os campos depois
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Manutenção</title>
    <link rel="stylesheet" href="estilo.css"> <!-- Estilo visual -->
</head>
<body>
<div class="container">
    <!-- Título com o modelo e placa do carro que está sendo editado -->
    <h2>Editar Manutenção - <?php echo $row['modelo'] . " (" . $row['placa'] . ")"; ?></h2>

    <!-- Formulário pra editar as observações do mecânico -->
    <!-- Aqui usamos POST porque vamos enviar informações que serão salvas no banco -->
    <form action="salvar_observacoes.php" method="POST">
        <!-- Campo escondido com o ID do agendamento -->
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <!-- Campo de texto para o mecânico escrever suas observações -->
        <label>Observações do mecânico:</label><br>
        <textarea name="observacoes_mecanico" rows="6" cols="50"><?php echo $row['observacoes_mecanico']; ?></textarea><br><br>

        <!-- Botão pra salvar -->
        <button type="submit">Salvar</button>
    </form>

    <!-- Link pra voltar à lista de agendamentos -->
    <br><a href="agendamentos_recebidos.php">Voltar</a>
</div>
</body>
</html>
