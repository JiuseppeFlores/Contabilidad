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
    rowStyle: rowStyle,
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

listar_cuentas();