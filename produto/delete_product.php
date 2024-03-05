<?php
ini_set ( 'display_errors' , 1); 
error_reporting (E_ALL); 

require_once 'config.php';

$cod_produto = $_GET['cod_produto'];
echo $cod_produto;
$conn->query("delete from tbl_produto where cod_produto = $cod_produto");
unset($conn);

header('Location: index.php');
?>