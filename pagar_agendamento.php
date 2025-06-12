<?php
session_start(); // Começa a sessão pra poder usar os dados do usuário logado

include("conexao.php"); // Conecta com o banco

// Verifica se o usuário está logado e se ele é do tipo "cliente"
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente') {
    // Se não estiver logado ou não for cliente, manda de volta pro login
    header("Location: index.php");
    exit(); // Encerra o script aqui mesmo
}

// Pega o ID do agendamento pela URL usando o método GET
$id = $_GET['id'];

// Faz a atualização no banco marcando o agendamento como pago
$sql = "UPDATE agendamentos SET pago = 'sim' WHERE id = $id";

// Executa a query e redireciona se deu certo
if ($conn->query($sql) === TRUE) {
    // Se funcionou, volta pra página com a lista de agendamentos
    header("Location: meus_agendamentos.php");
} else {
    // Se deu erro, exibe a mensagem
    echo "Erro ao processar pagamento.";
}
?>
