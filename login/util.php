<?php 
 
  //////  funcao de conexao
  //////  14-8-2023
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
    if(isset($_SESSION['sessaoAdmin']) && $_SESSION['sessaoAdmin'] === true){
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
   $paramAdmin=$linha['sessaoAdmin'] == true;
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

  function EnviaEmail ($pEmailDestino, $pAssunto, $pHtml, 
$pUsuario = "clickfun@projetoscti.com.br", $pSenha = "Click#Fun2023", $pSMTP = "smtp.projetoscti.com.br" )   
{
  ini_set('default_charset','UTF-8');
 require "PHPMailer/PHPMailerAutoload.php";
    
 try {

   //cria instancia de phpmailer
   $mail = new PHPMailer(); 
   $mail->IsSMTP();  

   // servidor smtp
   $mail->Host = $pSMTP;
   $mail->SMTPAuth = true;      // requer autenticacao com o servidor                         
   $mail->SMTPSecure = 'tls';                            
    
   $mail-> SMTPOptions = array (
     'ssl' => array (
     'verificar_peer' => false,
     'verify_peer_name' => false,
     'allow_self_signed' => true ) );
    
   $mail->Port = 587;      
    
   $mail->Username = $pUsuario; 
   $mail->Password = $pSenha; 
   $mail->From = $pUsuario; 
   $mail->FromName = " Suporte ClickFun LTDA"; 

   $mail->AddAddress($pEmailDestino, "Usuario"); 
   $mail->IsHTML(true); 
   $mail->Subject = $pAssunto; 
   $mail->Body = $pHtml;
   $enviado = $mail->Send(); 
   return $enviado;     
    
 } catch (phpmailerException $e) {
   echo $e->errorMessage(); // erros do phpmailer
 } catch (Exception $e) {
   echo $e->getMessage(); // erros  gerais
 }      
}

function ExecutaSQL( $paramConn, $paramSQL ) 
  {
    // exec eh usado para update, delete, insert
    // eh um metodo da conexao
    // retorna o nro de linhas afetadas
    $linhas = $paramConn->exec($paramSQL);
  
    if ($linhas > 0) { 
        return TRUE; 
    } else { 
        return FALSE; 
    }  
  }

  /*
  * Fun��o para executasql frases sql
  * marcelo c peres - 2023
  */

  // ValorSQL 
  // retorna o valor de um campo de um select
  // Set 2023 - Marcelo C Peres 
  function ValorSQL( $pConn, $pSQL ) 
  {
   $linhas = $pConn->query($pSQL)->fetch();
  
   if ($linhas > 0) { 
       return $linhas[0]; 
   } else { 
       return "0"; 
   }  
  }


  /**
  * Fun��o para gerar senhas aleat�rias
  *
  * @author    Thiago Belem <contato@thiagobelem.net>
  *
  * @param integer $tamanho Tamanho da senha a ser gerada
  * @param boolean $maiusculas Se ter� letras mai�sculas
  * @param boolean $numeros Se ter� n�meros
  * @param boolean $simbolos Se ter� s�mbolos
  *
  * @return string A senha gerada
  */

  function GeraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
  {
    //$lmin = 'abcdefghijklmnopqrstuvwxyz';
    $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '1234567890';
    $simb = '!@#$%*-';
    $retorno = '';
    $caracteres = '';

    //$caracteres .= $lmin;
    if ($maiusculas) $caracteres .= $lmai;
    if ($numeros)    $caracteres .= $num;
    if ($simbolos)   $caracteres .= $simb;

    $len = strlen($caracteres);
    
    for ($n = 1; $n <= $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand-1];
    }
    
    return $retorno;
  }

?>