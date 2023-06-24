// MARCANDO EL NAV-ITEM CORRESPONDIENTE
$('#nav_cuentas').addClass('active');
// INICIALIZANDO EL LISTADO DE CUENTAS
listar_cuentas();
// ADICIONAR CUENTA NUEVA
$('#form_adicionar_cuenta').on('submit', function(e){
    e.preventDefault();
    const ACCION = "ADICIONAR CUENTA";
    var datos = $('#form_adicionar_cuenta').serialize();
    $.ajax({
        data: datos,
        url: 'services/adicionar_cuenta.php',
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                listar_cuentas();
                //$('#modal_adicionar').modal('hide');
            }
            show_toast(ACCION,response.message,response.success?'text-bg-success':'text-bg-warning');
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            show_toast(ACCION,error.statusText,'text-bg-danger');
            console.log("["+ACCION+"] "+error.statusText);
        }
    });
});

$('#modal_adicionar').on('show.bs.modal', () => {
    $("#ca_rubro").select2({
        theme: "bootstrap-5"
    });
    cambio_nivel('ca');
});

$('#modal_adicionar').on('hide.bs.modal', () => {
    $('#form_adicionar_cuenta').trigger("reset");
    $('#lista_grupo').html("");
});

$('#modal_actualizar').on('show.bs.modal', () => {
    cambio_nivel('ce');
});

$('#modal_actualizar').on('hide.bs.modal', () => {
    $('#id_cuenta').text("");
    $('#cuenta_id').val("");
    $('#form_actualizar_cuenta').trigger("reset");
});

$('#modal_eliminar').on('hide.bs.modal', () => {
    $('#eliminar_descripcion').val("");
    $('#eliminar_id').val("");
});

function listar_cuentas(){
    const ACCION = "LISTAR CUENTAS";
    var datos = {  };
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
                document.getElementById('tbl_cuentas').innerHTML = "";
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
                $('#tbl_cuentas').bootstrapTable({
                    search: true,
                    searchAlign: "left",
                    rowStyle: rowStyle,
                    columns: [{
                      field: 'codigo',
                      title: 'CÓDIGO',
                    }, {
                      field: 'descripcion',
                      title: 'DESCRIPCIÓN',
                    }, {
                        field: 'movimiento',
                        title: 'MOVIMIENTO',
                        align: 'center',
                    }, {
                        field: 'operate',
                        title: 'ACCIONES',
                        align: 'center',
                        clickToSelect: false,
                        events: window.operateEvents,
                        formatter: operateFormatter
                    }],
                    data: response.data/*,
                    onDblClickRow: test*/
                });
            }else{
                show_toast(ACCION,response.message,response.success?'text-bg-success':'text-bg-warning');
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

function rowStyle(row, index) {
    var classes = [
      'fw-semibold text-muted',
      'text-primary',
    ]

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

function editar_cuenta(id_cuenta){
    const ACCION = "EDITAR CUENTA";
    var datos = { id_cuenta : id_cuenta };
    $.ajax({
        data: datos,
        url: 'services/obtener_cuenta.php',
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                cuenta = response.data;
                $('#modal_actualizar').modal('show');
                document.querySelectorAll(`input[name="ce_nivel"]`).forEach(element => {
                    if(element.value === cuenta.nivel) {
                        element.checked = true;
                    }
                });
                cambio_nivel('ce');
                $('#id_cuenta').text(response.data.codigo);
                $('#ce_id').val(response.data.idCuenta);
                $('#ce_codigo_cuenta').val(response.data.codigo);
                var codigo = "";
                var modo = 'ce';
                switch(cuenta.nivel){
                    case 'G':
                        codigo = cuenta.grupo;
                        break;
                    case 'R':
                        codigo = cuenta.rubro;
                        break;
                    case 'T':
                        codigo = cuenta.titulo;
                        break;
                    case 'C':
                        codigo = cuenta.compuesta;
                        break;
                    case 'S':
                        codigo = cuenta.subcuenta;
                        break;
                }
                setTimeout(()=>{
                    $('#ce_grupos').val(cuenta.grupo);
                    $('#ce_grupos').trigger('change');
                    setTimeout(()=>{
                        $('#ce_rubros').val(cuenta.rubro);
                        $('#ce_rubros').trigger('change');
                        setTimeout(()=>{
                            $('#ce_titulos').val(cuenta.titulo);
                            $('#ce_titulos').trigger('change');
                            setTimeout(()=>{
                                $('#ce_compuestas').val(cuenta.compuesta);
                                $('#ce_compuestas').trigger('change');
                                setTimeout(()=>{
                                    $('#ce_codigo').val(codigo);
                                    generar_codigo('ce');
                                },200);
                            },200);
                        },200);
                    },200);
                },200);
                $('#ce_descripcion').val(response.data.descripcion);
            }else{
                show_toast(ACCION,response.message,'text-bg-warning');
            }
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            show_toast(ACCION,error.statusText,'text-bg-danger');
            console.log("["+ACCION+"] "+error.statusText);
        }
    });
}

