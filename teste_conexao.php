<?php
// Inclui o arquivo que faz a conexão com o banco de dados
include("conexao.php");

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    // Se deu erro, exibe a mensagem e encerra o script
    die("Falha na conexão: " . $conn->connect_error);
} else {
    // Se deu certo, mostra mensagem de sucesso
    echo "Conexão bem-sucedida com o banco de dados!";
}
?>
