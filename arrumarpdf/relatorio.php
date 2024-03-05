<?php
// mostra erros do php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("util.php");


// calcula hoje
$hoje = date('Y-m-d');
// calcula ontem
$ontem = date('Y-m-d', (strtotime('-1 day', strtotime($hoje))));

echo "
     <form action='' method='POST'>
      Data inicial<br><input type='date' name='datai' value='$ontem'><br>
      Data final<br><input type='date' name='dataf' value='$ontem'><br>
      <input type='radio' name='op' value='html' checked> Mostrar na tela<br>
      <input type='radio' name='op' value='pdf'> Gerar PDF<br>
      <br>
      <input type='checkbox' name='preview'>Gerar e Pré-Visualizar (Atenção: marque o botão gerar pdf além da checkbox!)<br>
      <br>
      <input type='submit' value='Gerar'>
     </form> ";

if ($_POST) {
    // faz conexao
    $conn = conecta();

    $datai = $_POST['datai'];
    $dataf = $_POST['dataf'];
    $op = isset($_POST['op']) ? $_POST['op'] : 'html';
    $preview = isset($_POST['preview']);

    $SQLCompra = "
            SELECT tbl_compra.cod_compra, tbl_compra.data_compra, tbl_usuario.nome_usuario, tbl_compra.valor_compra
            FROM tbl_compra
            INNER JOIN tbl_usuario ON tbl_compra.cod_usuario = tbl_usuario.cod_usuario 
            WHERE tbl_compra.data_compra >= :datai 
                AND tbl_compra.data_compra <= :dataf 
                AND TRIM(LOWER(tbl_compra.status_compra)) = 'concluída'
            ORDER BY tbl_compra.data_compra
        ";

    // formata valores em reais
    setlocale(LC_ALL, 'pt_BR.utf-8');

    $html = "<html>";

    // abre a consulta de COMPRA do periodo
    $compra = $conn->prepare($SQLCompra);
    $compra->execute(['datai' => $datai, 'dataf' => $dataf]);

    $timestamp = date('Ymd_His');
    $nomepdf = "relatorios/relatorio_$timestamp.pdf";
    ///////////// M E S T R E ////////////////////
    // carrega a proxima linha COMPRA
    $html .= "<br><br>
        <b>" .
        sprintf('%5s', 'Id') .
        sprintf('%22s', 'Data') .
        sprintf('%44s', 'Nome') .
        sprintf('%36s', 'Total') .
        "</b>
        <br>";

    while ($linha_compra = $compra->fetch()) {
        $cod_compra = sprintf('%5s', $linha_compra['cod_compra']);
        $data = sprintf('%22s', $linha_compra['data_compra']);
        $cliente = sprintf('%44s', $linha_compra['nome_usuario']);
        $total = sprintf('%36s', number_format($linha_compra['valor_compra'], 2, ',', '.'));

        $html .= $cod_compra . $data . $cliente . $total . "<br>";
    }

    $html .= "</html>";

    if ($op == 'html') {
      echo $html;
  } elseif ($op == 'pdf') {
      // Use mpdf to convert HTML to PDF
      $mpdf = new Mpdf();
      $mpdf->WriteHTML($html);

      // Output PDF to a file
      $mpdf->Output($nomepdf, 'F');

      echo 'Gerado com sucesso';
      if ($preview) {
          header('Location: ' . $nomepdf);
          exit;
      }}
}
?>
