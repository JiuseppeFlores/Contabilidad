let spinner = `
<div class="text-center">
  <div class="spinner-border" role="status">
    <span class="visually-hidden">Cargando...</span>
  </div>
</div>`;
$('#tbl_asientos').bootstrapTable({
  search: true,
  searchAlign: "left",
  showPagination: "true",
  editable: "true",
  detailView: 1,
  onExpandRow: function (index, row, $detail) {
    /* eslint no-use-before-define: ["error", { "functions": false }]*/
    //expandTable($detail, cells - 1)
    console.log(index,row,$detail);
    console.log("detalle:",row);
    //listarAsientos($detail);
  },
  columns: [{
      field: 'numero',
      title: 'NÚMERO',
      align: 'center',
  }, {
      field: 'referencia',
      title: 'REFERENCIA',
      align: 'center',
  }, {
      field: 'debe',
      title: 'DEBE',
      align: 'center',
  }, {
    field: 'haber',
    title: 'HABER',
    align: 'center',
  }, {
    field: 'bco',
    title: 'BCO',
    align: 'center',
  }, {
      field: '',
      title: 'EMPAREJADO',
      align: 'center',
      clickToSelect: false,
      events: window.operateEvents,
      formatter: operateFormatter
  }],
  data: []
});

function uploadExcel(){
  var inputFile = document.getElementById("excelFile").files[0];
  if(inputFile != null && inputFile != undefined){
    console.log("Archivo enviado", inputFile);
    $("#modal_subir_excel").modal('hide');
    $("#tbl_asientos").empty();
    $("#tbl_asientos").html(spinner) 
  }
  // var formData = new FormData();
  // formData.append("excelFile", inputFile);

  // var xhr = new XMLHttpRequest();
  // xhr.open("POST", "./principal.php", true);

  // xhr.onreadystatechange = function() {
  //   if (xhr.readyState === XMLHttpRequest.DONE) {
  //     if (xhr.status === 200) {
  //       // Éxito: archivo enviado correctamente
  //       console.log(xhr.responseText);
  //     } else {
  //       // Error al enviar el archivo
  //       console.error("Error al enviar el archivo.");
  //     }
  //   }
  // };

  // xhr.send(formData);
}

function listar_asientos(){

}


function operateFormatter(value, row, index) {
  if(row.nivel == 'G'){
      return [
          ''
      ].join('');
  }else{
      return [
          '<a class="edit" href="javascript:void(0)" title="Editar">',
          '<i class="bi bi-pencil-fill text-primary"></i>',
          '</a>  ',
          '<a class="remove" href="javascript:void(0)" title="Eliminar">',
          '<i class="bi bi-trash-fill text-danger"></i>',
          '</a>'
      ].join('');
  }
}