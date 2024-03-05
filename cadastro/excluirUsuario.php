<?php 
   // mostra erros do php
   ini_set ( 'display_errors' , 1); 
   error_reporting (E_ALL);

   include("util.php");
   
   // pra nao ter que em todo arquivo colocar a mesma linha de conexao
   // manda vazio e no util.php deixa fixa 
   $conn = conecta();

   // nem precisava testar pq a unica forma de chegar aqui 
   // eh no link excluir
   
   if (isset($_GET['codigoUsuario'])) {

       $linha = [ 'codigoUsuario' => $_GET['codigoUsuario'] ]; 

       $sql = " update usuarios set excluido=true 
                where cod_usuario = :codigoUsuario"; 

       // prepara!
       $delete = $conn->prepare($sql); 
       $delete->execute($linha);
   }

   // volta pra home 
   header('Location: index.php');     

?>