<?php
session_start();
include("conexao.php");

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$usuario_id = $_SESSION['id'];
$usuario_tipo = $_SESSION['tipo'];

// Agora os IDs vêm da URL
$id_cliente = isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : 0;
$id_mecanico = isset($_GET['id_mecanico']) ? intval($_GET['id_mecanico']) : 0;

// Validação básica
if ($id_cliente <= 0 || $id_mecanico <= 0) {
    die("IDs inválidos para iniciar o chat.");
}


// Envia a mensagem
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mensagem'])) {
    $mensagem = $conn->real_escape_string($_POST['mensagem']);
    $autor = $usuario_tipo;
    $conn->query("INSERT INTO mensagens (id_cliente, id_mecanico, autor, mensagem, data_envio) 
                  VALUES ($id_cliente, $id_mecanico, '$autor', '$mensagem', NOW())");
}

// Consulta as mensagens
if ($id_cliente > 0 && $id_mecanico > 0) {
    $mensagens = $conn->query("SELECT * FROM mensagens 
                               WHERE id_cliente = $id_cliente AND id_mecanico = $id_mecanico 
                               ORDER BY data_envio ASC");
} else {
    die("Não foi possível identificar a conversa entre cliente e mecânico.");
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Chat</title>
  <link rel="stylesheet" href="estilo.css">
  <style>
    body { color: black; }
    .mensagem strong { color: black; }
    .mensagem small { font-size: 10px; color: #555; }
  </style>
</head>
<body>
<div class="container">
  <h2>Chat entre Cliente e Mecânico</h2>

  <div style="border: 1px solid #ccc; padding: 10px; height: 300px; overflow-y: scroll; background: #f9f9f9;">
    <?php while($msg = $mensagens->fetch_assoc()): ?>
      <p class="mensagem">
        <strong><?php echo ucfirst($msg['autor']); ?>:</strong> <?php echo $msg['mensagem']; ?><br>
        <small><?php echo date('d/m/Y H:i', strtotime($msg['data_envio'])); ?></small>
      </p>
      <hr>
    <?php endwhile; ?>
  </div>

  <form method="POST" style="margin-top: 15px;">
    <textarea name="mensagem" rows="3" cols="50" required placeholder="Digite sua mensagem..."></textarea><br>
    <button type="submit">Enviar</button>
  </form>

  <br>
  <?php
  $link_voltar = "index.php";
  if ($usuario_tipo === 'cliente') {
      $link_voltar = "painel_cliente.php";
  } elseif ($usuario_tipo === 'mecanico') {
      $link_voltar = "painel_mecanica.php";
  } elseif ($usuario_tipo === 'admin') {
      $link_voltar = "painel_admin.php";
  }
  ?>
  <a href="<?php echo $link_voltar; ?>" class="btn">Voltar ao painel</a>
</div>
</body>
</html>
