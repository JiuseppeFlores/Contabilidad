let posicion = 0;
let rows = []; // contiene los indices de las filas
let rowsEliminar = []; // ids asiento que han sido eliminados y esta
let rowsInsert = []; // asientos a insertar
$(document).ready(function(){
  let c = Number($('#cantidad').val());
  posicion = c;
  rows = new Array(posicion);
  rows.fill().forEach((_,i) => rows[i] = i+1);

  totales();
  
  $("#form_editar_comprobante").on('submit', (e) => {
    e.preventDefault();
    updateComprobante();
    deleteAsientos();
    insertAsientos();
  })
})

$(document).on('change', 'input[name="debe[]"]', () => {
  dolares();
  totales();
});
$(document).on('change', 'input[name="haber[]"]', () => {
  dolares();
  totales();
});
$(document).on('change', 'input[name="debeDolar[]"]', () => {
  totales();
});
$(document).on('change', 'input[name="haberDolar[]"]', () => {
  totales();
});

function totales(){
  var total_debe = 0;
  var total_haber = 0;

  var total_debe_s = 0;
  var total_haber_s = 0;

  rows.forEach((i) => {
    total_debe += parseFloat($('#debe-'+i).val()) ?  parseFloat($('#debe-'+i).val()) : 0;
    total_haber += parseFloat($('#haber-'+i).val()) ? parseFloat($('#haber-'+i).val()) : 0;

    total_debe_s += parseFloat($('#debe-'+i+'-s').val()) ? parseFloat($('#debe-'+i+'-s').val()) : 0;
    total_haber_s += parseFloat($('#haber-'+i+'-s').val()) ? parseFloat($('#haber-'+i+'-s').val()) : 0;
  })
  
  $('#total_debe').text(total_debe.toFixed(2));
  $('#total_haber').text(total_haber.toFixed(2));
  $('#total_debe_s').text(total_debe_s.toFixed(2));
  $('#total_haber_s').text(total_haber_s.toFixed(2));
}

async function updateComprobante(){
  if(verificaTotales()){
    const id = $("#id_comprobante").val();
    const tipoComp = $("#comprobante_tipo").val();
    const fecha = $("#comprobante_fecha").val();
    const asientos = recuperarAsientosUpdate();
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
    console.log(data)
    console.log('-------- update')
  }else{
    show_toast('NO SE PUEDE ACTUALIZAR', 'Comprobante desbalanceado', 'text-bg-warning')
  }
  
}

