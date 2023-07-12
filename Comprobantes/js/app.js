const listarAsiento = (index,row,$detail) => {
  console.log($detail);
  const ACCION = "OBTENER DATOS COMPROBANTE";
  var datos = { idComprobante: row.idComprobante };
  $.ajax({
    data: datos,
    url: "services/obtener_comprobante.php",
    type: "GET",
    dataType: "JSON",
    beforeSend: function () {
      console.log("[" + ACCION + "] Enviando datos...");
    },
    success: function (response) {
      console.log(response);
      if (response.success) {
        buildTable($detail.html('<table></table>').find('table'), 3, 1,response.data.asientos, response.data.tipoCambio);
      }
      console.log("[" + ACCION + "] " + response.message);
    },
    error: function (error) {
      console.log("[" + ACCION + "] ", error);
    },
  });
}

function buildTable($el, cells, rows, asientos, tc) {
  var i; var j; var row
  var columns = [
    {field: "codigo",title: "CODIGO"},
    {field: "descripcion",title: "CUENTA"},
    {field: "referencia",title: "REFERENCIA"},
    {field: "debe",title: "DEBE"},
    {field: "haber",title: "HABER"},
    {field: "debeDolar",title: "DEBE (SU$)"},
    {field: "haberDolar",title: "HABER (SU$)"},
    {field: "banco",title: "BANCO"},
    {field: "cheque",title: "CHEQUE"},
  ]
  var data = []
  console.log(asientos);
  asientos.forEach((asiento) => {
    row = {
      codigo: asiento.codigo,
      descripcion: asiento.descripcion,
      referencia: asiento.referencia,
      debe: asiento.debe.toFixed(2),
      haber: asiento.haber.toFixed(2),
      debeDolar: asiento.debeDolar.toFixed(2),
      haberDolar: asiento.haberDolar.toFixed(2),
      banco: asiento.bco,
      cheque: asiento.cheque
    };
    
    data.push(row)
  });
  $el.bootstrapTable({
    columns: columns,
    data: data,
  })
}

const formatterUser = (value) => {
  var style = '<span class="badge text-dark">'+(value == null ? "S/N" : value)+'</span>';
  return style;
}

const formatterStatus = (value) => {
  var style = '<span class="badge text-'+(value == "ACTIVO" ? "success" : "danger")+'">'+value+'</span>';
  return style;
}

$("#tbl_comprobantes").bootstrapTable({
  search: true,
  searchAlign: "left",
  showPagination: "true",
  editable: "true",
  detailView: 1,
  onExpandRow: listarAsiento,
  columns: [
    {
      field: "numero",
      title: "NÚMERO",
      align: "center",
    },
    {
      field: "tipo",
      title: "TIPO",
      align: "center",
    },
    {
      field: "fecha",
      title: "FECHA",
      align: "center",
    },
    {
      field: "glosa",
      title: "GLOSA",
    },
    {
      align: "center",
      field: "estado",
      title: "ESTADO",
      formatter: formatterStatus,
    },{
      align: "center",
      field: "usuario",
      title: "CREADO",
      formatter: formatterUser,
    },
    {
      field: "actions",
      title: "ACCIONES",
      align: "center",
      clickToSelect: false,
      events: window.operateEvents,
      formatter: operateFormatter,
    },
    {
      field: "archivo",
      title: "ARCHIVO",
      align: "center",
      formatter: operatorFile,
    },
  ],
  data: [],
});

// facturas
var FACTURAS = {};

const limpiarModal = () => {
  $("#form_registro_comprobante").trigger("reset");
  $("#asientos").html("");
  $("#total_debe").text("");
  $("#total_haber").text("");
  $("#total_debe_dolar").text("");
  $("#total_haber_dolar").text("");
  ASIENTOS = [];
  FACTURAS = {};
};

$("#modal_registrar_comprobante").on('show.bs.modal',()=>{
  $("#comprobante_fecha").val(obtenerFecha());
});

