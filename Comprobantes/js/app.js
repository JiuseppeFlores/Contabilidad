$('#modal_registrar_comprobante').on('hide.bs.modal', () => {
    $('#form_registro_comprobante').trigger("reset");
    $('#asientos').html("");
    ASIENTOS = [];
});

function adicionar_comprobante(){
    $('#modal_registrar_comprobante').modal('show');
    $('#comprobante_fecha').val(get_date());
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
                response.data.accounts.forEach( (count) => {
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
        const ACCION = "REGISTRAR COMPROBANTE";
        let datos = $('#form_registro_comprobante').serialize();
        datos = datos+"&total_asientos="+ASIENTOS.length;
        $.ajax({
            data: datos,
            url: 'services/registrar_comprobante.php',
            type: 'POST',
            dataType: 'JSON',
            beforeSend: function(){
                console.log("["+ACCION+"] Enviando datos...");
            },
            success:function(response){
                $('#modal_registrar_comprobante').modal('hide');
                listar_comprobantes()
                show_toast(
                    ACCION,
                    response.message,
                    response.success ? 'text-bg-success' : 'text-bg-danger'
                );
                console.log("["+ACCION+"] "+response.message);
            },
            error: function(error){
                $('#modal_registrar_comprobante').modal('hide');
                show_toast(ACCION,error.statusText,'text-bg-danger');
                console.log("["+ACCION+"] ",error);
            }
        });
    }else{
        console.log("Debe asignar asientos contables");
    }
});

function listar_comprobantes(){
    let params = new URLSearchParams(location.search);
    var pagina = (params.get('page') == null ? 1 : (parseInt(params.get('page')) > 0 ? parseInt(params.get('page')) : 1 ));
    const ACCION = "LISTAR COMPROBANTES";
    var datos = { pagina : pagina , total : CANTIDAD_REGISTROS };
    $.ajax({
        data: datos,
        url: 'services/listar_comprobantes.php',
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                $('#tbl_comprobantes').bootstrapTable({
                    search: true,
                    searchAlign: "left",
                    showPagination: "true",
                    editable: "true",
                    columns: [{
                        field: 'numero',
                        title: 'NÚMERO',
                        align: 'center',
                    }, {
                        field: 'tipo',
                        title: 'TIPO',
                        align: 'center',
                    }, {
                        field: 'fecha',
                        title: 'FECHA',
                        align: 'center',
                    }, {
                        field: 'glosa',
                        title: 'GLOSA',
                    }, {
                        field: 'actions',
                        title: 'ACCIONES',
                        align: 'center',
                        clickToSelect: false,
                        events: window.operateEvents,
                        formatter: operateFormatter
                    }],
                    data: response.data.comprobantes/*,
                    onDblClickRow: test*/
                });
                //start_pagination( pagina , response.data.total );
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
}

function operateFormatter(value, row, index) {
    if(row.nivel == 'G'){
        return [
            ''
        ].join('');
    }else{
        return [
            '<a class="edit" href="javascript:void(0)" title="Editar">',
            '<i class="bi bi-pencil-fill text-primary"></i>',
            '</a>  ',
            '<a class="remove" href="javascript:void(0)" title="Eliminar">',
            '<i class="bi bi-trash-fill text-danger"></i>',
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
        eliminar_cuenta(row.idCuenta,row.descripcion.replaceAll("&nbsp;", ''));
      /*$table.bootstrapTable('remove', {
        field: 'id',
        values: [row.id]
      })*/
    }
}

$('#modal_lista_cuentas').on('show.bs.modal', () => {
    console.log("asd");
});

function listar_cuentas(){
    const ACCION = "LISTAR CUENTAS";
    var datos = { };
    $.ajax({
        data: datos,
        url: 'services/listar_cuentas.php',
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                console.log(response);
                response.data.forEach( (cuenta) => {
                    var sp = "&nbsp";
                    switch(cuenta.nivel){
                        case 'G':
                            sp = '';
                            break;
                        case 'R':
                            sp = sp.repeat(4);
                            break;
                        case 'T':
                            sp = sp.repeat(8);
                            break;
                        case 'C':
                            sp = sp.repeat(12);
                            break;
                        case 'S':
                            sp = sp.repeat(16);
                            break;
                    };
                    cuenta.descripcion = sp + cuenta.descripcion;
                });
                $('#t_cuentas').bootstrapTable({
                    columns: [{
                      field: 'codigo',
                      title: 'Codigo'
                    }, {
                      field: 'descripcion',
                      title: 'Descripción'
                    }],
                    data: response.data,
                    onDblClickRow: test
                });
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
}

function test(e){
    console.log(e);
}