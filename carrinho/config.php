<?php
// mostra erros do php
ini_set ( 'display_errors' , 1); 
error_reporting (E_ALL); 

try {
    $conn = new PDO('pgsql:host=pgsql.projetoscti.com.br; dbname=projetoscti22; user=projetoscti22; password=721175');
} catch (PDOException $e) {
echo "Erro de Conexão: " . $e->getMessage() . "\n";
exit;
}

/*$hostname = 'pgsql:host=pgsql.projetoscti.com.br';
$username = 'projetoscti22';
$password = '721175';
$database = 'projetoscti22';

$conn = new PDO($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}*/
?>
