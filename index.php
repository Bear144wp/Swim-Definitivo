<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
  <div class="container">
  <h2>Login</h2>
  <form action="login.php" method="POST">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="senha" placeholder="Senha" required><br>
    <button type="submit">Entrar</button>
    <p style="text-align: center; margin-top: 15px;">
      Ainda não tem uma conta?
      <a href="cadastro.html">Cadastre-se aqui</a>
    </p>
    
  </form>
</div>
</body>
</html>
