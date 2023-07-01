//$(document).ready(function(){
    var printIcon = function(cell, formatterParams){ //plain text value
        var id = cell._cell.row.data.id;
        return `
            <a class="remove" href="javascript:void(0)" onclick="removerAsiento(${id})" title="Eliminar">
                <i class="bi bi-dash-circle-fill text-danger fs-5"></i>
            </a>
        `;
    };

    var tabledata = [
        {id:1, name:"Billy Bob", age:12, gender:"male", height:95, col:"red", dob:"14/05/2010"},
        {id:2, name:"Jenny Jane", age:42, gender:"female", height:142, col:"blue", dob:"30/07/1954"},
        {id:3, name:"Steve McAlistaire", age:35, gender:"male", height:176, col:"green", dob:"04/11/1982"},
    ];
    var table = new Tabulator("#tbl_asientos", {
        layout: "fitColumns",
        responsiveLayout: "collapse",
        cssClass: "table-striped",
        columns: [
            {
                title:"", 
                columns:[
                    {title:"Código", field:"codigo", validator:"required", headerSort:false},
                    {title:"Cuenta", field:"descripcion",  validator:"required", headerSort:false},
                    {title:"Referencia", field:"referencia", editor:true, headerSort:false}
                ]
            },{
                title:"Bolivianos (Bs)",
                columns:[
                    {title:"Debe", field:"debe_b", editor:"number", hozAlign:"right", validator:"required", headerSort:false, formatter:"money", formatterParams:{
                        decimal:".",
                        thousand:",",
                        negativeSign:true
                    }},
                    {title:"Haber", field:"haber_b", editor:"number", hozAlign:"right", validator:"required", headerSort:false, formatter:"money", formatterParams:{
                        decimal:".",
                        thousand:",",
                        negativeSign:true
                    }}
                ]
            },{
                title:"Dolares ($us)",
                columns:[
                    {title:"Debe", field:"debe_s", validator:"required", hozAlign:"right", headerSort:false, formatter:"money", formatterParams:{
                        decimal:".",
                        thousand:",",
                        negativeSign:true
                    }},
                    {title:"Haber", field:"haber_s", validator:"required", hozAlign:"right", headerSort:false, formatter:"money", formatterParams:{
                        decimal:".",
                        thousand:",",
                        negativeSign:true
                    }}
                ]
            },{
                title:"",
                columns:[
                    {title:"Banco", field:"banco", editor:true, headerSort:false},
                    {title:"Cheque", field:"cehque", editor:true, headerSort:false},
                    {title:"Acciones", field:"acciones", headerSort:false, formatter:printIcon, hozAlign:"center"}
                ]
            }
        ]
    });

    $('#t_cuentas').bootstrapTable({
        search: true,
        searchAlign: "left",
        rowStyle: rowStyle,
        columns: [{
          field: 'codigo',
          title: 'Codigo'
        }, {
          field: 'descripcion',
          title: 'Descripción'
        }],
        data: [],
        onDblClickRow: seleccionarCuenta
    });
//});

function removerAsiento(id){
    table.deleteRow(id);
}

$('#form_registro_comprobante').on('submit',(e) => {
    e.preventDefault();
    var valid = table.validate();
    if(valid){
        console.log("FORM VALIDATE");
    }
});

$('#btn_adicionar_asiento').on('click', () => {
    var max = 0;
    table.getData().forEach((row) => {
        max = row.id > max ? row.id :max;
    });
    var data = {
        id : parseInt(max) + 1,
        referencia: $('#comprobante_glosa').val()
    }
    table.addRow(data);
    console.log(max);
});

table.on("cellClick", function(e, cell){
    var field = cell._cell.column.definition.field;
    var id = cell._cell.row.data.id;
    document.getElementById('')
    if( field == 'codigo' || field == 'descripcion' ){
        $('#t_cuentas').bootstrapTable('removeAll');
        $('#modal_lista_cuentas').attr('id-asiento',id);
        $('#modal_lista_cuentas').modal('show');
        listarCuentas();
    }
});

function seleccionarCuenta(e){
    if(e.movimiento == 1){
        console.log("Valido");
        var id = $('#modal_lista_cuentas').attr('id-asiento');
        table.updateData([
            {
                id : id,
                codigo : e.codigo, 
                descripcion : e.descripcion.replaceAll("&nbsp;", '')
            }
        ]);
        $('#modal_lista_cuentas').removeAttr('id-asiento');
        $('#modal_lista_cuentas').modal('hide');
    }else{
        console.log("NO VALIDO");
        alert("La cuenta seleccionada no tiene movimientos.");
    }
}

$('#modal_lista_cuentas').on('hide.bs.modal',() => {
    $('#modal_lista_cuentas').removeAttr('id-asiento');
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

function listarCuentas(){
    const ACCION = "LISTAR CUENTAS";
    var datos = { };
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
                response.data.forEach( (cuenta) => {
                    var sp = "&nbsp;";
                    switch(cuenta.nivel){
                        case 'G':sp = '';break;
                        case 'R':sp = sp.repeat(4);break;
                        case 'T':sp = sp.repeat(8);break;
                        case 'C':sp = sp.repeat(12);break;
                        case 'S':sp = sp.repeat(16);break;
                        case 'A':sp = sp.repeat(20);break;
                    };
                    cuenta.descripcion = sp + cuenta.descripcion;
                });
                $('#t_cuentas').bootstrapTable('load', response.data);
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

/*const ACCION = "OBTENER NRO. COMPROBANTE";
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
                data: [],
                onDblClickRow: test
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
    
};*/
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
/*$('#btn_adicionar_asiento').on('click',() =>{
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
  }*/