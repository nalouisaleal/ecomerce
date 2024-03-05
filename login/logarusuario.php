<?php
// Conex�o com o banco de dados
try {
    $conn = new PDO('pgsql:host=pgsql.projetoscti.com.br; dbname=projetoscti22; user=projetoscti22; password=721175');
} catch (PDOException $e) {
    echo "Erro de Conex�o: " . $e->getMessage() . "\n";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email_usuario']) && isset($_POST['senha_usuario'])) {
        $email = $_POST['email_usuario'];
        $senhaFornecida = $_POST['senha_usuario'];

        // Consulta para obter dados do usu�rio com base no email
        $sql = "SELECT cod_usuario, senha_usuario FROM tbl_usuario WHERE email_usuario = :email_usuario and senha_usuario = :senha_usuario";
        $consulta = $conn->prepare($sql);
	$consulta->bindValue(':email_usuario', $email, PDO::PARAM_STR);
	$consulta->bindValue(':senha_usuario', $senhaFornecida, PDO::PARAM_STR);
	$consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            // Armazenar a senha do banco de dados em uma vari�vel
            $senhaDoBanco = $resultado['senha_usuario'];
	//echo $senhaDoBanco;
	//echo $senhaFornecida;

            // Verificar a senha
            if (strcasecmp($senhaFornecida, $senhaDoBanco) === 0) {
                // Senha v�lida, redirecionar para a p�gina produtos.php com o c�digo do usu�rio
                $cod_usuario = $resultado['cod_usuario'];
                header("Location: ../produto/produtos.php?cod_usuario=$cod_usuario");
                exit();
            } else {
                	
		// Senha incorreta
                echo 'Senha incorreta. Por favor, verifique suas credenciais.';
            }
        } else {
            echo 'Usu�rio n�o encontrado. Por favor, verifique suas credenciais.';
        }
    } else {
        echo 'Email ou senha n�o fornecidos.';
    }
}
?>
