<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=etimpwiiAluno;charset=utf8", "root", "");
    echo "Conexão com banco realizada com sucesso!";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>