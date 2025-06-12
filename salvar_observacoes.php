<?php
// Começa a sessão pra verificar quem tá logado
session_start();

// Conecta com o banco
include("conexao.php");

// Verifica se o usuário tá logado e se é um mecânico
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'mecanico') {
    header("Location: index.php"); // Se não for, manda pro login
    exit();
}

// Pega o ID do agendamento vindo do formulário
$id = $_POST['id'];

// Pega as observações que o mecânico escreveu
$obs = $_POST['observacoes_mecanico'];

// Atualiza no banco a observação daquele agendamento específico
$sql = "UPDATE agendamentos SET observacoes_mecanico = '$obs' WHERE id = $id";

// Se deu certo, redireciona de volta pra lista de agendamentos
if ($conn->query($sql) === TRUE) {
    header("Location: agendamentos_recebidos.php");
} else {
    // Se deu erro, mostra a mensagem
    echo "Erro ao salvar observações.";
}
?>
