<?php
  // mostra erros do php
  ini_set ( 'display_errors' , 1); 
  error_reporting (E_ALL);   

  // inicia a sessao
  session_start(); 
  $_SESSION['sessaoConectado']=false; 
  $_SESSION['admin']=false; 

  header('Location: index.php');
?>