$('#form_actualizar_cuenta').on('submit', function(e){
    e.preventDefault();
    const ACCION = "ACTUALIZAR CUENTA";
    var datos = $('#form_actualizar_cuenta').serialize();
    $.ajax({
        data: datos,
        url: 'services/actualizar_cuenta.php',
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                listar_cuentas();
                $('#modal_actualizar').modal('hide');
            }
            show_toast(ACCION,response.message,response.success?'text-bg-success':'text-bg-danger');
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            show_toast(ACCION,error.statusText,'text-bg-danger');
            console.log("["+ACCION+"] "+error.statusText);
        }
    });
});

function eliminar_cuenta(id_cuenta, descripcion){
    $('#modal_eliminar').modal('show');
    $('#eliminar_id').val(id_cuenta);
    $('#eliminar_descripcion').text(descripcion);
}

$('#form_eliminar_cuenta').on('submit', function(e){
    e.preventDefault();
    const ACCION = "ELIMINAR CUENTA";
    var datos = $('#form_eliminar_cuenta').serialize();
    $.ajax({
        data: datos,
        url: 'services/eliminar_cuenta.php',
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                listar_cuentas();
                $('#modal_eliminar').modal('hide');
            }
            show_toast(ACCION,response.message,response.success?'text-bg-success':'text-bg-warning');
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            show_toast(ACCION,error.statusText,'text-bg-danger');
            console.log("["+ACCION+"] "+error.statusText);
        }
    });
});

function cambio_nivel(modo){
    var nivel = document.querySelector('input[name="' + modo + '_nivel"]:checked').value;
    var text_nivel = "";
    var id_grupo = '#'+modo+'_lista_grupo';
    var id_rubro = '#'+modo+'_lista_rubro';
    var id_titulo = '#'+modo+'_lista_titulo';
    var id_compuesta = '#'+modo+'_lista_compuesta';
    $(id_grupo).html('');
    $(id_rubro).html('');
    $(id_titulo).html('');
    $(id_compuesta).html('');
    $('#' + modo + "_codigo_cuenta").val("");
    $('#' + modo + '_codigo').val("");
    var id_sl_grupos = modo + "_grupos";
    var id_sl_rubros = modo + "_rubros";
    var id_sl_titulos = modo + "_titulos";
    var id_sl_compuestas = modo + "_compuestas";
    switch(nivel){
        case 'R':
            document.getElementById(modo + '_codigo').maxLength = 1;
            $(id_grupo).html(crear_select(id_sl_grupos,'grupo',cambio_grupo,modo));
            text_nivel = "Rubro";
            break;
        case 'T':
            document.getElementById(modo + '_codigo').maxLength = 2;
            $(id_grupo).html(crear_select(id_sl_grupos,'grupo',cambio_grupo,modo));
            $(id_rubro).html(crear_select(id_sl_rubros,'rubro',cambio_rubro,modo));
            text_nivel = "Título";
            break;
        case 'C':
            document.getElementById(modo + '_codigo').maxLength = 2;
            $(id_grupo).html(crear_select(id_sl_grupos,'grupo',cambio_grupo,modo));
            $(id_rubro).html(crear_select(id_sl_rubros,'rubro',cambio_rubro,modo));
            $(id_titulo).html(crear_select(id_sl_titulos,'titulo',cambio_titulo,modo));
            text_nivel = "Compuesta";
            break;
        case 'S':
            document.getElementById(modo + '_codigo').maxLength = 2;
            $(id_grupo).html(crear_select(id_sl_grupos,'grupo',cambio_grupo,modo));
            $(id_rubro).html(crear_select(id_sl_rubros,'rubro',cambio_rubro,modo));
            $(id_titulo).html(crear_select(id_sl_titulos,'titulo',cambio_titulo,modo));
            $(id_compuesta).html(crear_select(id_sl_compuestas,'compuesta',cambio_compuesta,modo));
            text_nivel = "Sub Cuenta";
            break;
    }
    listar_grupos(id_sl_grupos);
    document.getElementById('label_' + modo + '_codigo').innerHTML = "Código " + text_nivel;
}

const cambio_grupo = (e) => {
    var modo = e.target.dataset.mode;
    var id_grupos = modo + "_grupos";
    var grupo = $('#' + id_grupos).val();
    $('#' + modo + '_codigo_cuenta').val(grupo);
    $('#' + modo + '_codigo').val("");
    var nivel = document.querySelector('input[name="' + modo + '_nivel"]:checked').value;
    if(nivel != 'R'){
        var id_rubros = modo + "_rubros";
        document.getElementById(id_rubros).value = "";
        const opt = document.getElementById(id_rubros).firstElementChild;
        $('#'+id_rubros).html(opt);
        listar_rubros(id_rubros,grupo);
    }
}

const cambio_rubro = (e) => {
    var modo = e.target.dataset.mode;
    var id_grupos = modo + "_grupos";
    var id_rubros = modo + "_rubros";
    var grupo = $("#" + id_grupos).val();
    var rubro = $("#" + id_rubros).val();
    $('#' + modo + '_codigo_cuenta').val(grupo + rubro);
    $('#' + modo + '_codigo').val("");

    var nivel = document.querySelector('input[name="' + modo + '_nivel"]:checked').value;
    if(nivel != 'R' && nivel != 'T'){
        var id_titulos = modo + "_titulos";
        document.getElementById(id_titulos).value = "";
        const opt = document.getElementById(id_titulos).firstElementChild;
        $('#' + id_titulos).html(opt);
        listar_titulos(id_titulos,grupo,rubro);
    }
}

