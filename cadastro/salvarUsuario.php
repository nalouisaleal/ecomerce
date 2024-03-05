<?php 
   // mostra erros do php
   ini_set ( 'display_errors' , 1); 
   error_reporting (E_ALL);

   include("util.php");

   // pra nao ter que em todo arquivo colocar a mesma linha de conexao
   // manda vazio e no util.php deixa fixa    
   $conn = conecta();

   $linha = [ 'cod_usuario' => $_POST['cod_usuario'],
              'nome_usuario'        => $_POST['nome_usuario'],
              'telefone_usuario'     => $_POST['telefone_usuario'], 
              'senha_usuario'       => $_POST['senha_usuario'],
              'email_usuario'       => $_POST['email_usuario'],
              'admin'       => false ];

   $sql = "update tbl_usuario set 
             nome      = :nome, 
             usuario   = :usuario,   
             senha     = :senha, 
             email     = :email,   
             admin     = :admin 
           where cod_usuario = :cod_usuario "; 
   
   // prepara!
   $update = $conn->prepare($sql); 
   $update->execute($linha);

   // volta pra usuarios
   header('Location: usuarios.php');     

?>
