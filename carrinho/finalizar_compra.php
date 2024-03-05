<?php
// Inclua o util.php e estabeleça a conexão com o banco de dados
include("util.php");

try {
    $conn = new PDO('pgsql:host=pgsql.projetoscti.com.br; dbname=projetoscti22; user=projetoscti22; password=721175');
} catch (PDOException $e) {
    echo "Erro de Conexão: " . $e->getMessage() . "\n";
    exit;
}

// Verifique se a operação é "finalizar"
if (isset($_GET['operacao']) && $_GET['operacao'] === 'finalizar') {
    // Obtenha o código da compra da sessão atual
    $session_id =$_GET['session'];
    $codigoCompra = intval(ValorSQL($conn, "SELECT cod_compra FROM tbl_tmpcompra WHERE sessao_tmpcompra = '$session_id'"));
    $codigoUsuario = $_GET['cod_usuario'];
    $total=$_GET['total'];

   
    // Insira os detalhes da compra na tabela tbl_compra
    $dataHoje = date('Y/m/d');
    $statusCompra = 'Concluída';

    $sql = "INSERT INTO tbl_compra (data_compra, cod_usuario, sessao, valor_compra, status_compra)
            VALUES (:data_compra, :cod_usuario, :sessao, :valor_compra, :status_compra)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':data_compra', $dataHoje, PDO::PARAM_STR);
    $stmt->bindParam(':cod_usuario', $codigoUsuario, PDO::PARAM_INT);
    $stmt->bindParam(':sessao', $session_id, PDO::PARAM_STR);
    $stmt->bindParam(':valor_compra', $total, PDO::PARAM_INT);
    $stmt->bindParam(':status_compra', $statusCompra, PDO::PARAM_STR);
    $stmt->execute();

    // Redirecione de volta para a página inicial ou para onde você desejar
    header("Location: ../index.html"); // Substitua 'index.php' pela página desejada
    exit();
    
} else {
    echo 'Operação de finalização de compra inválida.';
}
?>