$(document).ready(() => {
  $("#documentacao").on("click", () => {
    //$("#pagina").load("documentacao.html");
    //$.get("documentacao.html", (data) => {
    //podereia ser qualquer parâmetro (está sendo recuperado o conteúdo em si da resposta) - data, valor..
    //console.log(data);
    //$("#pagina").html(data);
    //});
    $.post("documentacao.html", (data) => {
      $("#pagina").html(data);
    });
    //através da instancia do jQuery ($) será executado o método get (.get()) que espera a url .get('') e uma ação .get('', data => {}) - data pois estamos recuperando o conteúdo em si da resposta
  });
  $("#suporte").on("click", () => {
    //$("#pagina").load("suporte.html");
    //$.get("suporte.html", (data) => {
    //podereia ser qualquer parâmetro (está sendo recuperado o conteúdo em si da resposta) - data, valor..
    //console.log(data);
    //$("#pagina").html(data);
    //});
    $.post("suporte.html", (data) => {
      $("#pagina").html(data);
    });
  });
});
