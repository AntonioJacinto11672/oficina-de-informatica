// Inicialização global das DataTables — idioma Português.
// Nota: a extensão "Responsive" do DataTables foi propositadamente deixada de
// fora — em combinação com tabelas sem linhas de dados, produzia o aviso
// "Incorrect column count" (datatables.net/tn/18). O scroll horizontal do
// Bootstrap (.table-responsive, já presente em todas as tabelas) continua a
// tratar do comportamento em ecrãs estreitos.
$(document).ready(function() {
  $('#dataTable').DataTable({
    language: {
      sEmptyTable: "Não existem dados disponíveis nesta tabela",
      sInfo: "A mostrar _START_ a _END_ de _TOTAL_ registos",
      sInfoEmpty: "A mostrar 0 a 0 de 0 registos",
      sInfoFiltered: "(filtrado de um total de _MAX_ registos)",
      sLengthMenu: "Mostrar _MENU_ registos",
      sLoadingRecords: "A carregar...",
      sProcessing: "A processar...",
      sSearch: "Pesquisar:",
      sZeroRecords: "Não foram encontrados registos correspondentes",
      oPaginate: {
        sFirst: "Primeiro",
        sLast: "Último",
        sNext: "Seguinte",
        sPrevious: "Anterior"
      },
      oAria: {
        sSortAscending: ": ativar para ordenar a coluna de forma ascendente",
        sSortDescending: ": ativar para ordenar a coluna de forma descendente"
      }
    }
  });
});
