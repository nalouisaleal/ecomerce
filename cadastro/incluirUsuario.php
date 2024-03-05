<?php
// Show PHP errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("util.php");

// To avoid duplicating the same connection code in every file
// Pass an empty string to the 'conecta' function, and configure it in 'util.php'
$conn = conecta("");

if (isset($_POST['senha_usuario'])) {
    $senha = $_POST['senha_usuario'];
} else {
    $senha = '1';
}

if (isset($_POST['confsenha'])) {
    $c_senha = $_POST['confsenha'];
} else {
    $c_senha = '2';
}

if ($senha == $c_senha) {
    // Passwords match
    if (isset($_POST['sexo_usuario'])) {
        $sexoSelecionado = $_POST['sexo_usuario'];
    } else {
        $sexoSelecionado = "";
    }

    $linha = [
      'nome_usuario' => $_POST['nome_usuario'],
      'telefone_usuario' => $_POST['telefone_usuario'],
      'senha_usuario' => $_POST['senha_usuario'],
      'email_usuario' => $_POST['email_usuario'],
      'sexo_usuario' => $sexoSelecionado,
      'admin' => 'false', // Change this to 'true' or 'false' as needed
      'excluido_usuario' => 'false' // Change this to 'true' or 'false' as needed
  ];
  
  $sql = "INSERT INTO tbl_usuario (nome_usuario, telefone_usuario, senha_usuario, email_usuario, sexo_usuario, admin, excluido_usuario)  
             VALUES (:nome_usuario, :telefone_usuario, :senha_usuario, :email_usuario, :sexo_usuario, :admin, :excluido_usuario)";
  
  // Prepare the statement and execute it
  $update = $conn->prepare($sql);
  $update->execute($linha);
  header('Location: ../produto/produtos.php');
} else {
    header('Location: formUsuario.php?erro=1');
}
