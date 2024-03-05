<?php
// mostra erros do php
ini_set ( 'display_errors' , 1); 
error_reporting (E_ALL);  

try {
    $conn = new PDO('pgsql:host=pgsql.projetoscti.com.br; dbname=projetoscti22; user=projetoscti22; password=721175');
} catch (PDOException $e) {
echo "Erro de Conex�o: " . $e->getMessage() . "\n";
exit;
}


// Recupere o c digo do usu rio da URL
if (isset($_GET['cod_usuario'])) {
    $codUsuario = $_GET['cod_usuario'];
}else{
    $codUsuario=0;
}
$select=$conn->prepare("SELECT * FROM tbl_produto");
$select->execute();
$fetch=$select->fetchAll();

//var_dump($fetch);
?>

<!DOCTYPE html>
<html lang='pt-br'>
<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Produtos</title>
    <link rel='stylesheet' href='shop.css'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
   
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
        <li><a href='../carrinho/carrinho.php'>Carrinho</a></li>
        <li><a href='../devops/desenvolvedores.html'>Desenvolvedores</a></li>
    </ul>
    <div class='nav-icon flex'>
        <a href='../login/login.php'><i class='bx bx-user-circle'></i></a>
	<script src="/java.js"></script>

    </div>

</header>

<main>
  <div class='page-title'>PRODUTOS</div>
  <div class='container'>

<?php
ini_set ( 'display_errors' , 1); 
error_reporting (E_ALL); 
include("util.php");
session_start();
require_once 'config.php';

$conn = conecta();
$sql = "SELECT * FROM tbl_produto";
$result = $conn->query($sql);
$fetch=$result->fetchAll();

