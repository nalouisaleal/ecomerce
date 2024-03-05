<?php
ini_set ( 'display_errors' , 1); 
error_reporting (E_ALL); 



require_once 'config.php';
$cod_produto =$_POST['cod_produto'];
$nome_produto = $_POST['nome_produto'];
$descricao_produto = $_POST['descricao_produto'];
$preco_produto = $_POST['preco_produto'];

$linha = ['cod' => $cod_produto,
 'nome'=>$nome_produto,
  'descricao' => $descricao_produto,
    'preco' => $preco_produto];
 
 
//$update = $conn->prepare('update tbl_produto (cod_produto, nome_produto, descricao_produto, preco_produto)
    //VALUES :cod, :nome, :descricao, :preco');

$sql = "update tbl_produto set nome_produto = :nome, descricao_produto = :descricao, preco_produto = :preco  
        where cod_produto = :cod";

$update = $conn->prepare($sql);
$update->execute($linha);

echo("dados atualizados!");
unset($update);
echo "<a href=produtos.php>Retornar aos Produtos</a>";




?>