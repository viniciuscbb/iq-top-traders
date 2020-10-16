<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
    <textarea id="lista-sinais" name="lista" onchange="digita()" rows="100"></textarea>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script>
      function digita(){
        lista = $("#lista-sinais").serialize();
        a = `nome_robo=xinvictus&data=&slct_time_vela=5&slct_tipo_operacao=&slct_qnt_gale=0&slct_tipo_mercado=TRUE&timezone=America%2FSao_Paulo&sinaisduplicados=FALSE&onGT=&delay=00&txt_porct_banca=&txt_entrada_fixa=&txt_stk=&txt_stp=&slct_onTx=&lista=${lista}`;
        $.ajax({
          type: "POST",
          url: "https://conversordesinais.xyz/converter",
          data: a,
          dataType: "json",
          success: function(a) {
            $("#lista-sinais").val(a.dados.lista);
            console.log(a.dados.lista)
          },
          error: function(a, b, c) {
            console.log(b, c)
          }
        })
      }
    </script>
</body>

</html>