$select = $conn->query(" select * from tbl_produto
where excluido_produto = false
order by descricao_produto ");

$codigoProduto=null;
$excluidoProduto=null;
while ( $linha = $select->fetch() )  
{
  // imprime as posicoes de $linha que sao os campos carregados  
  $codigoProduto = $linha['cod_produto'];
  $excluidoProduto = $linha['excluido_produto'];
}

// Recupere o c digo do usu rio da URL
if (isset($_GET['cod_usuario'])) {
    $codUsuario = $_GET['cod_usuario'];
} else{
    $codUsuario=0;
}

// verifica se o login foi feito    
if (isset($_SESSION['sessaoConectado'])) {
$sessaoConectado = $_SESSION['sessaoConectado'];

} else { 
    $sessaoConectado = false; 
}



    // caso esteja logado ...
if ( $sessaoConectado ) {
    // recupera o valor do cookie com o usuario    
    if (isset($_COOKIE['loginCookie'])) {
    $loginCookie = $_COOKIE['loginCookie']; 
    } else {
    $loginCookie = '';
    }
    //var_dump($excluidoProduto);
    if ( isset($_SESSION['sessaoAdmin']) ) {       
    if ($_SESSION['sessaoAdmin']) {
        foreach($fetch as $row){
            echo" <section>";
           echo" <div class='frame float'>";
           echo"    <div class='foto'>";
           echo"         <img src='../imagens/f1.jpeg' alt='foto do produto'>";
           echo"     </div>";
           echo"     <div class='info'>";
           echo"         <h2> ".$row['nome_produto']."</h2>";
           echo"         <a href='edit_product.php?cod_produto=" . $row['cod_produto'] . "'><i class='bx bx-edit'></i></a> ";
           echo"         <a href='delete_product.php?cod_produto=" . $row['cod_produto'] . "'><i class='bx bx-x-circle'></i></a>";
           echo"     </div>";
           echo"     <div class='desc'>";
           echo"        <p>" . $row['descricao_produto'] . "</p>";
           echo"    </div>";
           echo "<a href='create_product.php'><img src = '../imagens/botadd.png'></a>";
           echo"</section>";
        }
    }
    else{
        foreach($fetch as $row){
            if($codUsuario==0){
                echo" <section>";
                echo" <div class='frame'>";
                echo"    <div class='foto'>";
                echo"         <img src='../imagens/f1.jpeg' alt='foto do produto'>";
                echo"     </div>";
                echo"     <div class='info'>";
                echo"        <h2>".$row['nome_produto']."</h2>";
                echo"            <a href='../carrinho/carrinho.php?operacao=incluir&codigoProduto=".$row['cod_produto']."&cod_usuario=".$codUsuario."' ><i class='bx bx-cart'></i></a>";    echo"     </div>";
                echo"     <div class='desc'>";
                echo"        <p>" . $row['descricao_produto'] . "</p>";
                echo"    </div>";
                echo"</section></div>";
            }
        }
    }
}

else{
    foreach($fetch as $row){
        if($codUsuario==0){
           
            echo" <div class='frame'>";
            echo"    <div class='foto'>";
            echo"         <img src='../imagens/f1.jpeg' alt='foto do produto'>";
            echo"     </div>";
            echo"     <div class='info'>";
            echo"        <h2>".$row['nome_produto']."</h2>";
            echo"            <a href='../carrinho/carrinho.php?operacao=incluir&codigoProduto=".$row['cod_produto']."&cod_usuario=".$codUsuario."' ><i class='bx bx-cart'></i></a>";    echo"     </div>";
            echo"     <div class='desc'>";
            echo"        <p>" . $row['descricao_produto'] . "</p>";
            echo"    </div>";
            echo"</div>";
           
        }
}
}
}else{
    foreach($fetch as $row){
        if($codUsuario==0){
           
            echo" <div class='frame'>";
            echo"    <div class='foto'>";
            echo"         <img src='../imagens/f1.jpeg' alt='foto do produto'>";
            echo"     </div>";
            echo"     <div class='info'>";
            echo"        <h2>".$row['nome_produto']."</h2>";
            echo"     </div>";
            echo"     <div class='desc'>";
            echo"        <p>" . $row['descricao_produto'] . "</p>";
            echo"    </div>";
            echo"</div>";
           
        }else{
            
            echo" <div class='frame'>";
            echo"    <div class='foto'>";
            echo"         <img src='../imagens/f1.jpeg' alt='foto do produto'>";
            echo"     </div>";
            echo"     <div class='info'>";
            echo"        <h2>".$row['nome_produto']."</h2>";
            echo"            <a href='../carrinho/carrinho.php?operacao=incluir&codigoProduto=".$row['cod_produto']."&cod_usuario=".$codUsuario."' ><i class='bx bx-cart'></i></a>";    echo"     </div>";
            echo"     <div class='desc'>";
            echo"        <p>" . $row['descricao_produto'] . "</p>";
            echo"    </div>";
            echo"</div>";
        }
    }
}
?>
</main>
<main>
<div class='page-title'>ATRAÇÕES</div>
<div class='container'>
    <section>
        <div class='frame float'>
            <div class='foto'>
                <img src='../imagens/acessorios.png' alt='foto do produto'>
            </div>
            <div class='info'>
                <h2>Acessórios</h2>
            </div>
            <div class='desc'>
                <p>Use nossos acessórios para dar um tcham nas suas fotos</p>
            </div>
    </section>
    <section>
        <div class='frame float'>
            <div class='foto'>
                <img src='../imagens/canetas.png' alt='foto do produto'>
            </div>
            <div class='info'>
                <h2>Canetas</h2>
                <!--<a href='#'><i class='bx bx-heart-circle can'></i></a>-->
            </div>
            <div class='desc'>
                <p>Anote suas memórias</p>
            </div>
    </section>
</div>
</main>
 <!--rodape-->
 <section class="contact">
        <div class="contact-info">
            <div class="first-info">
                <img src="../imagens/logo.png" alt="">
                <p><a href="https://cti.feb.unesp.br/">Colégio Técnico Industrial "Prof. Isaac Portal Roldán"</a></p>
                <p><a href="https://www.google.com/maps/place/Col%C3%A9gio+T%C3%A9cnico+Industrial+%22Prof.+Isaac+Portal+Rold%C3%A1n%22+-+UNESP/@-22.340622,-49.0276889,17z/data=!3m1!4b1!4m6!3m5!1s0x94bf67ba4accea4f:0xc015eb23d210db44!8m2!3d-22.340627!4d-49.025114!16s%2Fg%2F12176jrj?entry=ttu">Av. Nações Unidas, 58-50 - Núcleo Residencial <br> Presidente Geisel - Bauru/SP</a></p>
                <p><a href="mailto:clickfun2023@gmail.com?subject=">clickfun2023@gmail.com</a></p>

                <div class="social-icon">
                    <a href="https://instagram.com/clickfunbauru?igshid=NzZlODBkYWE4Ng=="><i class='bx bxl-instagram'></i></a>
                    <a href="https://www.youtube.com/channel/UC1-BXPs1yCiEPBx0tQEF0aQ/"><i class='bx bxl-youtube'></i></a>
                </div>
            </div>

            <div class="second-info">
                <h4>Ajuda</h4>
                <p><a href="mailto:clickfun2023@gmail.com?subject=Suporte">Contact us</a></p>
                <p><a href="devops/desenvolvedores.html">Desenvolvedores</a></p>
            </div>

            <div class="third-info">
                <h4>Comprar</h4>
                <p><a href="produto/produtos.php">Polaroids</a></p>
            </div>

            <div class="fourth-info">
                <h4>Projeto</h4>
                <p><a href="devops/desenvolvedores.html">Desenvolvedores</a></p>
            </div>
            <div class = "subir">
                <h4>Voltar</h4>
                <p><a href="#"><i class='bx bxs-to-top'></i></a></p>
            </div>
        </div>
    </section>

    <div class="end-text">
        <p>Copyright © @2023. All Rights Reserved. Design By ClickFun.</p>
    </div>

</body>
</html>
