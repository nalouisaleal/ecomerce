<?php
// mostra erros do php
ini_set ( 'display_errors' , 1); 
error_reporting (E_ALL);  

include('util.php');

try {
    $conexao = new PDO('pgsql:host=pgsql.projetoscti.com.br; dbname=projetoscti22; user=projetoscti22; password=721175');
} catch (PDOException $e) {
echo "Erro de Conexão: " . $e->getMessage() . "\n";
exit;
}
$select=$conexao->prepare("SELECT * FROM tbl_produto");
$select->execute();
$fetch=$select->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link rel="stylesheet" href="../perfil/perfil.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>

    <!--barra de menu-->
    <header class="flex">
        <a href="index.html" class="logo"><img src="imagens/logo.png" alt="logo"></a>
       <ul class="flex none">
            <li>
                <form action="https://www.google.com/search" method="get" class="search-bar"> 
                    <input type="text" placeholder="search">
                    <button type="submit"><i class='bx bx-search'></i></button>
                </form>
            </li>
        </ul>
        <ul class="navmenu flex" id="navbar">
            <li><a href="../perfil/" >Home</a></li>
            <li><a href="../produto/display_produtos.php">CRUD</a></li>
            <li><a href="carrinho/carrinho.php">Usuários</a></li>
            <li><a href="devops/desenvolvedores.html">Relatórios</a></li>
        </ul>
        <div class="nav-icon flex">
            <a href="../projetoscti10/login/login.php"><i class='bx bx-user-circle'></i></a>
        </div>

    </header>

<main>
  <div class='page-title'>PRODUTOS</div>
  <div class='container'>
<?php

foreach($fetch as $produto)
{
  //$produto['preco_produto']
  echo"
  <!--bloco de display do produto-->
  <section>
      <div class='frame float'>
          <div class='foto'>
              <img src='https://picsum.photos/326' alt='foto do produto'>
          </div>
          <div class='info'>
              <h2>".$produto['nome_produto']."</h2>
              <a href='delete_product.php??cod_produto=".$produto['cod_produto']."'><i class='bx bx-trash-alt'></i></a>
              <a href='edit_product.php?cod_produto=".$produto['cod_produto']."'><i class='bx bx-edit'></i></a>
              <a href='create_product.php'><i class='bx bx-plus-circle'></i></a>
          </div>
          <div class='desc'>
              <p>".$produto['descricao_produto']."</p>
          </div>
  </section>

";
}

?>
</div>
</body>
</html>