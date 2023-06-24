const ACCION = "OBTENER NRO. COMPROBANTE";
var datos = { id_proyecto : 1 };
$.ajax({
    data: datos,
    url: 'services/obtener_numero_comprobante.php',
    type: 'GET',
    dataType: 'JSON',
    beforeSend: function(){
        console.log("["+ACCION+"] Enviando datos...");
    },
    success:function(response){
        if(response.success){
            $('#nro_comprobante').val(response.data);
            $('#comprobante_tipo_cambio').val('6.97');
            $('#tbl_asientos').bootstrapTable({
                showFooter: "true",
                columns: [{
                  field: 'codigo',
                  title: 'CÓDIGO',
                }, {
                  field: 'descripcion',
                  title: 'NOMBRE DE LA CUENTA',
                }, {
                    field: 'referencia',
                    title: 'REFERENCIA',
                }, {
                    field: 'debe',
                    title: 'DEBE',
                    footerFormatter: test,
                }, {
                    field: 'haber',
                    title: 'HABER',
                }, {
                    field: 'banco',
                    title: 'BANCO',
                }, {
                    field: 'cheque',
                    title: 'CHEQUE',
                }, {
                    field: 'action',
                    title: 'ACCION',
                    align: 'center',
                    clickToSelect: false,
                    events: window.operateEvents,
                    formatter: operateFormatter
                }],
                data: []/*,
                onDblClickRow: test*/
            });
        }else{
            $('#modal_registrar_comprobante').modal('hide');
            show_toast(ACCION,response.message,'text-bg-danger');
        }
        console.log("["+ACCION+"] "+response.message);
    },
    error: function(error){
        $('#modal_registrar_comprobante').modal('hide');
        show_toast(ACCION,error.statusText,'text-bg-danger');
        console.log("["+ACCION+"] ",error);
    }
});

const CABECERAS = {
    
};
/*codigo',
    title: 'CÓDIGO',
}, {
    field: 'descripcion',
    title: 'NOMBRE DE LA CUENTA',
}, {
    field: 'referencia',
    title: 'REFERENCIA',
}, {
    field: 'debe',
    title: 'DEBE',
}, {
    field: 'haber',
    title: 'HABER',
}, {
    field: 'banco',
    title: 'BANCO',
}, {
    field: 'cheque',*/
$('#btn_adicionar_asiento').on('click',() =>{
    console.log("add");
    var $table = $('#tbl_asientos');
    var data = $table.bootstrapTable('getData');
    console.log(data);
    var id = data.length + 1;
    $table.bootstrapTable('insertRow', {
        index: 1,
        row: {
            id: id,
            codigo: '',
            descripcion: '',
            referencia: '',
            debe: '',
            haber: '',
            banco: '',
            cheque: ''
        }
    });
});

function operateFormatter(value, row, index) {
    if(row.nivel == 'G'){
        return [
            ''
        ].join('');
    }else{
        return [
            '<!--<a class="edit" href="javascript:void(0)" title="Editar">',
            '<i class="bi bi-pencil-fill text-primary"></i>',
            '</a>  -->',
            '<a class="remove" href="javascript:void(0)" title="Eliminar">',
            '<i class="bi bi-dash-circle-fill text-danger fs-5"></i>',
            '</a>'
        ].join('');
    }
}

window.operateEvents = {
    'click .edit': function (e, value, row, index) {
      //alert('You click like action, row: ' + JSON.stringify(row))
      editar_cuenta(row.idCuenta);
    },
    'click .remove': function (e, value, row, index) {
        //eliminar_cuenta(row.idCuenta,row.descripcion.replaceAll("&nbsp;", ''));
        var $table = $('#tbl_asientos');
        $table.bootstrapTable('remove', {
            field: 'id',
            values: [row.id]
        });
    }
};

$('#btn_remover_asiento').on('click',() =>{
    console.log("rem");
    var $table = $('#tbl_asientos');
    $table.bootstrapTable('remove', {
        field: '$index',
        values: [1]
    });
});

function test(data, footerValue) {
    return footerValue
  }