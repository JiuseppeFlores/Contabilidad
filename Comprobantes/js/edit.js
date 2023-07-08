$(document).ready(function(){
  let c = Number($('#cantidad').val());
  totales(c);
  
  $("#form_editar_comprobante").on('submit', (e) => {
    e.preventDefault();
    updateComprobante(c);
  })
})

function totales(c){
  var total_debe = 0;
  var total_haber = 0;

  var total_debe_s = 0;
  var total_haber_s = 0;

  for( i = 1 ; i <= c; i++ ){
    total_debe += parseFloat($('#debe-'+i).val());
    total_haber += parseFloat($('#haber-'+i).val());

    total_debe_s += parseFloat($('#debe-'+i+'-s').text()) ? parseFloat($('#debe-'+i+'-s').text()) : 0;
    total_haber_s += parseFloat($('#haber-'+i+'-s').text()) ? parseFloat($('#haber-'+i+'-s').text()) : 0;
  }
  
  $('#total_debe').text(total_debe.toFixed(2));
  $('#total_haber').text(total_haber.toFixed(2));
  $('#total_debe_s').text(total_debe_s.toFixed(2));
  $('#total_haber_s').text(total_haber_s.toFixed(2));
}

async function updateComprobante(c){
  const id = $("#id_comprobante").val();
  const tipoComp = $("#comprobante_tipo").val();
  const fecha = $("#comprobante_fecha").val();
  const asientos = recuperarAsientos();
  let data = {};
  const existePDF = $("#namePDF").val();
  let pdf = await leerArchivo();
  if(pdf != -1){ // existe archivo
    data = {
      idComprobante: id,
      tipoComprobante: tipoComp,
      numero: $("#nro_comprobante").val(),
      fecha: fecha,
      tipoCambio: $("#comprobante_tipo_cambio").val(),
      moneda: $("#comprobante_moneda").val(),
      comprobanteDetalle: $("#comprobante_detalle").val(),
      comprobanteNit: $("#comprobante_nit_ci").val(),
      nroRecibo: $("#comprobante_nro_recibo").val(),
      glosa: $("#comprobante_glosa").val(),
      asientos: asientos,
      pdfFile: pdf,
      existePDF: existePDF,
    }
  }else{
    data = {
      idComprobante: id,
      tipoComprobante: tipoComp,
      numero: $("#nro_comprobante").val(),
      fecha: fecha,
      tipoCambio: $("#comprobante_tipo_cambio").val(),
      moneda: $("#comprobante_moneda").val(),
      comprobanteDetalle: $("#comprobante_detalle").val(),
      comprobanteNit: $("#comprobante_nit_ci").val(),
      nroRecibo: $("#comprobante_nro_recibo").val(),
      glosa: $("#comprobante_glosa").val(),
      asientos: asientos,
    }
  }
  $.ajax({
    type: "POST",
    url: "./services/update_comprobante.php",
    data: data,
    dataType: "JSON",
    success: function (response) {
      console.log(response)
      if(Number(response.success) == 1){
        show_toast(
          'ACTUALIZADO',
          'Comprobante y asientos actualizados',
          "text-bg-success"
        );
        setTimeout(() => {
          window.location.href = "./index.php";
        }, 1010)
      }else{
        show_toast(
          'OCURRIO UN ERROR',
          'Ocurrio un error al actualizar',
          "text-bg-warning"
        );
      }

    },
    error: function (response) {
      console.log(response,'---Error')
    }
  })
  console.log(data);
}

function recuperarAsientos(){
  const asientos = [];
  const rows = $("#asientos tr");

  rows.each((index, row) => {
    let inputs = $(row).find('input');
    asientos.push({
      idAsiento: $(inputs[0]).val(),
      idCuenta: $(inputs[2]).val(),
      referencia: $(inputs[3]).val(),
      debe: $(inputs[4]).val(),
      haber: $(inputs[5]).val(),
      bco: $(inputs[6]).val(),
      cheque: $(inputs[7]).val(),
    })
  })
  return asientos;
}

function leerArchivo(){
  var pdfFile = document.getElementById("pdfFile").files[0];
  if(pdfFile == null || pdfFile == undefined){
    return new Promise((resolve, reject) => resolve(-1));
  }
  return new Promise((resolve, reject) => {
    var reader = new FileReader();

    reader.onloadend = function () {
      var base64Data = btoa(reader.result);
      resolve(base64Data);
    };

    reader.onerror = function (error) {
      reject(error);
    };

    reader.readAsBinaryString(pdfFile);
  });
}

const listarCuentas_e = (e) => {
  console.log(e)
  const id = $(e).attr('id').split('-')[1];
  console.log(id);
  // $('#t_cuentas').bootstrapTable('removeAll');
  //$('#modal_lista_cuentas').attr('id-asiento',id);
  $('#modal_lista_cuentas').attr('data-id-asiento', id);
  $('#modal_lista_cuentas').modal('show');
  seleccionarCuenta();
};