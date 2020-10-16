$('#lista-sinais').on('input', function(e) {
  digita();
});

function digita() {
  var mercado = $("#mercado option:selected").val();
  var timeframe = $("#timeframe option:selected").val();
  lista = $("#lista-sinais").serialize();
  a = `nome_robo=xinvictus&data=&slct_time_vela=${timeframe}&slct_tipo_operacao=&slct_qnt_gale=0&slct_tipo_mercado=${mercado}&timezone=America%2FSao_Paulo&sinaisduplicados=TRUE&onGT=&delay=00&txt_porct_banca=&txt_entrada_fixa=&txt_stk=&txt_stp=&slct_onTx=&lista=${lista}`;
  $.ajax({
    type: "POST",
    url: "https://conversordesinais.xyz/converter",
    data: a,
    dataType: "json",
    success: function(a) {
      $("#btCheck").prop("disabled", false);
      $("#lista-sinais").val(a.dados.lista);
    },
    error: function(a, b, c) {
      console.log(b, c)
    }
  })
}