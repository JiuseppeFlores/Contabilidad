$(document).ready(function(){
    var fecha_actual = obtenerFecha();
    $('#ss_fecha_inicial').val(fecha_actual);
    $('#ss_fecha_final').val(fecha_actual);
    $('#tbl_registros').bootstrapTable({
        search: true,
        searchAlign: "left",
        showFooter: "true",
        columns: [{
          field: 'codigo',
          title: "CODIGO"
        }, {
          field: 'descripcion',
          title: "DESCRIPCION"
        }, {
            field: 'debe',
            title: "DEBE"
        }, {
            field: 'haber',
            title: "HABER"
        }, {
            field: 'deudor',
            title: "DEUDOR"
        }, {
            field: 'acreedor',
            title: "ACREEDOR"
        }],
        data: []
    });
});

$('#btn_generar_reporte').on('click', () => {
    var fechaInicial = document.getElementById('ss_fecha_inicial').value;
    var fechaFinal = document.getElementById('ss_fecha_final').value;
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "../Reportes/balanceSumasSaldosPdf.php");
    form.setAttribute("target", "_blank");
    form.innerHTML = `
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