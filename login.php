<?php
session_start();
include("conexao.php");

$email = $_POST['email'];
$senha = $_POST['senha'];

// Proteção contra SQL Injection
$email = mysqli_real_escape_string($conn, $email);
$senha = mysqli_real_escape_string($conn, $senha);

$sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    $_SESSION['id'] = $usuario['id'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['tipo'] = trim($usuario['tipo']); // <- remove qualquer espaço invisível

    // Redirecionamento por tipo de usuário
    switch ($_SESSION['tipo']) {
        case 'cliente':
            header("Location: painel_cliente.php");
            exit();
        case 'mecanico':
            header("Location: painel_mecanica.php");
            exit();
        case 'admin':
            header("Location: painel_admin.php");
            exit();
        default:
            header("Location: index.php"); 
            exit();
    }
    

} else {
    echo "Email ou senha incorretos!";
}
?>
