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
?>
