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


function Modal(){
  echo"<!DOCTYPE html>
  <html lang='pt-br'>
  <head>
      <style>
      .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 200px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
      }
      
      /* Modal Content */
      .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
      }
      
      /* The Close Button */
      .close {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
      }
      
      .close:hover,
      .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
      }
      </style>
  </head>
  <body>
  
  <!-- Trigger/Open The Modal -->
  <button id='myBtn'>finalizar compra</button>
  
  <!-- The Modal -->
  <div id='myModal' class='modal'>
  
    <!-- Modal content -->
    <div class='modal-content'>
      <span class='close'>&times;</span>
      <p>Compra realizada com sucesso! <br>Obrigado por escolher a ClickFun. Estamos animados por tê-lo(a) como parte da nossa família de clientes. 
          Se gostou do nosso produto,considere compartilhar sua experiência com amigos e familiares.
           Seu apoio é inestimável. Obrigado! </p>
    </div>
  
  </div>
  
  <script>
  // Get the modal
  var modal = document.getElementById('myModal');
  
  // Get the button that opens the modal
  var btn = document.getElementById('myBtn');
  
  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName('close')[0];
  
  // When the user clicks the button, open the modal 
  btn.onclick = function() {
    modal.style.display = 'block';
  }
  
  // When the user clicks on <span> (x), close the modal
  span.onclick = function() {
    modal.style.display = 'none';
  }
  
  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = 'none';
    }
  }
  </script>
  </body>
  </html>";
}
?>