function recuperarAsientosUpdate(){
  const asientos = [];
  // Obtenemos los asientos que se actualizaran
  const indices = rows.filter(element => !rowsInsert.includes(element));
  indices.forEach( (i) => {
    let inputs = $("#row-"+i).find('input');
    asientos.push({
      idAsiento: $(inputs[0]).val(),
      idCuenta: $(inputs[2]).val(),
      referencia: $(inputs[3]).val(),
      debe: $(inputs[4]).val() == '' ? 0 : $(inputs[4]).val(),
      haber: $(inputs[5]).val() == '' ? 0 : $(inputs[5]).val(),
      debeDolar: $(inputs[6]).val() == '' ? 0 : $(inputs[6]).val(),
      haberDolar: $(inputs[7]).val() == '' ? 0 : $(inputs[7]).val(),
      bco: $(inputs[8]).val(),
      cheque: $(inputs[9]).val(),
    })
  });
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

const dolares = () => {
  const cambio = parseFloat($("#comprobante_tipo_cambio").val());
  rows.forEach((i) => {
    let debe = parseFloat($("#debe-"+i).val() == '' ? 0 : $("#debe-"+i).val());
    let haber = parseFloat($("#haber-"+i).val() == '' ? 0 : $("#haber-"+i).val());
    $("#debe-"+i+'-s').val((debe / cambio).toFixed(2));
    $("#haber-"+i+'-s').val((haber / cambio).toFixed(2));
  })
}

const verificaTotales = () => {
  let debe = parseFloat($('#total_debe').text());
  let haber = parseFloat($('#total_haber').text());
  let debeDolar = parseFloat($('#total_debe_s').text());
  let haberDolar = parseFloat($('#total_haber_s').text());
  if(debe == haber && debeDolar == haberDolar){
    return true;
  }else{
    return false;
  }
}

const eliminarAsiento = (pos) => {
  if($("#idAsiento-"+pos).val() != '0'){
    rowsEliminar.push($("#idAsiento-"+pos).val());
  }
  $("#row-"+pos).remove();
  rows = rows.filter((element) => element !== pos);
  rowsInsert = rowsInsert.filter((element) => element !== pos);
  totales();
}

const deleteAsientos = () => {
  if(rowsEliminar.length > 0 && verificaTotales()){
    let data = {
      ids: rowsEliminar
    }
    $.ajax({
      type: "POST",
      url: "./services/eliminar_asientos.php",
      data: data,
      dataType: "JSON",
      success: function (response) {
        console.log(response)
        if(!response.ok){
          show_toast(
            'OCURRIO UN ERROR',
            'Ocurrio un error al eliminar asientos',
            "text-bg-warning"
          );
        }
      },
      error: function (response) {
        console.log(response,'---Error')
      }
    })
  }
}

const insertAsientos = () => {
  if(rowsInsert.length > 0 && verificaTotales()){
    let asientos = [];
    rowsInsert.forEach((i) => {
      let inputs = $("#row-"+i).find('input');
      asientos.push({
        idAsiento: $(inputs[0]).val(),
        idCuenta: $(inputs[2]).val(),
        referencia: $(inputs[3]).val(),
        debe: $(inputs[4]).val() == '' ? 0 : $(inputs[4]).val(),
        haber: $(inputs[5]).val() == '' ? 0 : $(inputs[5]).val(),
        debeDolar: $(inputs[6]).val() == '' ? 0 : $(inputs[6]).val(),
        haberDolar: $(inputs[7]).val() == '' ? 0 : $(inputs[7]).val(),
        bco: $(inputs[8]).val(),
        cheque: $(inputs[9]).val(),
      })
    })
    const data = {
      idComprobante: $("#id_comprobante").val(),
      asientos
    }
    $.ajax({
      type: "POST",
      url: "./services/insertar_asientos.php",
      data: data,
      dataType: "JSON",
      success: function (response) {
        console.log(response)
        if(!response.ok){
          show_toast(
            'OCURRIO UN ERROR',
            'Ocurrio un error al insertar asientos',
            "text-bg-warning"
          );
        }
      },
      error: function (response) {
        console.log(response,'---Error INSERT')
      }
    })
  }
  console.log('//////// insert ')
}

const addAsiento = () => {
  posicion++;
  const id = posicion;
  rows.push(id);
  rowsInsert.push(id);
  $("#asientos").append(
  `<tr id="row-${id}">
    <input type="hidden" id="idAsiento-${id}"  value="0">
    <td>
      <div class="form-outline">
        <input type="text" name="codigo[]" id="codigo-${id}" class="form-control" autocomplete="off" value="" ondblclick="listarCuentas_e(this)">
      </div>
      <input type="hidden" name="cuenta[]" id="id-cuenta-${id}" value="">
    </td>
    <td>
      <span id="sp-${id}"></span>
    </td>
    <td>
      <div class="form-outline">
        <input type="text" id="referencia-${id}" name="referencia[]" class="form-control" value="">
      </div>
    </td>
    <td>
      <div class="form-outline">
        <input type="decimal" id="debe-${id}" name="debe[]" class="form-control" value="">
      </div>
    </td>
    <td>
      <div class="form-outline">
        <input type="decimal" id="haber-${id}" name="haber[]" class="form-control" value="">
      </div>
    </td>
    <td>
      <div class="form-outline">
        <input type="decimal" id="debe-${id}-s" name="debeDolar[]" class="form-control" value="">
      </div>
    </td>
    <td >
      <div class="form-outline">
        <input type="decimal" id="haber-${id}-s" name="haberDolar[]" class="form-control" value="">
      </div>
    </td>
    <td>
      <div class="form-outline">
        <input type="text" id="banco-${id}" name="banco[]" class="form-control" value="">
      </div>
    </td>
    <td>
      <div class="form-outline">
        <input type="text" id="cheque-${id}" name="cheque[]" class="form-control" value="">
      </div>
    </td>
    <td>
      <button type="button" class="btn btn-danger btn-circle" onclick="eliminarAsiento(${id})"><i class="bi bi-x"></i></button>
    </td>
  </tr>`
  );
}

$(document).on('focusout', 'input[name="codigo[]"]', (e) => {
  const codigo = e.currentTarget.value;
  if(codigo != ''){
    const id = e.currentTarget.id.split('-')[1];
    var datos = { 
      codigo: codigo,
      movimiento: 1
    };
    peticionObtenerCodigo(id, datos, 'OBTENER CUENTA CODIGO');
  }
})





const getRows = () => {
  return rows;
}
const getRowsEliminar = () => {
  return rowsEliminar;
}
const getRowsInsert = () => {
  return rowsInsert;
}