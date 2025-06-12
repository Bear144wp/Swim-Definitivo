<?php
// Inicia a sessão pra saber quem tá logado
session_start();

// Conecta com o banco de dados
include("conexao.php");

// Se o cara não estiver logado como cliente, manda de volta pro login
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente') {
    header("Location: index.php");
    exit();
}

// Pega os dados que vieram do formulário (avaliar_mecanico.php)
$id = $_POST['id']; // ID do agendamento
$nota = $_POST['avaliacao']; // Nota dada pelo cliente (ex: 4 ou 5)
$comentario = $_POST['comentario']; // Comentário escrito

// Atualiza o agendamento com a nota e o comentário que o cliente mandou
$sql = "UPDATE agendamentos 
        SET avaliacao = $nota, comentario = '$comentario' 
        WHERE id = $id";

// Se tudo deu certo, volta pra tela de agendamentos
if ($conn->query($sql) === TRUE) {
    header("Location: meus_agendamentos.php");
} else {
    // Se deu erro, mostra a mensagem
    echo "Erro ao salvar avaliação.";
}
?>
