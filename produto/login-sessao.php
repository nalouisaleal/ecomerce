<?php
  
  //2º
  // mostra erros do php 

  ini_set ( 'display_errors' , 1); 
  error_reporting (E_ALL);  

  include("util.php");
  
  // inicia a sessao
  session_start();   

  // login que veio do form
  $login = $_POST['email_usuario'];
  $senha = $_POST['senha_usuario'];
  $eh_admin = false;

  if ($login<>'') {
      DefineCookie('loginCookie', $login, 60); 
      $_SESSION['sessaoConectado'] = funcaoLogin($login, $senha, $eh_admin); 
      $_SESSION['admin']     = $eh_admin;   
  }
     
  header('Location: index.php');
?> 