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

});