function obtenerNroComprobante(){
  const ACCION = "OBTENER NRO. COMPROBANTE";
  var datos = { 
    // fecha: obtenerFecha(),
    fecha: $("#comprobante_fecha").val(),
    tipo: $('#comprobante_tipo').val()
  };
  $.ajax({
    data: datos,
    url: "services/obtener_numero_comprobante.php",
    type: "GET",
    dataType: "JSON",
    beforeSend: function () {
      console.log("[" + ACCION + "] Enviando datos...");
    },
    success: function (response) {
      console.log(response);
      if (response.success) {
        $("#nro_comprobante").val(response.data);
      }
      console.log("[" + ACCION + "] " + response.message);
    },
    error: function (error) {
      show_toast(ACCION, error.statusText, "text-bg-danger");
      console.log("[" + ACCION + "] ", error);
    },
  });
}

function get_counts() {
  const ACCION = "OBTENER CUENTAS";
  var datos = { movimiento: 1 };
  $.ajax({
    data: datos,
    url: "../Cuentas/services/listar_cuentas.php",
    type: "GET",
    dataType: "JSON",
    beforeSend: function () {
      console.log("[" + ACCION + "] Enviando datos...");
    },
    success: function (response) {
      if (response.success) {
        response.data.forEach((count) => {
          CUENTAS.push({
            id: count.idCuenta,
            codigo: count.codigo,
            cuenta: count.descripcion,
          });
        });
      }
      console.log("[" + ACCION + "] " + response.message);
    },
    error: function (error) {
      console.log("[" + ACCION + "] " + error.statusText);
    },
  });
}



$("#form_registro_comprobante").on("submit", function (e) {
  e.preventDefault();
  if (ASIENTOS.length > 0) {
    var debe = $("#total_debe").text();
    var haber = $("#total_haber").text();
    var debe_s = $("#total_debe_dolar").text();
    var haber_s = $("#total_haber_dolar").text();

    console.log(debe, haber, debe_s, haber_s);

    if (debe != haber || debe_s != haber_s) {
      //alert("Comprobante desbalanceado");
      show_toast('NO SE PUEDE ADICIONAR', 'Comprobante desbalanceado', 'text-bg-warning');
      return;
    }

    const ACCION = "REGISTRAR COMPROBANTE";
    let datos = $("#form_registro_comprobante").serialize();
    datos = datos + "&total_asientos=" + ASIENTOS.length;
    if (Object.keys(FACTURAS).length > 0) {
      datos = datos + "&facts=" + encodeURIComponent(JSON.stringify(FACTURAS));
      console.log("FACTURAS", JSON.stringify(FACTURAS))
    }

    var pdfFile = document.getElementById("pdfFile").files[0];
    if (pdfFile != null && pdfFile != undefined) {
      console.log("Existe archivo");
      var reader = new FileReader();
      reader.onloadend = function () {
        var base64Data = btoa(reader.result);
        datos += "&pdfFile=" + encodeURIComponent(base64Data);

        // console.log(datos)
        $.ajax({
          data: datos,
          url: "services/registrar_comprobante.php",
          type: "POST",
          dataType: "JSON",
          beforeSend: function () {
            console.log("[" + ACCION + "] Enviando datos...");
          },
          success: function (response) {
            /*$("#modal_registrar_comprobante").modal("hide");
            listar_comprobantes();
            show_toast(
              ACCION,
              response.message,
              response.success ? "text-bg-success" : "text-bg-danger"
            );
            console.log("[" + ACCION + "] " + response.message);
            console.log("Mensaje PDF", response.pdf);*/
          },
          error: function (error) {
            /*$("#modal_registrar_comprobante").modal("hide");
            show_toast(ACCION, error.statusText, "text-bg-danger");
            console.log("[" + ACCION + "] ", error);*/
          },
        });
      };
      reader.readAsBinaryString(pdfFile);
    } else {
      $.ajax({
        data: datos,
        // data: {datos:datos, },
        url: "services/registrar_comprobante.php",
        type: "POST",
        dataType: "JSON",
        beforeSend: function () {
          console.log("[" + ACCION + "] Enviando datos...");
        },
        success: function (response) {
          $("#modal_registrar_comprobante").modal("hide");
          listar_comprobantes();
          show_toast(
            ACCION,
            response.message,
            response.success ? "text-bg-success" : "text-bg-danger"
          );
          console.log("[" + ACCION + "] " + response.message);
          console.log(response)
        },
        error: function (error) {
          $("#modal_registrar_comprobante").modal("hide");
          show_toast(ACCION, error.statusText, "text-bg-danger");
          console.log("[" + ACCION + "] ", error);
        },
      });
    }
  } else {
    console.log("Debe asignar asientos contables");
    alert("Debe asignar asientos contables");
  }

  limpiarModal();
});

