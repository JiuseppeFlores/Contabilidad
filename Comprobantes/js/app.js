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
    {field: "debe_s",title: "DEBE (SU$)"},
    {field: "haber_s",title: "HABER (SU$)"},
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
      debe: asiento.debe,
      haber: asiento.haber,
      debe_s: Math.round(parseFloat(asiento.debe) * parseFloat(tc), 2),
      haber_s: Math.round(parseFloat(asiento.haber) * parseFloat(tc), 2),
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
      field: "actions",
      title: "ACCIONES",
      align: "center",
      clickToSelect: false,/*
      events: window.operateEvents,
      formatter: operateFormatter,*/
    },
    {
      field: "archivo",
      title: "ARCHIVO",
      align: "center",
      formatter: function (value, row, index) {
        let color = "var(--bs-danger)";
        let habil = "";
        let direccion = "#";
        if (row.filepdf == "no") {
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
      },
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
  $("#total_debe_s").text("");
  $("#total_haber_s").text("");
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

async function get_counts() {
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
    var debe = parseFloat($("#total_debe").text());
    var haber = parseFloat($("#total_haber").text());
    var debe_s = parseFloat($("#total_debe_s").text());
    var haber_s = parseFloat($("#total_haber_s").text());

    console.log(debe, haber);

    if (debe != haber && debe_s != haber_s) {
      alert("Existe diferencia en las sumas de DEBE y HABER");
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
            $("#modal_registrar_comprobante").modal("hide");
            listar_comprobantes();
            show_toast(
              ACCION,
              response.message,
              response.success ? "text-bg-success" : "text-bg-danger"
            );
            console.log("[" + ACCION + "] " + response.message);
            console.log("Mensaje PDF", response.pdf);
          },
          error: function (error) {
            $("#modal_registrar_comprobante").modal("hide");
            show_toast(ACCION, error.statusText, "text-bg-danger");
            console.log("[" + ACCION + "] ", error);
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

function listar_comprobantes() {
  let params = new URLSearchParams(location.search);
  var pagina =
    params.get("page") == null
      ? 1
      : parseInt(params.get("page")) > 0
      ? parseInt(params.get("page"))
      : 1;
  const ACCION = "LISTAR COMPROBANTES";
  var datos = { pagina: pagina, total: CANTIDAD_REGISTROS };
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
        //start_pagination( pagina , response.data.total );
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
    return [
      '<a class="edit" href="javascript:void(0)" title="Editar">',
      '<i class="bi bi-pencil-fill text-primary"></i>',
      "</a>  ",
      '<a class="remove" href="javascript:void(0)" title="Eliminar">',
      '<i class="bi bi-trash-fill text-danger"></i>',
      "</a>",
    ].join("");
  }
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
    },
    success: function (response) {
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
