// Verifique a correspondência da senha no lado do cliente
document.getElementById('registrationForm').onsubmit = function validarSenha() {
    var senha = document.getElementById('senha').value;
    var confsenha = document.getElementById('confsenha').value;

    if (senha !== confsenha) {
        alert('As senhas não coincidem. Redigite.');
        return false; // Impede o envio do formulário
    }
}