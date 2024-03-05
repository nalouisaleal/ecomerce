<?php
ini_set ( 'display_errors' , 1); 
error_reporting (E_ALL); 

require_once 'config.php';


    $nome_produto = $_POST['nome_produto'];
    $descricao_produto = $_POST['descricao_produto'];
    $preco_produto = $_POST['preco_produto'];

    $sql = "INSERT INTO tbl_produto (nome_produto,descricao_produto,preco_produto) VALUES ('$nome_produto', '$descricao_produto', $preco_produto)";

   $conn->query($sql);
    header("Location: index.php");


?>