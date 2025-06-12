<?php
session_start();
include("conexao.php");

$id_usuario = $_SESSION['id'];
$modelo = $_POST['modelo'];
$placa = $_POST['placa'];
$ano = $_POST['ano'];

$sql = "INSERT INTO carros (id_usuario, modelo, placa, ano)
        VALUES ('$id_usuario', '$modelo', '$placa', '$ano')";

if ($conn->query($sql) === TRUE) {
    header("Location: meus_carros.php");
} else {
    echo "Erro ao cadastrar carro: " . $conn->error;
}
?>
