<?php
require 'util.php';
ini_set('default_charset','UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o campo de email foi enviado via formulário
    if (isset($_POST['email_usuario'])) {
        $email = $_POST['email_usuario'];

        // Conecta ao banco de dados 
        $conn = conecta();

        // Verifica se o e-mail fornecido existe na base de dados
        $varSQL = "SELECT nome_usuario FROM tbl_usuario WHERE email_usuario = '$email'";
        $resultado = $conn->query($varSQL)->fetch();

        if ($resultado) {
            // Gera uma nova senha aleatória
            $novaSenha = GeraSenha(8, true, true, true);
            
            // Atualiza a senha na base de dados
            $updateSQL = "UPDATE tbl_usuario SET senha_usuario = '$novaSenha' WHERE email_usuario = '$email';";
            if (ExecutaSQL($conn, $updateSQL)) {
                // Envie a nova senha por e-mail
                $assunto = 'Atualização de senha';
                $mensagem = "Sua nova senha é: $novaSenha";
               $enviado = EnviaEmail($email, $assunto, $mensagem);

                if ($enviado) {
                    echo 'Uma nova senha foi enviada para o seu e-mail.';
                } else {
                    echo 'Ocorreu um erro ao enviar a nova senha por e-mail. Por favor, tente novamente mais tarde.';
                }
            } else {
                echo 'Ocorreu um erro ao redefinir a senha. Por favor, tente novamente mais tarde.';
            }
        } else {
            echo 'O e-mail fornecido não está cadastrado.';
        }
    }
}
?>

<!-- Formulário HTML para permitir ao usuário inserir seu e-mail -->
<!DOCTYPE html>
<html>
<head>
    <title>Esqueci Minha Senha</title>
</head>
<body>
    <h2>Esqueci Minha Senha</h2>
    <form method="post">
        <label for="email_usuario">E-mail:</label>
        <input type="email_usuario" id="email_usuario" name="email_usuario" required>
        <button type="submit">Redefinir Senha</button>
    </form>
</body>
</html>