function listar_comprobantes(estado) {
  let params = new URLSearchParams(location.search);
  var pagina =
    params.get("page") == null
      ? 1
      : parseInt(params.get("page")) > 0
      ? parseInt(params.get("page"))
      : 1;
  const ACCION = "LISTAR COMPROBANTES";
  var datos = { 
    pagina: pagina,
    total: CANTIDAD_REGISTROS,
    estado: (estado == undefined) ? 'ACTIVO' : estado
  };
  $.ajax({
    data: datos,
    url: "services/listar_comprobantes.php",
    type: "GET",
    dataType: "JSON",
    beforeSend: function () {
      console.log("[" + ACCION + "] Enviando datos...");
    },
    success: function (response) {
      if (response.success) {
        //console.log(response);
        $("#tbl_comprobantes").bootstrapTable("removeAll");
        $("#tbl_comprobantes").bootstrapTable(
          "load",
          response.data.comprobantes
        );
        start_pagination( pagina , response.data.total );
      } else {
        show_toast(ACCION, response.message, "text-bg-danger");
      }
      console.log("[" + ACCION + "] " + response.message);
    },
    error: function (error) {
      show_toast(ACCION, error.statusText, "text-bg-danger");
      console.log(error);
      console.log("[" + ACCION + "] " + error.statusText);
    },
  });
}

function operateFormatter(value, row, index) {
  if (row.nivel == "G") {
    return [""].join("");
  } else {
    let url = window.location.origin + '/Contabilidad/Reportes/comprobantePdf.php?idComprobante='+row.idComprobante;
    console.log(url);
    return [
      '<div style="text-align: center;display: flex;flex-direction: row;justify-content: space-evenly;">',
      '<a href="./edit.php?id=' + row.idComprobante +'" title="EDITAR" class="btn btn-primary"><i style="font-size:17px;" class="bi bi-pen"></i></a>',
      '<button data-bs-toggle="modal" data-bs-target="#modal_anular_comprobante" data-id="' + row.idComprobante +'" title="ANULAR" class="btn btn-danger"><i style="font-size:17px;" class="bi bi-x-octagon"></i></button>',
      '<a href="'+url+'" target="_blank" title="IMPRIMIR" class="btn btn-dark"><i class="bi bi-printer" style="font-size:17px;"></i></a>',
      '</div>'
    ].join("");
  }
}
function operatorFile (value, row, index) {
  let color = "var(--bs-danger)";
  let habil = "";
  let direccion = "#";
  if (row.filepdf == "no" || row.filepdf == null) {
    color = "var(--bs-gray)";
    habil = "disabled";
  } else {
    let index = window.location.href.split("/").pop();
    direccion =
      window.location.href.replace(index, "") + "Files/" + row.filepdf;
  }
  return (
    '<a href="' +
    direccion +
    '" class="btn btn-light ' +
    habil +
    '" target="_blank"><i style="font-size:21px;color:' +
    color +
    '" class="bi bi-file-earmark-pdf-fill"></i></a>'
  );
}

window.operateEvents = {
  "click .edit": function (e, value, row, index) {
    //alert('You click like action, row: ' + JSON.stringify(row))
    editar_cuenta(row.idCuenta);
  },
  "click .remove": function (e, value, row, index) {
    eliminar_cuenta(row.idCuenta, row.descripcion.replaceAll("&nbsp;", ""));
    /*$table.bootstrapTable('remove', {
        field: 'id',
        values: [row.id]
      })*/
  },
};

