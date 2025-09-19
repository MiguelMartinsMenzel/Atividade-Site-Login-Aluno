<?php
$mensagem = '';
$mensagem_tipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $host = 'localhost';
    $dbname = 'etimpwiiAluno';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }

    $rm = filter_input(INPUT_POST, 'rm', FILTER_SANITIZE_NUMBER_INT);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_SPECIAL_CHARS);
    $senha = $_POST['senha'] ?? '';

    if (!$rm || !$nome || !$email || !$cpf || !$senha) {
        $mensagem = "Por favor, preencha todos os campos corretamente.";
        $mensagem_tipo = "error";
    } elseif (!preg_match('/^\d{11}$/', preg_replace('/\D/', '', $cpf))) {
        $mensagem = "CPF inválido. Informe os 11 dígitos numéricos.";
        $mensagem_tipo = "error";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO aluno (rm, nome, email, cpf, senha) VALUES (:rm, :nome, :email, :cpf, :senha)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                ':rm' => $rm,
                ':nome' => $nome,
                ':email' => $email,
                ':cpf' => $cpf,
                ':senha' => $senha_hash
            ]);
            $mensagem = "Aluno cadastrado com sucesso!";
            $mensagem_tipo = "success";

            $_POST = [];
        } catch (PDOException $e) {
            $mensagem = "Erro ao cadastrar aluno: " . $e->getMessage();
            $mensagem_tipo = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cadastro de Aluno</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .form-container {
      background-color: #fff;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }
    .form-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }
    label {
      font-weight: bold;
      color: #555;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }
    button {
      width: 100%;
      padding: 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #45a049;
    }
    .message {
      text-align: center;
      font-weight: bold;
      margin-bottom: 15px;
      padding: 10px;
      border-radius: 5px;
    }
    .success {
      color: #155724;
      background-color: #d4edda;
      border: 1px solid #c3e6cb;
    }
    .error {
      color: #721c24;
      background-color: #f8d7da;
      border: 1px solid #f5c6cb;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Cadastro de Aluno</h2>

    <?php if (!empty($mensagem)) : ?>
      <p class="message <?php echo $mensagem_tipo === 'success' ? 'success' : 'error'; ?>">
        <?php echo htmlspecialchars($mensagem); ?>
      </p>
    <?php endif; ?>

    <form action="" method="post">
      <label for="rm">RM:</label>
      <input type="number" id="rm" name="rm" value="<?php echo htmlspecialchars($_POST['rm'] ?? ''); ?>" required />

      <label for="nome">Nome Completo:</label>
      <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>" required />

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required />

      <label for="cpf">CPF:</label>
      <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($_POST['cpf'] ?? ''); ?>" required maxlength="11" />

      <label for="senha">Senha:</label>
      <input type="password" id="senha" name="senha" required />

      <button type="submit">Cadastrar</button>
    </form>
  </div>
</body>
</html>
