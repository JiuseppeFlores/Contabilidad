const footerDebe = (data) => {
    var totalDebe = 0;
    data.forEach((cuenta) => {
        totalDebe += parseFloat(cuenta.debe);
    })
    return totalDebe.toFixed(2);
}
const footerHaber = (data) => {
    var totalHaber = 0;
    data.forEach((cuenta) => {
        totalHaber += parseFloat(cuenta.haber);
    })
    return totalHaber.toFixed(2);
}
const footerDeudor = (data) => {
    var totalDeudor = 0;
    data.forEach((cuenta) => {
        totalDeudor += parseFloat(cuenta.deudor);
    })
    return totalDeudor.toFixed(2);
}
const footerAcreedor = (data) => {
    var totalAcreedor = 0;
    data.forEach((cuenta) => {
        totalAcreedor += parseFloat(cuenta.acreedor);
    })
    return totalAcreedor.toFixed(2);
}
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
            align: "right",
            field: 'debe',
            title: "DEBE",
            footerFormatter: footerDebe
        }, {
            align: "right",
            field: 'haber',
            title: "HABER",
            footerFormatter: footerHaber
        }, {
            align: "right",
            field: 'deudor',
            title: "DEUDOR",
            footerFormatter: footerDeudor
        }, {
            align: "right",
            field: 'acreedor',
            title: "ACREEDOR",
            footerFormatter: footerAcreedor
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
    const ACCION = "VER REGISTROS BALANCE DE SUMAS Y SALDOS";
    var datos = {
        fecha_inicial: $('#ss_fecha_inicial').val(),
        fecha_final: $('#ss_fecha_final').val()
    };
    $.ajax({
        data: datos,
        url: 'services/registros_cuentas_saldos.php',
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                response.data.forEach( (registro) => {
                    registro.deudor = (registro.debe - registro.haber > 0 ) ? registro.debe - registro.haber : 0;
                    registro.acreedor = (registro.debe - registro.haber < 0 ) ? registro.haber - registro.debe : 0;

                    registro.debe = registro.debe.toFixed(2);
                    registro.haber = registro.haber.toFixed(2);
                    registro.deudor = registro.deudor.toFixed(2);
                    registro.acreedor = registro.acreedor.toFixed(2);
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