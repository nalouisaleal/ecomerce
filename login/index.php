
<?php 
   // mostra erros do php
   ini_set ( 'display_errors' , 1); 
   error_reporting (E_ALL);   

   // inicia a sessao
   session_start(); 
   
   include("util.php");

   echo "<html>";   
   
   // seu css
   echo "<head>
      <title>Home</title>
         <link rel='stylesheet' type='text/css' 
          href='home.css'>
         <script></script> 
         </head>";

   echo "<body>";

   include ('cabecalho.php');
   //include ('login.php');

   // faz conexao 
   $conn = conecta(); 

   //aqui vc coloca td que tera na homepage 

   echo "<div class='navbar'>
   <div>
     <nav>
         <ul>
         <li><img src='logo.png'></li>
         <li>
          <form action='https://www.google.com/search' method='get' class='search-bar'> 
          <input type='text' placeholder='search'>
          <button type='submit'><img src='lupa.png' >
          </form>
          </li> 
         <li><a href='/anadourado/E-Commerce/produtos/produtos.php'  class='colortext container flex'>Produtos</a></li>
         <li><a href=# class='colortext container flex'>Carrinho</a></li>
         <li><a href=# class='colortext container flex'>Desenvolvedores</a></li>
         <li><a href='login.php' class='colortext container flex'>Login</a></li> 
         </ul>
     </nav>
   </div>
   <div class='box'>
    </div>
    <div class='footer'>
    </div>
 </div>";
   

  echo "</body></html>";
?>


