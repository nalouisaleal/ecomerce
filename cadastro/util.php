<?php
function conecta ($params = "")  // igual a nada pra indicar q aceita vazio !! 
{
  if ($params == "") {
      $params="pgsql:host=pgsql.projetoscti.com.br; dbname=projetoscti22; 
               user=projetoscti22; password=721175";
  }

  $varConn = new PDO($params);

  if (!$varConn) {
      echo "Nao foi possivel conectar";
  } else { return $varConn; }
}
/////////////////////////
function oi(){
  if(isset($_SESSION['admin']) && $_SESSION['admin'] === true){
  echo "bom dia adm";
  }
}
//////  funcao de login
function funcaoLogin ($paramLogin, $paramSenha, &$paramAdmin)  
{
 $conn=conecta();
 $varSQL="select senha_usuario,admin from tbl_usuario where email_usuario= '$paramLogin'";
 $linha=$conn->query($varSQL)->fetch();
 $senha=$linha['senha_usuario'];
 $paramAdmin=$linha['admin'] == true;
 $paramAdmin = ($paramLogin == 'clickfun2023@gmail.com' and 
                $paramSenha == '12345');
 
 return $senha=$paramSenha;  // todos sao validos!

}

//////  funcao de definir cookie
//////  11-9-2023
function DefineCookie($paramNome, $paramValor, $paramMinutos) 
{
 echo "Cookie: $paramNome Valor: $paramValor";  
 setcookie($paramNome, $paramValor, time() + $paramMinutos * 60); 
}
?>
