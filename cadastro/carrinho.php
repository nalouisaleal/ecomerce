<?php
// mostra erros do php
ini_set ( 'display_errors' , 1); 
error_reporting (E_ALL);  

include ('util.php'); 
session_start();
include('cabecalho.php');

$session_id = session_id();
$conn = conecta();

if(isset ($_SESSION['sessaoLogin'])){
  $login = $_SESSION['sessaoLogin'];
  $codigoUsuario = ValorSQL($conn, "select cod_usuario from tbl_usuario
                                    where usuario = '$login'");

}
 // existe alguma compra associada ao session_id ??
$existe = intval (ValorSQL($conn," select count(*) from tbl_compra 
                                     inner join tbl_tmpcompra
                                     on tbl_compra.cod_compra = tbl_tmpcompra.cod_compra  
                                     where tbl_tmpcompra.sessao_tmpcompra = '$session_id' ") == 1);

if(!$existe){
  $dataHoje = date('Y/m/d');

  $statusCompra = 'Pendente';

      // cria um registro de tbl_compra com o usuario nulo
  ExecutaSQL($conn,"insert into tbl_compra (data_compra, status_compra)
                    values ('$dataHoje', '$statusCompra')");
  //recupera o codigo usado no auto-incremento
  $codigoCompra = ValorSQL($conn, "select max(cod_compra) from tbl_compra");

  ExecutaSQL($conn, "insert into tbl_tmpcompra(cod_compra, sessao_tmpcompra)
                      values ('$codigoCompra','$session_id')");
}else{
  // como o teste mostrou que ja existe um registro de compra
    // retorna esse codigo de compra

    $codigoCompra = intval ( ValorSQL($conn," select tbl_compra.cod_compra from tbl_compra
                                              inner join tbl_tmpcompra on tbl_compra.cod_compra = 
                                              tbl_tmpcompra.cod_compra 
                                              where tbl_tmpcompra.sessao_tmpcompra = '$session_id' "));

    // obtem o status dessa compra, se criou agora, entao eh 'pendente'
    $statusCompra = ValorSQL($conn, "select status_compra from tbl_compra 
    where cod_compra = $codigoCompra ");
}
 ////////////// se estiver logado atualiza e 'liga' a compra com o 
 ////////////// usuario

 if (isset($codigoUsuario)) {
  //ExecutaSQL($conn,"update tbl_compra set cod_usuario = $codigoUsuario where cod_usuario is null and cod_compra = $codigoCompra"); 

  $update = $conn->prepare("update tbl_compra set cod_usuario = :codigoUsuario where cod_usuario is null and cod_compra = :codigoCompra");
  $update->bindParam(":codigoUsuario", $codigoUsuario, PDO::PARAM_INT);
  $update->bindParam(":codigoCompara", $codigoCompra, PDO::PARAM_INT);
  $update->execute();
}
//linha 78 continuar
//declarar antes para ser visivel
$operacao = null; $codigoProduto = null;

if ($_GET) {
  //chegaram?
  if(isset($_GET['operacao']) && isset($_GET['codigoProduto'])) {
    $operacao = $_GET['operacao'];
    $codigoProduto = $_GET['codigoProduto'];
  }

  //obtém a qtd atual desse produto no carrinho

  $quantidade = (ValorSQL($conn, "select qtd_carrinho from tbl_carrinho
                                        where cod_produto = $codigoProduto 
                                        and cod_compra = $codigoCompra"));

  if($operacao == 'incluir') {
    if($quantidade == 0) {
      ExecutaSQL($conn, "insert into tbl_carrinho (cod_produto, cod_compra, qtd_carrinho)
                          values ($codigoProduto, $codigoCompra, 1) ");              
    } else{
      ExecutaSQL($conn, "update tbl_carrinho set qtd_carrinho = qtd_carrinho +1
                          where cod_produto = $codigoProduto
                          and cod_compra = $codigoCompra");
  }
  } else
    if($operacao == 'excluir'){
      if($quantidade == 1){
        ExecutaSQL($conn, "delete from tbl_carrinho where
                          cod_produto = $codigoProduto and
                          cod_compra = $codigoCompra");
      }else{
        ExecutaSQL($conn,"update tbl_carrinho set qtd_carrinho = qtd_carrinho-1
                          where cod_produto = $codigoProduto
                          and cod_compra = $codigoCompra");
      }
    }else
    if($operacao == 'fechar'){
      // fazer dps do viva cti
    }
}





if($sessaoConectado){
  echo "<p align='right'><a href='../login/logout.php'>Sair</a></p>";
  echo "<p align='right'>Usuário Comum <br>";   

}
else{

}
//if ( $_SESSION['sessaoAdmin'] ) {




/*

if(!isset($_SESSION['sessao']))
{
    $_SESSION['sessao']=array();
}
  */
/*
if(isset($_GET['add']) && $_GET['add'] == "carrinho")//se o user realmente colocou no carrinho
{
    // adiciona ao carrinho
    $cod_produto=$_GET['id'];
    if(!isset($_SESSION['sessao'][$cod_produto]))
    {
        $_SESSION['sessao'][$cod_produto]=1;//primeirra vez q o produto está sendo adicionado
    }else{
        $_SESSION['sessao'][$cod_produto]+=1;
    }
}*/
?>
<!DOCTYPE html>
<html lang='pt-br'>
  <head>
    <meta charset='UTF-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <title>Carrinho de Compras</title>
    <link rel='stylesheet' href='styles.css' />
    <link
      href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'
      rel='stylesheet'
    />
  </head>
  <body>
    <!--barra de menu-->
    <header class='flex'>
      <a href='../home' class='logo'><img src='logo.png' alt='logo'></a>
      <ul class='navmenu flex'>
          <li>
              <form action='https://www.google.com/search' method='get' class='search-bar'> 
                  <input type='text' placeholder='search'>
                  <button type='submit'><i class='bx bx-search'></i></button>
              </form>
          </li>
      </ul>
      <ul class='navmenu flex'>
          <li><a href='../home'>Home</a></li>
          <li><a href='../produto/produtos.php'>Produtos</a></li>
          <li><a href='../carrinho/carrinho.php'>Carrinho</a></li>
          <li><a href='#'>Desenvolvedores</a></li>
      </ul>
      <div class='nav-icon flex'>
          <a href='login.php'><i class='bx bx-user-circle'></i></a>
      </div>

      <div class='bx bx-menu' id='menu-icon'></div>
  </header>

    <!--DISPLAY TABELA DE PRODUTOS-->
    <main>
      <div class='page-title'>SEU CARRINHO</div><!--NOME DO CARRINHO-->
      <div class='content'><!--ENGLOBA TUDO!!-->
        
        <section> <!--MOSTRA OS PRODUTOS DENTRO DO CARRINHO-->
          <table> <!--TABELA DE FORMATAÇÃO-->
            <thead> <!--CABEÇALHO DA TABELA-->
              <tr>
                <th>Produto</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Total</th>
                <!--<th>-</th>-->
              </tr>
            </thead>
<?php
//exibe o carrinho


if(count($_SESSION['sessao'])==0){
    echo"carrinho vazio!!<br> <a href='exemplo.php'>adicionar itens</a>";
}else{
    try {
        $conn = new PDO('pgsql:host=pgsql.projetoscti.com.br; dbname=projetoscti22; user=projetoscti22; password=721175');
    } catch (PDOException $e) {
    echo "Erro de Conexão: " . $e->getMessage() . "\n";
    exit;
    }
    
    foreach($_SESSION['sessao'] as $cod_produto => $quantidade){
        $select=$conn->prepare("SELECT * FROM tbl_produto WHERE cod_produto=?");
        $select->bindParam(1,$cod_produto);
        $select->execute();
        $produtos=$select->fetchAll();
       
       /* echo 
        $produtos[0]['nome_produto']."<br>".
        $produtos[0]['preco_produto']."<br>".
        $quantidade."<br>".
        $produtos[0]['preco_produto']*$quantidade."<br><br>"
        ;
        echo"<a href='remover.php?remover=carrinho&id=".$cod_produto."'>Remover do carrinho</a><br>";*/
        echo"<tbody> <!--PRODUTO1-->
              <tr>
                <td> <!--DISPLAY DA IMAGEM E DESCRIÇÃO DO PRODUTO-->
                  <div class='product'> 
                    <img src='foto.jfif' alt='' width='100' height='120' />
                    <div class='info'>
                      <div class='name'>".$produtos[0]['nome_produto']."</div>
                      <div class='category'>".$produtos[0]['descricao_produto']."</div>
                    </div>
                  </div>
                </td>

                <td>R$ ".$produtos[0]['preco_produto']."</td> <!--VALOR-->
                
                <td> <!--ADICIONAR OU REMOVER PRODUTOS-->
                  <div class='qty'>
                    <a href=''><button><i class='bx bx-minus'></i></button></a>
                    <span>".$quantidade."</span>
                    <a href='carrinho.php?operacao=incluir&codigoProduto=".$cod_produto."'><button><i class='bx bx-plus'></i></button></a>
                  </div>
                </td>

                <td>R$ ".$produtos[0]['preco_produto']*$quantidade."</td> <!--SOMA TOTAL (PRODUTO*QUANTIDADE)-->

                <td> <!--BOTÃO DE REMOVER PRODUTO DO CARRINHO-->
                  <a href='carrinho.php?operacao=excluir&codigoProduto=".$cod_produto."'><button class='remove'><i class='bx bx-x'></i></button></a>
                </td>

              </tr>
            </tbody>"; 
    }
}

?>
</table>
        </section>

        <aside>
          <div class='box'> <!--ESPAÇO DE FINALIZAR COMPRA-->
            
            <section>Resumo da compra</section> <!--NOME DO ESPAÇO-->
           
            <div class='info'> <!--INFOS DA COMPRA-->
             <!-- <div><span>Sub-total</span><span>R$ 418</span></div>-->
              <div><span>Frete</span><span>Gratuito</span></div>
             
              <div> <!--CUPOM DESCONTO-->
                <button>
                  Adicionar cupom de desconto
                  <i class='bx bx-right-arrow-alt'></i>
                </button>
              </div>

            </div>
            <?php
            try {
                $conn = new PDO('pgsql:host=pgsql.projetoscti.com.br; dbname=projetoscti22; user=projetoscti22; password=721175');
            } catch (PDOException $e) {
            echo "Erro de Conexão: " . $e->getMessage() . "\n";
            exit;
            }
            
            foreach($_SESSION['sessao'] as $cod_produto => $quantidade){
                $select=$conn->prepare("SELECT * FROM tbl_produto WHERE cod_produto=?");
                $select->bindParam(1,$cod_produto);
                $select->execute();
                $produtos=$select->fetchAll();
                 echo"   <footer> <!--APRESENTA VALOR FINAL DA COMPRA-->
                    <span>Total</span>
                    <span>".$produtos[0]['preco_produto']*$quantidade."</span>
                    </footer>";
            }
            ?>
          </div>

         <a href=''>Finalizar Compra</a> <!--BOTÃO FINALIZAR COMPRA-->
        </aside>

      </div>
    </main>
  </body>
</html>