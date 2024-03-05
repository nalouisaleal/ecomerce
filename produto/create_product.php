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
   <!--MENU-->
<header>
      
</header>

<!--DISPLAY TABELA DE PRODUTOS-->
<main>
  <div class='page-title'>INSERIR PRODUTOS</div><!--NOME DO CARRINHO-->
  <div class='container'>
    <!--bloco de display do produto-->
    <section>
        <div class='frame float'>
            <div class='foto'>
                <img src='https://picsum.photos/326' alt='foto do produto'>
            </div>
            <form method="POST" action="insert.php">
            <div class='info'>
            <input type="text" name="nome_produto" placeholder='nome'>
            <input type="text" name="preco_produto" placeholder='preÃ§o' class='input-preco'>
            </div>
            <div class='desc'>
            <textarea name="descricao_produto" placeholder='descriÃ§Ã£o'></textarea>
            </div>
            <input type="submit" value="Criar" class='sub'>
            </form>
    </section>


   <!-- Name: <input type="text" name="nome_produto"><br>
    Description: <textarea name="descricao_produto"></textarea><br>
    Price: <input type="text" name="preco_produto"><br>
    <input type="submit" value="Create">-->

