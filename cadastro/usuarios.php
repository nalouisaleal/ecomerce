<?php 

   // mostra erros do php
   ini_set ( 'display_errors' , 1); 
   error_reporting (E_ALL);   
   
   include("util.php");

   /// coloca esse css fora e abre do jeito certo ok?

   echo "<style>
   #tabela {
     font-family: Arial, Helvetica, sans-serif;
     border-collapse: collapse;
     width: 50%;
   }
   
   #tabela td, #tabela th {
     border: 1px solid #ddd;
     padding: 8px;
   }
   
   #tabela tr:nth-child(even){background-color: #f2f2f2;}
   
   #tabela tr:hover {background-color: #ddd;}
   
   #tabela th {
     padding-top: 12px;
     padding-bottom: 12px;
     text-align: left;
     background-color: #04AA6D;
     color: white;
   }
   </style>
   </head>";

   // faz conexao 
   $conn = conecta();

   if (isset($_POST['varPesquisa'])) 
   {
     $varPesquisa = $_POST['varPesquisa'];
   } else {
     $varPesquisa = "";
   }
  
   echo "Variavel pesquisa: $varPesquisa";

   $sql = " select * from usuarios 
            where nome like '%$varPesquisa%' and excluido<>'s'  
            order by nome ";
   
   // faz um select basico
   $select = $conn->query($sql);
   
   echo "<form action='usuarios.php' method='post'>
           <input type='text' name='varPesquisa'>
           <input type='submit' value='Procurar'>   
         </form>";

   // enquanto houver registro leia em $linha
   echo "<table border='1' id='tabela'>
  
         <th>Cod</th>
         <th>Nome</th>
         <th>Usuario</th>
         <th>Email</th>
         <th>Admin?</th>
         <th></th>
         <th></th>";

   // fetch significa carregar proxima linha
   // qdo nao tiver mais nenhuma retorna FALSE pro while
   while ( $linha = $select->fetch() )  
   {
     // imprime as posicoes de $linha que sao os campos carregados  
     $codigoUsuario = $linha['cod_usuario'];
     $nome          = $linha['nome'];
     $usuario       = $linha['usuario'];
     $email         = $linha['email'];
     $admin         = $linha['sessaoAdmin'];

     echo "<tr> 
            <td>$codigoUsuario</td>
            <td>$nome</td>
            <td>$usuario</td>
            <td>$email</td>
            <td>$admin</td>  
            <td><a href='formUsuario.php?codigoUsuario=$codigoUsuario'><img src='imagens/alterar.png' width=30></a></td>
            <td><a href='excluirUsuario.php?codigoUsuario=$codigoUsuario'><img src='imagens/excluir.png' width=30></a></td>                                            
           </tr>";       
   }

   echo "</table>
         <br>
         <a href='formUsuario.php'>
           <img src='imagens/adicionar.png' width=30>
         </a>

         <br>
         <a href='index.php'>Home</a>"; 
?>
