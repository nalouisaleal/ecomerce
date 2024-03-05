<?php
  // mostra erros do php
  ini_set ( 'display_errors' , 1); 
  error_reporting (E_ALL);   

  // inicia a sessao
  session_start(); 
  $_SESSION['sessaoLogin']=false; 
  $_SESSION['sessaoAdmin']=false; 

  header('Location: ../index.html');
?>