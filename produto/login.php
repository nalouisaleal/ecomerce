<?php
 /*  // mostra erros do php
  ini_set ( 'display_errors' , 1); 
  error_reporting (E_ALL);   

  // se nao estiver conectado vai pedir o login
  if (isset($_SESSION['sessaoConectado'])) {
      $sessaoConectado = $_SESSION['sessaoConectado'];
  } else { 
    $sessaoConectado = false; 
  }

  // se sessao nao conectada ...
  if (!$sessaoConectado) { 
     
     $loginCookie = '';

     // recupera o valor do cookie com o usuario    
     if (isset($_COOKIE['loginCookie'])) {
        $loginCookie = $_COOKIE['loginCookie']; 
     }*/
     ini_set ( 'display_errors' , 1); 
     error_reporting (E_ALL);   
       session_start();
     include("util.php");
       try {
         if (isset($_SESSION['sessaoConectado']) && $_SESSION['sessaoConectado'] === true) {
           // Se o usuário já está logado, redirecione para a página de perfil
           header('Location: index.php');
           exit;
         }
     
         if ($_SERVER["REQUEST_METHOD"] == "POST") {
           $email = $_POST['email'];
           $senha = $_POST['senha'];
     
           $conn = conecta();
     
           if (!$conn) {
             throw new Exception("Falha na conexão com o banco de dados.");
           }
     
           $sql = "SELECT * FROM tbl_usuario WHERE email_usuario = :email AND senha_usuario = :senha";
           $stmt = $conn->prepare($sql);
           $stmt->bindParam(':email', $email);
           $stmt->bindParam(':senha', $senha);
           $stmt->execute();
     
           if ($stmt->rowCount() == 1) {
             // Dados do usuário encontrados
             $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            if($userData['admin']==true){
                $_SESSION['admin']=true;
            }
            else{

              $_SESSION['admin']=false;

            }
             // Armazene os dados do usuário na sessão
             $_SESSION['sessaoConectado'] = true;
             $_SESSION['codUsuario'] = $userData['cod_usuario'];
             $_SESSION['nomeUsuario'] = $userData['nome_usuario'];
             $_SESSION['emailUsuario'] = $userData['email_usuario'];
             $_SESSION['telefoneUsuario'] = $userData['telefone_usuario'];
            
             if($_SESSION['admin']){
              //oi();
              header('Location: index.php');

             }else{
              header('Location: index.php');
             }
             exit;
           } else {
             throw new Exception("Credenciais inválidas. Por favor, tente novamente.");
           }
         }
       } catch (Exception $e) {
         echo "Erro: " . $e->getMessage();
       }
     
       echo "
      <html>
      <head>
      <title>ClickFun</title>
      <link rel='stylesheet' href='login.css'/>
      </head>
      <body>
          <form name='formlogin' method='post' action=''>
          <div class='login'>
        <div class='login_esq'>
            <img src='CLICKFUN_LOGOFINAL.png' alt='logo_clickfun' class='imagem'>
            <h1><center>Faça login<br>Tire essa foto com a gente!</center></h1>
            <button class='btn_cadastro'>Cadastre-se</button>
        </div>
        <div class='login_dir'>
            <div class='espaco_login'>
                <h1>Login</h1>
                <div class='textfield'>
                    <label for='usuario'>Usuário</label>
                    <input type='text' name='email' placeholder='Usuário'>
                </div>
                <div class='textfield'>
                    <label for='senha'>Senha</label>
                    <input type='password' name='senha' placeholder='Senha'>
                </div>
                <button class='btn_login'>LOGIN</button>
            </div>
            
        </div>
    </div>
          </form>
      </body>
      </html>";
    
?>

