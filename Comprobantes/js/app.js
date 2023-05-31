// MARCANDO EL NAV-ITEM CORRESPONDIENTE
$('#nav_asientos').addClass('active');
// INICIALIZANDO VARIABLES COMPONENTES

function adicionar_comprobante(){
    $('#modal_registrar_comprobante').modal('show');
    $('#comprobante_fecha').val(get_date());
}

async function get_counts(){
    const ACCION = "OBTENER CUENTAS";
    var datos = { pagina : 1 , total : 1000 };
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

$('#form_registro_comprobante').on('submit', function(e){
    e.preventDefault();
    if(ASIENTOS.length > 0){

    }else{
        console.log("Debe asignar asientos contables");
    }
});