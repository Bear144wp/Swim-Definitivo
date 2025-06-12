<?php
// Inicia a sessão pra sabermos quem está logado
session_start();

// Se o usuário não estiver logado, manda ele pro login
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

// Conecta com o banco de dados
include("conexao.php");

// Verifica se foi passado um ID na URL (via GET)
if (isset($_GET['id'])) {
    // Converte o ID pra número inteiro por segurança
    $id = intval($_GET['id']);

    // Monta o comando SQL pra deletar o agendamento com esse ID
    $sql = "DELETE FROM agendamentos WHERE id = $id";

    // Executa o comando no banco
    if ($conn->query($sql) === TRUE) {
        // Se der certo, redireciona pra página de listagem dos agendamentos
        header("Location: meus_agendamentos.php");
        exit;
    } else {
        // Se der erro na hora de deletar, mostra o erro na tela
        echo "Erro ao excluir: " . $conn->error;
    }
} else {
    // Caso o ID não tenha vindo na URL, mostra uma mensagem de erro
    echo "ID inválido.";
}
?>
