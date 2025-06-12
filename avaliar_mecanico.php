<?php
// Inicia a sessão pra saber quem está logado
session_start();

// Conecta com o banco
include("conexao.php");

// Verifica se o usuário está logado e se é do tipo cliente
// Se não for, manda pro login
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente') {
    header("Location: index.php");
    exit();
}

// Aqui a gente pega o ID da manutenção que veio pela URL (ex: ?id=7)
// Esse valor vem do link que o cliente clicou para avaliar
$id_agendamento = $_GET['id'];

// Agora vamos buscar no banco os dados desse agendamento
// A consulta já junta (JOIN) a tabela de agendamentos com a tabela de carros
// Assim conseguimos mostrar modelo e placa no formulário
// Também garantimos que o carro seja do cliente logado (segurança)
$sql = "SELECT a.*, c.modelo, c.placa FROM agendamentos a
        JOIN carros c ON a.id_carro = c.id
        WHERE a.id = $id_agendamento AND c.id_usuario = {$_SESSION['id']}";

// Executa a consulta e guarda o resultado
$result = $conn->query($sql);
$agendamento = $result->fetch_assoc(); // pega os dados como array associativo

// Aqui a gente confere se:
/// 1. Existe mesmo esse agendamento
/// 2. E se ele já foi concluído (só pode avaliar depois que terminou)
if (!$agendamento || $agendamento['status'] !== 'concluido') {
    echo "Manutenção inválida para avaliação.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Avaliar Mecânico</title>
  <link rel="stylesheet" href="estilo.css"> <!-- CSS pro layout bonitão -->
</head>
<body>
<div class="container">
  <h2>Avaliar Atendimento do Mecânico</h2>

  <!-- Mostra o modelo e placa do carro que foi atendido -->
  <p><strong>Carro:</strong> <?php echo $agendamento['modelo']; ?> (<?php echo $agendamento['placa']; ?>)</p>

  <!-- Formulário que envia a avaliação para outro arquivo (salvar_avaliacao.php) -->
  <form method="POST" action="salvar_avaliacao.php">
    
    <!-- Campo escondido com o ID do agendamento (pra saber qual manutenção foi avaliada) -->
    <input type="hidden" name="id" value="<?php echo $agendamento['id']; ?>">

    <!-- Campo da nota de 1 a 5 -->
    <label>Nota (1 a 5):</label><br>
    <input type="number" name="avaliacao" min="1" max="5" required><br><br>

    <!-- Campo pro cliente deixar um comentário sobre o atendimento -->
    <label>Comentário:</label><br>
    <textarea name="comentario" rows="4" cols="50" required></textarea><br><br>

    <!-- Botão pra enviar a avaliação -->
    <button type="submit">Enviar Avaliação</button>
  </form>

  <br>
  <!-- Link pra voltar à lista de agendamentos -->
  <a href="meus_agendamentos.php">Voltar</a>
</div>
</body>
</html>
