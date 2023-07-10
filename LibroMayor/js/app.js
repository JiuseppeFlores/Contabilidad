$(document).ready(function(){
    var fecha_actual = obtenerFecha();
    $('#lm_fecha_inicial').val(fecha_actual);
    $('#lm_fecha_final').val(fecha_actual);
    $('#t_cuentas').bootstrapTable({
        search: true,
        searchAlign: "left",
        rowStyle: rowStyle,
        formatLoadingMessage: function(){return "Cargando"},
        columns: [{
          field: 'codigo',
          title: 'Codigo'
        }, {
          field: 'descripcion',
          title: 'Descripción'
        }],
        data: [],
        onDblClickRow: seleccionarCuenta
    });
    $('#tbl_registros').bootstrapTable({
        search: true,
        searchAlign: "left",
        showFooter: "true",
        columns: [{
          field: 'fecha',
          title: "FECHA"
        }, {
          field: 'tipoCambio',
          title: "T/C"
        }, {
            field: 'glosa',
            title: "DESCRIPCION"
        }, {
            field: 'cheque',
            title: "CHEQUE"
        }, {
            field: 'debe',
            title: "DEBE"
        }, {
            field: 'haber',
            title: "HABER"
        }, {
            field: 'saldos',
            title: "SALDOS"
        }],
        data: []
    });
});

$('#lm_descripcion').on('click', () => {
    $('#modal_lista_cuentas').modal('show');
    $('#t_cuentas').bootstrapTable('removeAll');
    $('#t_cuentas').bootstrapTable('showLoading');
    const ACCION = "LISTAR CUENTAS";
    var datos = { };
    $.ajax({
        data: datos,
        url: '../Cuentas/services/listar_cuentas.php',
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                console.log(response);
                response.data.forEach( (cuenta) => {
                    var sp = "&nbsp;";
                    switch(cuenta.nivel){
                        case 'G':sp = '';break;
                        case 'R':sp = sp.repeat(4);break;
                        case 'T':sp = sp.repeat(8);break;
                        case 'C':sp = sp.repeat(12);break;
                        case 'S':sp = sp.repeat(16);break;
                        case 'A':sp = sp.repeat(20);break;
                    };
                    cuenta.descripcion = sp + cuenta.descripcion;
                });
                $('#t_cuentas').bootstrapTable('hideLoading');
                $('#t_cuentas').bootstrapTable('load', response.data);
                
            }else{
                show_toast(ACCION,response.message,'text-bg-danger');
            }
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            show_toast(ACCION,error.statusText,'text-bg-danger');
            console.log("["+ACCION+"] "+error.statusText);
        }
    });
});

function rowStyle(row, index) {
    var classes = [
      'fw-semibold text-muted',
      'text-primary'
    ];

    if( row.movimiento == 1 ){
        return {
            classes: classes[1]
        };
    }else{
        return {
            classes: classes[0]
        };
    }
}

function seleccionarCuenta(e){
    if(e.movimiento == 1){
        console.log("Valido");
        document.getElementById('lm_descripcion').value = e.descripcion.replaceAll("&nbsp;", '');
        document.getElementById('lm_codigo').value = e.codigo;
        $('#modal_lista_cuentas').modal('hide');
    }else{
        console.log("NO VALIDO");
        alert("La cuenta seleccionada no tiene movimientos.");
    }
}

$('#btn_generar_reporte').on('click', () => {
    var descripcion = document.getElementById('lm_descripcion').value;
    var codigo = document.getElementById('lm_codigo').value;
    var fechaInicial = document.getElementById('lm_fecha_inicial').value;
    var fechaFinal = document.getElementById('lm_fecha_final').value;
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "../Reportes/libroMayorPdf.php");
    form.setAttribute("target", "_blank");
    form.innerHTML = `
        <input type="hidden" name="descripcion" value="${descripcion}">
        <input type="hidden" name="codigoCuenta" value="${codigo}">
        <input type="hidden" name="fechaInicial" value="${fechaInicial}">
        <input type="hidden" name="fechaFinal" value="${fechaFinal}">
    `;
    document.body.appendChild(form);
    form.submit();
    form.remove();
});

$('#btn_ver_registros').on('click', () => {
    const ACCION = "VER REGISTROS LIBRO MAYOR";
    var datos = { 
        descripcion: $('#lm_descripcion').val(),
        codigo: $('#lm_codigo').val(),
        fecha_inicial: $('#lm_fecha_inicial').val(),
        fecha_final: $('#lm_fecha_final').val()
    };
    $.ajax({
        data: datos,
        url: 'services/registros_libro_mayor.php',
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                console.log(response);
                response.data.forEach( (registro, index) => {
                    registro.tipoCambio = registro.tipoCambio.toFixed(2);
                    registro.saldos = (
                        index == 0 ? 
                        (registro.debe - registro.haber).toFixed(2) : 
                        (registro.debe - registro.haber + parseFloat(response.data[index - 1].saldos)).toFixed(2)
                    );
                    registro.debe = registro.debe.toFixed(2);
                    registro.haber = registro.haber.toFixed(2);
                });
                $('#tbl_registros').bootstrapTable('removeAll');
                $('#tbl_registros').bootstrapTable('load', response.data);
            }else{
                show_toast(ACCION,response.message,'text-bg-danger');
            }
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            show_toast(ACCION,error.statusText,'text-bg-danger');
            console.log("["+ACCION+"] "+error.statusText);
        }
    });
});