<?php
ini_set ( 'display_errors' , 1); 
error_reporting (E_ALL); 

require_once 'config.php';

function editar(){
    include('util.php');
    $conn->query("update tbl_produto ( cod_produto = $cod_produto");

}


$cod_produto = $_GET['cod_produto'];
$sql = "select * from tbl_produto where cod_produto = $cod_produto";
$select = $conn->query($sql)->fetch();


$cod_produto = $select['cod_produto'];
$nome_produto = $select['nome_produto'];
$descricao_produto = $select['descricao_produto'];
$preco_produto = $select['preco_produto'];

$hidden=1;


echo "<body><form action='salvar.php' method='post'>

<label>nome<label><br>
<input type='text' name='nome_produto' value='$nome_produto'><br>
<label>descricao<label><br>
<input type='text' name='descricao_produto' value='$descricao_produto'><br>
<label>preco<label><br>
<input type='text' name='preco_produto' value='$preco_produto'><br>
<label>codigo<label><br>
<input type='text' name='cod_produto' value='$cod_produto'><br>
<input type='submit' value='Salvar'>
</form></body>";
   //<input type='text' name='hidden' value='$hidden'><br>
?>
