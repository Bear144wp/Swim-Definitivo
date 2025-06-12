<?php
session_start();
include("conexao.php");

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$tipo = $_POST['tipo']; // cliente, mecanica ou admin

// Verifica se o e-mail já existe
$verifica = $conn->query("SELECT * FROM usuarios WHERE email = '$email'");
if ($verifica->num_rows > 0) {
    echo "Este e-mail já está cadastrado. <a href='index.php'>Tentar novamente</a>";
    exit;
}

// Inserção
$sql = "INSERT INTO usuarios (nome, email, senha, tipo)
        VALUES ('$nome', '$email', '$senha', '$tipo')";

if ($conn->query($sql) === TRUE) {
    echo "Cadastro realizado com sucesso! <a href='index.php'>Fazer login</a>";
} else {
    echo "Erro ao cadastrar: " . $conn->error;
}
?>

