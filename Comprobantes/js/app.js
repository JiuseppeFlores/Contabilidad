// MARCANDO EL NAV-ITEM CORRESPONDIENTE
$('#nav_asientos').addClass('active');
// INICIALIZANDO VARIABLES COMPONENTES

$(document).ready(function() {
    $('.mi-selector').select2();
});
/*var options = {
    format: 'dd-mm-yyyy'
}
var myDatepicker = new mdb.Datepicker(document.getElementById('exampleDatepicker1'), options)*/
async function get_counts(){
    const ACCION = "OBTENER CUENTAS";
    var datos = { pagina : 1 , total : 1000 };
    await $.ajax({
        data: datos,
        url: '../Cuentas/services/listar_cuentas.php',
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                response.data.forEach( (count) => {
                    CUENTAS.push({
                        id: count.idCuenta,
                        codigo: count.codigo,
                        cuenta: count.descripcion
                    });
                });
            }
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            console.log("["+ACCION+"] "+error.statusText);
        }
    });
}