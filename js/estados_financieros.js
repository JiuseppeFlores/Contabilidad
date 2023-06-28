$(document).ready(function(){
    $('#bg_fecha_final').val(obtenerFecha('m'));
    $('#er_fecha_final').val(obtenerFecha('m'));
});

$('#form_balance_general').on('submit', (e) => {
    var fechaInicial = (new Date().getFullYear())+"-01-01";
    var fechaFinal = $('#bg_fecha_final').val();
    fechaFinal = fechaFinal + "-" + obtenerUltimoDia(fechaFinal);
    var nivel = $('input[name="bg_nivel"]:checked').val();
    var moneda = $('input[name="bg_moneda"]:checked').val();

    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "Reportes/balanceGeneralPdf.php");
    form.setAttribute("target", "_blank");
    form.innerHTML = `
        <input type="hidden" name="fechaInicial" value="${fechaInicial}">
        <input type="hidden" name="fechaFinal" value="${fechaFinal}">
        <input type="hidden" name="nivel" value="${nivel}">
        <input type="hidden" name="moneda" value="${moneda}">
    `;
    document.body.appendChild(form);
    form.submit();
    form.remove();
    $('#modal_balance_general').modal('hide');
    e.preventDefault();
});

$('#form_estado_resultados').on('submit', (e) => {
    var fechaInicial = (new Date().getFullYear())+"-01-01";
    var fechaFinal = $('#er_fecha_final').val();
    fechaFinal = fechaFinal + "-" + obtenerUltimoDia(fechaFinal);
    var nivel = $('input[name="er_nivel"]:checked').val();
    var moneda = $('input[name="er_moneda"]:checked').val();
    var formato = $('#er_formato').prop('checked');

    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "Reportes/estadoResultadosPdf.php");
    form.setAttribute("target", "_blank");
    form.innerHTML = `
        <input type="hidden" name="fechaInicial" value="${fechaInicial}">
        <input type="hidden" name="fechaFinal" value="${fechaFinal}">
        <input type="hidden" name="nivel" value="${nivel}">
        <input type="hidden" name="moneda" value="${moneda}">
        <input type="hidden" name="formato" value="${formato}">
    `;
    document.body.appendChild(form);
    console.log(form);
    form.submit();
    form.remove();
    $('#modal_estado_resultados').modal('hide');
    e.preventDefault();
});