const cambio_titulo = (e) => {
    var modo = e.target.dataset.mode;
    var id_grupos = modo + "_grupos";
    var id_rubros = modo + "_rubros";
    var id_titulos = modo + "_titulos";

    var grupo = $('#' + id_grupos).val();
    var rubro = $('#' + id_rubros).val();
    var titulo = $('#' + id_titulos).val();
    $('#' + modo + '_codigo_cuenta').val(grupo + rubro + titulo);
    $('#' + modo + '_codigo').val("");

    var nivel = document.querySelector('input[name="' + modo + '_nivel"]:checked').value;
    if(nivel != 'R' && nivel != 'T' && nivel != 'C'){
        var id_compuestas = modo + "_compuestas";
        document.getElementById(id_compuestas).value = "";
        const opt = document.getElementById(id_compuestas).firstElementChild;
        $('#' + id_compuestas).html(opt);
        listar_compuestas(id_compuestas,grupo,rubro,titulo);
    }
}

const cambio_compuesta = (e) => {
    var modo = e.target.dataset.mode;
    var id_grupos = modo + "_grupos";
    var id_rubros = modo + "_rubros";
    var id_titulos = modo + "_titulos";
    var id_compuestas = modo + "_compuestas";

    var grupo = $('#' + id_grupos).val();
    var rubro = $('#' + id_rubros).val();
    var titulo = $('#' + id_titulos).val();
    var compuesta = $('#' + id_compuestas).val();

    $('#' + modo + '_codigo_cuenta').val(grupo + rubro + titulo + compuesta);
    $('#' + modo + '_codigo').val("");
}

function generar_codigo(modo){
    var grupo = $('#' + modo + '_grupos').val();
    grupo = grupo == undefined ? '' : grupo;
    var rubro = $('#' + modo + '_rubros').val();
    rubro = rubro == undefined ? '' : rubro;
    var titulo = $('#' + modo + '_titulos').val();
    titulo = titulo == undefined ? '' : titulo;
    var compuesta = $('#' + modo + '_compuestas').val();
    compuesta = compuesta == undefined ? '' : compuesta;

    var id = $('#' + modo + '_codigo').val();
    id = parseInt(id) ? id : '0';
    var nivel = document.querySelector('input[name="' + modo + '_nivel"]:checked').value;
    switch(nivel){
        case 'T':
            id = id.length == 1 ? '0' + id : id;
            break;
        case 'C':
            id = id.length == 1 ? '0' + id : id;
            break;
        case 'S':
            id = id.length == 1 ? '0' + id : id;
            break;
    }
    document.getElementById(modo + '_codigo').value = id;
    $('#' + modo + '_codigo_cuenta').val(grupo + rubro + titulo + compuesta + id);
    
}

function listar_grupos(id_select){
    const ACCION = "LISTAR GRUPOS";
    var datos = {};
    $.ajax({
        data: datos,
        url: 'services/listar_grupos.php',
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                const select = document.getElementById(id_select);
                response.data.forEach( (option) => {
                    const opt = document.createElement('option');
                    opt.value = option.grupo;
                    opt.innerHTML = option.descripcion;
                    select.appendChild(opt);
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

function listar_rubros(id_select,id_grupo){
    const ACCION = "LISTAR RUBROS";
    var datos = {
        grupo: id_grupo
    };
    $.ajax({
        data: datos,
        url: 'services/listar_rubros.php',
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                const select = document.getElementById(id_select);
                response.data.forEach( (option) => {
                    const opt = document.createElement('option');
                    opt.value = option.rubro;
                    opt.innerHTML = option.descripcion;
                    select.appendChild(opt);
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

function listar_titulos(id_select,id_grupo,id_rubro){
    const ACCION = "LISTAR TITULOS";
    var datos = {
        grupo: id_grupo,
        rubro: id_rubro
    };
    $.ajax({
        data: datos,
        url: 'services/listar_titulos.php',
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                const select = document.getElementById(id_select);
                response.data.forEach( (option) => {
                    const opt = document.createElement('option');
                    opt.value = option.titulo;
                    opt.innerHTML = option.descripcion;
                    select.appendChild(opt);
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

function listar_compuestas(id_select,id_grupo,id_rubro,id_titulo){
    const ACCION = "LISTAR TITULOS";
    var datos = {
        grupo: id_grupo,
        rubro: id_rubro,
        titulo: id_titulo
    };
    $.ajax({
        data: datos,
        url: 'services/listar_compuestas.php',
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                const select = document.getElementById(id_select);
                response.data.forEach( (option) => {
                    const opt = document.createElement('option');
                    opt.value = option.compuesta;
                    opt.innerHTML = option.descripcion;
                    select.appendChild(opt);
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