<?php

 // Visualizar todos os erros
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Incluir util.php
include("util.php");

// Capturar session_id (para garantir o acesso concorrente)
session_start();
$session_id = session_id();


try {
  $conn = new PDO('pgsql:host=pgsql.projetoscti.com.br; dbname=projetoscti22; user=projetoscti22; password=721175');
} catch (PDOException $e) {
  echo "Erro de Conex�o: " . $e->getMessage() . "\n";
  exit;
}
// Recupere o c digo do usu rio da URL
if (isset($_GET['cod_usuario'])) {
  $codigoUsuario = $_GET['cod_usuario'];
} else{
  $codigoUsuario=0;
  ExecutaSQL($conn,
          "DELETE FROM tbl_carrinho ");
}

                          
   
 // existe alguma compra associada ao session_id ??
 $existe = intval ( ValorSQL($conn," select count(*) from tbl_compra 
                                     inner join tbl_tmpcompra
                                     on tbl_compra.cod_compra = tbl_tmpcompra.cod_compra  
                                     where tbl_tmpcompra.sessao_tmpcompra = '$session_id' ") ) == 1;
 // se nao existe
 if (!$existe) {   

    $dataHoje = date('Y/m/d');
 
    $statusCompra = 'Em Andamento';

    // cria um registro de tbl_compra com o usuario nulo
    ExecutaSQL($conn," insert into tbl_compra (data_compra, status_compra) 
                       values ('$dataHoje','$statusCompra') ");

    // recupera o codigo usado no auto-incremento
    $codigoCompra = ValorSQL($conn,"select max(cod_compra) from tbl_compra");

    // insere o tbl_tmpcompra
    ExecutaSQL($conn," insert into tbl_tmpcompra (cod_compra, sessao_tmpcompra) 
                       values ($codigoCompra,'$session_id') ");  
 } else{

    // como o teste mostrou que ja existe um registro de compra
    // retorna esse codigo de compra
    $codigoCompra = intval ( ValorSQL($conn," select tbl_compra.cod_compra from tbl_compra
                                              inner join tbl_tmpcompra on tbl_compra.cod_compra = 
                                              tbl_tmpcompra.cod_compra 
                                              where tbl_tmpcompra.sessao_tmpcompra = '$session_id' "));
    // obtem o status dessa compra, se criou agora, entao eh 'pendente'
     $statusCompra = ValorSQL($conn, " select status_compra from tbl_compra 
     where cod_compra = $codigoCompra ");


 }

 // se o carrinho foi chamado por COMPRAR, EXCLUIR ou FECHAR
 if ($_GET) {
  $operacao = $_GET['operacao'];
  $codigoProduto = isset($_GET['codigoProduto']) ? $_GET['codigoProduto'] : null; // Adicione verifica��o
 

  if ($operacao == 'incluir') {
      // Obtenha a quantidade atual desse produto no carrinho
      $quantidade = intval(ValorSQL($conn, "SELECT qtd_carrinho 
                                            FROM tbl_carrinho 
                                            WHERE cod_produto = $codigoProduto AND cod_compra = $codigoCompra"));
      
      if ($quantidade == 0) {
          ExecutaSQL($conn,
              "INSERT INTO tbl_carrinho (cod_produto, cod_compra, qtd_carrinho) 
              VALUES ($codigoProduto, $codigoCompra, 1)");
      } else {
          ExecutaSQL($conn,
              "UPDATE tbl_carrinho 
              SET qtd_carrinho = qtd_carrinho + 1 
              WHERE cod_produto = $codigoProduto AND cod_compra = $codigoCompra");
      }
  } else if ($operacao == 'diminuir') {
      // Obtenha a quantidade atual desse produto no carrinho
      $quantidade = intval(ValorSQL($conn, "SELECT qtd_carrinho 
                                            FROM tbl_carrinho 
                                            WHERE cod_produto = $codigoProduto AND cod_compra = $codigoCompra"));
      
      if ($quantidade == 1) {
          ExecutaSQL($conn,
              "DELETE FROM tbl_carrinho 
              WHERE cod_produto = $codigoProduto AND cod_compra = $codigoCompra");
      } else {
          ExecutaSQL($conn,
              "UPDATE tbl_carrinho 
              SET qtd_carrinho = qtd_carrinho - 1 
              WHERE cod_produto = $codigoProduto AND cod_compra = $codigoCompra");
      }
  } else if ($operacao == 'excluir') {
      ExecutaSQL($conn,
          "DELETE FROM tbl_carrinho 
          WHERE cod_produto = $codigoProduto AND cod_compra = $codigoCompra");
  }  
 }

 
 $sql = " select tbl_produto.cod_produto, 
                 tbl_produto.descricao_produto as descprod, 
                 tbl_carrinho.qtd_carrinho, 
                 tbl_produto.preco_produto, 
                 tbl_produto.preco_produto * tbl_carrinho.qtd_carrinho as sub  
          from tbl_produto 
               inner join tbl_carrinho on 
                  tbl_produto.cod_produto = tbl_carrinho.cod_produto 
          where tbl_carrinho.cod_compra = $codigoCompra  
          order by tbl_produto.descricao_produto ";
 
 $select = $conn->query($sql);
   
 // cria table com itens no carrinho e seus subtotais
 ?>

<!DOCTYPE html>
<html lang='pt-br'>
  <head>
    <meta charset='UTF-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <title>Carrinho de Compras</title>
    <link rel='stylesheet' href='carrinho.css' />
   
    <link
      href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'
      rel='stylesheet'
    />

  </head>
  <body>
    <!--barra de menu-->
    <header class='flex'>
      <a href='../' class='logo'><img src='../imagens/fotofinal.png' alt='logo'></a>
      <ul class='navmenu flex'>
          <li>
              <form action='https://www.google.com/search' method='get' class='search-bar'> 
                  <input type='text' placeholder='search'>
                  <button type='submit'><i class='bx bx-search'></i></button>
              </form>
          </li>
      </ul>
      <ul class='navmenu flex'>
          <li><a href='../'>Home</a></li>
          <li><a href='../produto/produtos.php'>Produtos</a></li>
          <li><a href='#'>Carrinho</a></li>
          <li><a href='../devops/desenvolvedores.html'>Desenvolvedores</a></li>
      </ul>
      <div class='nav-icon flex'>
          <a href='../login/login.php'><i class='bx bx-user-circle'></i></a>
      </div>

     
     <script src="java.js"></script>

  </header>

    <!--DISPLAY TABELA DE PRODUTOS-->
    <main>
      <div class='page-title'>SEU CARRINHO</div><!--NOME DO CARRINHO-->
      <div class='content'><!--ENGLOBA TUDO!!-->
        
        <section> <!--MOSTRA OS PRODUTOS DENTRO DO CARRINHO-->
          <table> <!--TABELA DE FORMATA��O-->
            <thead> <!--CABE�ALHO DA TABELA-->
              <tr>
                <th>Produto</th>
                <th>Preco</th>
                <th>Quantidade</th>
                <th>Total</th>
                <!--<th>-</th>-->
              </tr>
            </thead>
 <?php
    $total = 0;
    if ($select->rowCount() > 0) {
                 while ( $linha = $select->fetch() ) {
      
                  $codigoProduto = $linha['cod_produto']; 
                  $descProd      = $linha['descprod'];
                  $quant         = $linha['qtd_carrinho'];
                  $vunit         = $linha['preco_produto'];
                  $sub           = $linha['sub'];
                  echo"<tbody> <!--PRODUTO1-->
                    <tr>
                    <td> <!--DISPLAY DA IMAGEM E DESCRI��O DO PRODUTO-->
                        <div class='product'> 
                        <img src='../imagens/f1.jpeg' alt='' width='100' height='120' />
                        <div class='info'>
                            <div class='name'>Polaroids</div>
                            <div class='category'>".$descProd."</div>
                        </div>
                        </div>
                    </td>

                    <td>R$ ".$vunit."</td> <!--VALOR-->
                    
                    <td> <!--ADICIONAR OU REMOVER PRODUTOS-->
                        <div class='qty'>
                        <a href='carrinho.php?operacao=diminuir&codigoProduto=$codigoProduto&cod_usuario=".$codigoUsuario."'><button><i class='bx bx-minus'></i></button></a>
                        <span>".$quant."</span>
                        <a href='carrinho.php?operacao=incluir&codigoProduto=$codigoProduto&cod_usuario=".$codigoUsuario."'><button><i class='bx bx-plus'></i></button></a>
                        </div>
                    </td>

                    <td>R$ ".$sub."</td> <!--SOMA TOTAL (PRODUTO*QUANTIDADE)-->

                    <td> <!--BOT�O DE REMOVER PRODUTO DO CARRINHO-->
                    <a href='carrinho.php?operacao=excluir&codigoProduto=$codigoProduto&cod_usuario=".$codigoUsuario."'><button class='remove'><i class='bx bx-x'></i></button></a>
                    </td>

                    </tr>
                </tbody>";
                $total += $quant * $vunit;
              }
          }

if($_GET){
  $codigoUsuario = isset($_GET['cod_usuario']);
}

if($total!=null){
echo "</table>
</section>

<aside>
  <div class='box'> <!--ESPA�O DE FINALIZAR COMPRA-->
    
    <section>Resumo da compra</section> <!--NOME DO ESPA�O-->
   
    <div class='info'> <!--INFOS DA COMPRA-->
     <!-- <div><span>Sub-total</span><span>R$ $total</span></div>-->
      <div>Frete Gratuito</div>
     

    </div>
         <footer> <!--APRESENTA VALOR FINAL DA COMPRA-->
            <span>Total</span>
            <span>R$ $total</span>
            </footer>
  </div>

 <a href='finalizar_compra.php?operacao=finalizar&cod_usuario=".$codigoUsuario."&total=".$total."&session=".$session_id."' >finalizar compra</a><!--BOT�O FINALIZAR COMPRA-->
</aside>";
}else{
  if (isset($_GET['cod_usuario'])) {
    $codigoUsuario = $_GET['cod_usuario'];
    $redirectUrl = '../produto/produtos.php?cod_usuario=' . urlencode($codigoUsuario);
    header('Refresh: 3; URL=' . $redirectUrl); // Redireciona após 5 segundos
  } else{
    $redirectUrl = '../produto/produtos.php?cod_usuario=0' ;
    header('Refresh: 3; URL=' . $redirectUrl); // Redireciona após 5 segundos
  }
 
exit();
}
?>
 </main>
 </body>
 </html>


 
 
