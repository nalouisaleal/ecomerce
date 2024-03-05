<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="perfil.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <a name="topo"></a>
    <title>Perfil do Usuário</title>
</head>
<body>
<header class="flex">
        <a href="../" class="logo"><img src="../imagens/logo.png" alt="logo"></a>
       <ul class="flex none">
            <li>
                <form action="https://www.google.com/search" method="get" class="search-bar"> 
                    <input type="text" placeholder="search">
                    <button type="submit"><i class='bx bx-search'></i></button>
                </form>
            </li>
        </ul>
        <ul class="navmenu flex" id="navbar">
            <li><a href="../">Home</a></li>
            <li><a href="produto/produtos.php">Produtos</a></li>
            <li><a href="carrinho/carrinho.php">Carrinho</a></li>
            <li><a href="devops/desenvolvedores.html">Desenvolvedores</a></li>
        </ul>
        <div class="nav-icon flex">
            <a href="../projetoscti10/login/login.php"><i class='bx bx-user-circle'></i></a>
        </div>
        
        <a href="#" class="btn-toggle" onclick="showMenu()" id="menu-icon"><i class='bx bx-menu'></i></a>
       
       <!-- <div class="bx bx-menu" id="menu-icon"></div>-->
    </header>
    <section class="perfil">
        <img id="pimg" src=../imagens/perfil.png width=50px>
    </section>
</body>
</html>

<?php
ini_set ( 'display_errors' , 1); 
error_reporting (E_ALL);   
session_start();
include("util.php");
?>