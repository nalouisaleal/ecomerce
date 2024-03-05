<?php 
   // mostra erros do php
   ini_set ( 'display_errors' , 1); 
   error_reporting (E_ALL);

   include("util.php");


   // pra nao ter que em todo arquivo colocar a mesma linha de conexao
   // manda vazio e no util.php deixa fixa 
   $conn = conecta();

   session_start();
   
   // se receber o codigousuario, EDITA !
   // senao INCLUE !
   if (isset($_GET['cod_usuario'])) {
       $cod_usuario = $_GET['cod_usuario']; 
   } else {
       $cod_usuario = ""; // vai ser a chave para incluir o usuario, usado no IF
   }


   if ($cod_usuario == "") {
      // aqui se chegou um pedido de INCLUSAO

      $IncluiOuAtualiza = "incluirUsuario.php";

      $cod_usuario = "";
      $nome_usuario        = "";
      $telefone_usuario       = "";
      $email_usuario         = "";
      $admin         = false;
      $senha_usuario         = "";
      $sexo_usuario = "";

   } else {
     // aqui se chegou um pedido de ALTERACAO; ou seja, nao eh nulo

     $sql = "select * from tbl_usuario  
             where cod_usuario = $cod_suario" ;
     
     echo $sql;
     
     // faz um select basico
     $select = $conn->query($sql)->fetch();

     $IncluiOuAtualiza = "salvarUsuario.php";

     $cod_usuario = $select['cod_usuario'];
     $nome_usuario          = $select['nome_usuario'];
     $telefone_usuario       = $select['telefone_usuario'];
     $senha_usuario         = $select['senha_usuario'];
     $email_usuario         = $select['email_usuario'];
     $sexo_usuario = $select['sexo_usuario'];
     $admin         = false;
     $senha_usuario         = $select['senha_usuario'];
   }
   
   // abaixo veja que ao usar hidden vc impede q que o campo seja editado 
   // mas envia o valor como $_POST 
   $varHTML = "
   <!DOCTYPE html>
   <html long='pt-br'>
       <head>
           <meta charset = 'UTF-8'/>
           <title>Cadastro</title>
           <link rel='stylesheet' href='cadastro.css'/>
       </head>
      <body>
      <div class='container'>
      <div class='form_imagem'>
         <a href='../'> <img src='../imagens/CLICKFUN_LOGOFINAL.png' alt='Bem-vindo a Clickfun!!'></a>      </div>
  <div class='form'>
        <form action='$IncluiOuAtualiza' name='form' method='post'>
        <div class='form_header'>
                <div class='title'>
                    <h1>Cadastre-se</h1>
                </div>
                <div class='btn_login'>
                    <a href='../login/login.php'>Entrar</a>
                </div>
            </div>

            <div class='informacoes'>
                <div class='caixainfo'>
                    <label for='nomecompleto'>Nome completo: </label><br>
                    <input type='text' id='nomecompleto' name='nome_usuario' placeholder='Nome completo' value = '$nome_usuario' required>
                </div>

                <div class='caixainfo'>
                    <label for='email'>E-mail: </label><br>
                    <input type='email' id='email' name='email_usuario' placeholder='Email' value = '$email_usuario' required>
                </div>

                <div class='caixainfo'>
                    <label for='telefone'>Telefone: </label><br>
                    <input type='tel' id='telefone' name='telefone_usuario' placeholder='(xx)xxxx-xxxx'  value = '$telefone_usuario' required>
                </div>
                
                <div class='caixainfo'>
                    <label for='senha'>Senha: </label><br>
                    <input type='password' id='senha' name='senha_usuario' placeholder='senha'  value = '$senha_usuario'>
                </div>
                <div class='caixainfo'>
                    <label for='senha'>Confirme sua senha: </label><br>
                    <input type='password' id='confsenha' name='confsenha' placeholder='confirme sua senha'>
                </div>
 		<div class = 'caixainfo'>
                    <input type='hidden'  nome='cod_usuario' value = '$cod_usuario'>
                </div>

            </div>

            <div class='secso'>
                <div class='titulosexo'>
                    <h5>Sexo:</h5>
                </div>
                <div class='grupos'>
                    <div class='grupos1'>
                        <input type='radio' id='feminino' name='sexo_usuario' value='feminino'>
                        <label for='feminino'>Feminino</label>
                    </div>
                    <div class='grupos1'>
                        <input type='radio' id='masculino' name='sexo_usuario' value='masculino'>
                        <label for='masculino'>Masculino</label>
                    </div>
                    <div class='grupos1'>
                        <input type='radio' id='outro' name='sexo_usuario' value='outro'>
                        <label for='outro'>Outro</label>
                    </div>
                    <div class='grupos1'>
                        <input type='radio' id='prefironaodizer' name='sexo_usuario' value='prefironaodizer'>
                        <label for='prefironaodizer'>Prefiro não dizer</label>
                    </div>
                </div>
            </div>
            ";
          
   // se eh o admin que ta logado, permite editar o campo admin 
   if ( isset($_SESSION['sessaoAdmin']) ) {       
       if ($_SESSION['sessaoAdmin']) {
           $varHTML = $varHTML."<br>Admin(true/false)<br><input type='text' name='admin' value = '$admin'>";
       }     
   } else {
       // apenas passa o input mas nao edita (est  escondido)
       $varHTML = $varHTML."<input type='hidden' name='admin' value = '$admin'>";
   }

   function erro(){
    echo"<script>
    alert('Senha incorreta. Redigite.');
    </script>
    ";
   }
   if(isset($_GET['erro'])){
    $erro = $_GET['erro'];
    if($erro == '1'){
      erro();
    }
   else{
    $erro = '';
   }
  }

   $varHTML = $varHTML.
    "      
    <div class='continuabtn'>
        <button value='Cadastre-se'>Cadastre-se</button>
    </div> 
        </form>";
   /*<script>
       // Fun��o para validar a confirma��o de senha antes do envio do formul�rio
       function validarSenha() {
           var senha_usuario = document.getElementById('senha_usuario').value;
           var confsenha = document.getElementById('confsenha').value;

           if (senha_usuario !== confsenha) {
               alert('As senhas n�o coincidem. Por favor, confirme sua senha corretamente.');
               return false; // Impede o envio do formul�rio
           }
       }
       // Adicione o evento onsubmit ao formul�rio
       document.querySelector('form').onsubmit = validarSenha;
   </script>
";   */
echo "</body></html>";
       /* 
        echo "
        <script src = 'confirmarsenha.js'></script>     
    ";*/
        echo"</body> </html> ";
     
    echo $varHTML;
   
?>
