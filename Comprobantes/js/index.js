// MARCANDO EL NAV-ITEM CORRESPONDIENTE
$('#nav_asientos').addClass('active');
// INICIALIZANDO EL LISTADO DE COMPROBANTES
listar_comprobantes();

const cuentaSeleccionada = (cuenta) => {
    if(cuenta.movimiento == 0){
        alert("Seleccione una cuenta que genere movimiento.");
    }else{
        var id = $('#modal_lista_cuentas').attr('data-id-asiento');
        $("#id-cuenta-"+id).val(cuenta.idCuenta);
        $('#codigo-'+id).val(cuenta.codigo);
        $('#sp-'+id).text(cuenta.descripcion.replaceAll("&nbsp;", ""));
        $('#modal_lista_cuentas').modal('hide');
    }
}

function removerAsiento(){
    var e = document.getElementById('asientos').lastChild;
    e.remove();
    ASIENTOS.pop();
}

$("#t_cuentas").bootstrapTable({
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
    data: [],
    onDblClickRow: cuentaSeleccionada,
});

listar_cuentas();