function listar_cuentas() {
  const ACCION = "LISTAR CUENTAS";
  var datos = {};
  $.ajax({
    data: datos,
    url: "services/listar_cuentas.php",
    type: "GET",
    dataType: "JSON",
    beforeSend: function () {
      console.log("[" + ACCION + "] Enviando datos...");
      $("#spinner_table").html(`
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Cargando...</span>
      </div>`);
    },
    success: function (response) {
      $("#spinner_table").html('').hide();
      if (response.success) {
        console.log(response);
        response.data.forEach((cuenta) => {
          var sp = "&nbsp";
          switch (cuenta.nivel) {
            case "G":
              sp = "";
              break;
            case "R":
              sp = sp.repeat(4);
              break;
            case "T":
              sp = sp.repeat(8);
              break;
            case "C":
              sp = sp.repeat(12);
              break;
            case "S":
              sp = sp.repeat(16);
              break;
          }
          cuenta.descripcion = sp + cuenta.descripcion;
        });
        /*$("#t_cuentas").bootstrapTable({
          columns: [
            {
              field: "codigo",
              title: "Codigo",
            },
            {
              field: "descripcion",
              title: "Descripción",
            },
          ],
          data: response.data,
          onDblClickRow: test,
        });*/
      } else {
        show_toast(ACCION, response.message, "text-bg-danger");
      }
      console.log("[" + ACCION + "] " + response.message);
    },
    error: function (error) {
      show_toast(ACCION, error.statusText, "text-bg-danger");
      console.log("[" + ACCION + "] " + error.statusText);
    },
  });
}

function test(e) {
  console.log(e);
}
function modalAgregarFactura() {
  $("#modal_registrar_factura").modal("show");
}

function obtenerValorParametro(url, parametro) {
  parametro = parametro.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  let regex = new RegExp("[\\?&]" + parametro + "=([^&#]*)");
  let results = regex.exec(url);
  return results === null
    ? ""
    : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function getFacturas() {
  return FACTURAS;
}

function peticionPrueba(objeto){
  // const objeto = {
  //   0:{
  //     nit: "12345678",
  //     nroFactura: "23333",
  //     nuevo: "https://dafdsfasdf/afdfasdfasdfd&nuevo=090988&otro=oud"
  //   },
  //   2:{
  //     nit: "12345678",
  //     nroFactura: "32423",
  //     nuevo:"no"
  //   }
  // }
  const valores = "nro=1"+"&facts=" + JSON.stringify(FACTURAS);
  $.ajax({
    data: {datos:valores},
    url: "services/prueba.php",
    type: "POST",
    success: function (response) {
      console.log(response)
    },
    error: function (error) {
      console.log("ERROR----", error)
    },
  });
}

$("#modal_anular_comprobante").on("show.bs.modal", function (e) {
  var id = $(e.relatedTarget).data().id;
  $("#id_comprobante").val(id);
});

function anularComprobante(){
  const id_comprobante = $("#id_comprobante").val();
  // console.log(id_comprobante)
  $.ajax({
    data: {id:id_comprobante},
    url: "services/anular_comprobante.php",
    type: "POST",
    dataType: "JSON",
    beforeSend: function () {
    },
    success: function (response) {
      // console.log(response)
      if(response.code){
        show_toast(`ANULAR COMPROBANTE`, response.message, "text-bg-info")
        listar_comprobantes();
      }else{
        show_toast(`ANULAR COMPROBANTE`, response.message, "text-bg-danger")
      }
    },
    error: function (error) {
      show_toast('OCURRIO UN ERROR', 'No se anuló el comprobante', "text-bg-danger");
      console.log('ERROR anular comprobante ',error);
    },
  });
}

$('#btn_ver_comprobantes').on('change',(e)=>{
  var estado = $('#btn_ver_comprobantes').prop('checked'); 
  if(estado){
    $('#lbl_ver_comprobantes').text('Ver Activos');
    listar_comprobantes('ANULADO');
  }else{
    $('#lbl_ver_comprobantes').text('Ver Anulados');
    listar_comprobantes('ACTIVO');
  }
});