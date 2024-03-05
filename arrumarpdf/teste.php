<?php 

   // mostra erros do php
   ini_set ( 'display_errors' , 1); 
   error_reporting (E_ALL);   
   
   include("util.php");

   // calcula hoje
   $hoje = date('Y-m-d');
   // calcula ontem
   $ontem = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $hoje ) ) ));
  
   echo "
     <form action='' method='POST'>
      Data inicial<br><input type='date' name='datai' value='$ontem'><br>
      Data final<br><input type='date' name='dataf' value='$hoje'><br>
      <input type='radio' name='op' value='tela' checked> Mostrar na tela<br>
      <input type='radio' name='op' value='pdf'> Gerar PDF<br><br>
      <input type='checkbox' name='prev'>Pré-Visualizar (Selecione a opção Gerar PDF também)<br><br>
      <input type='submit' value='Gerar'>
     </form> ";

   if ( $_POST ) {
      // faz conexao 
      $conn = conecta();

      $datai = $_POST['datai'];
      $dataf = $_POST['dataf'];
      $op = isset($_POST['op']) ? $_POST['op'] : 'tela';
      $prev = isset($_POST['prev']);

      $SQLCompra = 
              "select tbl_compra.cod_compra, tbl_compra.data_compra, tbl_usuario.nome, 
                  sum ( tbl_carrinho.qtde * tbl_produto.preco ) total  
                from tbl_compra 
                  inner join tbl_usuario on tbl_compra.cod_usuario = tbl_usuario.cod_usuario 
                  inner join tbl_carrinho on tbl_carrinho.cod_compra = tbl_compra.cod_compra
                  inner join tbl_produto on tbl_produto.cod_produto = tbl_carrinho.cod_produto 
                where 
                  tbl_compra.data_compra >= :datai and tbl_compra.data_compra <= :dataf and 
                  tbl_compra.status = 'Finalizada'  
                group by 
                  tbl_compra.cod_compra, tbl_compra.data_compra, tbl_usuario.nome 
                order by tbl_compra.data_compra "; 

      $SQLItensCompra = 
                "select tbl_produto.nome, tbl_carrinho.qtde, tbl_produto.preco, 
                  tbl_carrinho.qtde * tbl_produto.preco subtotal 
                from tbl_carrinho  
                  inner join tbl_produto on tbl_produto.cod_produto = tbl_carrinho.cod_produto 
                where 
                  tbl_carrinho.cod_compra = :cod_compra   
                order by tbl_produto.descricao "; 
    
      /*   m o d e l o
      Cod  Data        Cliente               $ Total
        1  20/10/2023  JOAO DA SILVA        10000,00
          Produto      Qt   Unit        Sub
          CHAVEIRO      2   50,00    100,00
          BOTOM         1   10,00     10,00
      */
  
      // formata valores em reais 
      setlocale(LC_ALL, 'pt_BR.utf-8', );

      $html = "<html>";

      // abre a consulta de COMPRA do periodo
      $compra = $conn->prepare($SQLCompra);
      $compra->execute ( [ 'datai' => $datai, 
                          'dataf' => $dataf ] );
      // prepara os ITENS     
      $itens_compra = $conn->prepare($SQLItensCompra);
      $nomepdf = "relatorios/relatorio_$datai-$dataf.pdf";

      // fetch significa carregar proxima linha
      // qdo nao tiver mais nenhuma retorna FALSE pro while
    
      /////////////  M E S T R E ////////////////////   
      // carrega a proxima linha COMPRA
      $html .= "<br><br>
              <b>".
              sprintf('%5s', 'Id').
              sprintf('%24s','Data').
              sprintf('%38s','Nome').
              sprintf('%26s','Total').
              "</b>
              <br>";

      while ( $linha_compra = $compra->fetch() )  
      {
        $cod_compra = sprintf('%18s',$linha_compra['cod_compra']);
        $data       = sprintf('%29s',$linha_compra['data_compra']);
        $cliente    = sprintf('%26s',$linha_compra['nome']);
        $total      = sprintf('%26s',number_format($linha_compra['total'], 2, ',', '.'));
        
        $html .= $cod_compra . $data . $cliente . $total . "<br>";               
            
        // executa ITENS passando o codigo da COMPRA atual
        $itens_compra->execute( [ 'cod_compra' => 
                              $linha_compra['cod_compra'] ] );

        $html .= "<b>".
              sprintf('%20s','Prod').
              sprintf('%20s','Qtd').
              sprintf('%26s','$ unit').
              sprintf('%25s','$ sub').
              "</b><br>";

        function limitarComQuebraDeLinha($texto, $limite) {
            if (strlen($texto) > $limite) {
                $texto = substr($texto, 0, $limite) . "<br>" . substr($texto, $limite);
            }
            return $texto;
        }
            
        /////////////  D E T A L H E  ////////////////////
        // carrega a proxima linha ITENS_COMPRA
        while ( $linha_itens_compra = $itens_compra->fetch() ) 
        {
          $produto  = sprintf('%30s', limitarComQuebraDeLinha($linha_itens_compra['nome'], 20));
          $qtd      = sprintf('%21s',$linha_itens_compra['qtde']);
          $unit     = sprintf('%27s',number_format($linha_itens_compra['preco'], 2, ',', '.'));
          $subtotal = sprintf('%25s',number_format($linha_itens_compra['subtotal'], 2, ',', '.'));

          $html .= $produto . $qtd . $unit . $subtotal . "<br>";
        } 
      }

      $html.="</html>";

      if($op == 'tela'){

        echo $html;

      }
      else if($op == 'pdf'){
        if (CriaPDF ( 'Relatorio de Vendas', $html, $nomepdf)){
            if($prev)
            {
              header('Location: '.$nomepdf);
              exit;
            }
        }else{ 
          echo 'Erro ao gerar';
        }
      }

      //header('Location: relatorios/relatorio.pdf');

   }
  
   echo "<br><a href='index.php'>Home</a>"; 
?>