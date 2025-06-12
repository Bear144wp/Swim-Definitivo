<?php

$host = "localhost"; 
$usuario = "root";         
$senha = "";                
$banco = "sistema_manutencao"; 

// Cria a conexão usando mysqli (MySQL Improved)
// Passa as informações acima para se conectar ao banco
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica se deu algum erro na hora de conectar
// connect_error guarda a mensagem de erro se a conexão falhar
if ($conn->connect_error) {
    // Se der erro, para tudo e mostra a mensagem na tela
    die("Erro na conexão: " . $conn->connect_error);
}

?>
