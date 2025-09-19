<?php
// Configuração do banco de dados
$servername = "localhost";
$username = "root";
$password = ""; // ou sua senha, se tiver
$database = "etimpwiiAluno";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Pegar dados do formulário
$rm = $_POST['rm'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];

// Inserir no banco
$sql = "INSERT INTO aluno (rm, nome, email, senha, cpf) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issss", $rm, $nome, $email, $senha, $cpf);
$stmt->execute();

// Verifica se inseriu com sucesso
$sucesso = $stmt->affected_rows > 0;

// Fecha conexão
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Confirmação de Cadastro</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #e0f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .container {
      background-color: #ffffff;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      text-align: center;
      max-width: 400px;
    }

    h2 {
      color: #4CAF50;
    }

    p {
      font-size: 18px;
      color: #333;
    }

    .btn {
      margin-top: 20px;
      display: inline-block;
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
    }

    .btn:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>

  <div class="container">
    <?php if ($sucesso): ?>
      <h2>Cadastro Realizado!</h2>
      <p>O aluno <strong><?= htmlspecialchars($nome) ?></strong> foi cadastrado com sucesso.</p>
    <?php else: ?>
      <h2>Erro ao Cadastrar</h2>
      <p>Houve um problema ao salvar os dados. Tente novamente.</p>
    <?php endif; ?>
    <a href="index.html" class="btn">Voltar para o início</a>
  </div>

</body>
</html>
