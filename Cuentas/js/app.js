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
        dataType: 'TEXT',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            console.log(response);
            /*if(response.success){
                listar_cuentas();
                $('#modal_adicionar').modal('hide');
            }
            show_toast(ACCION,response.message,response.success?'text-bg-success':'text-bg-warning');
            console.log("["+ACCION+"] "+response.message);*/
        },
        error: function(error){
            show_toast(ACCION,error.statusText,'text-bg-danger');
            console.log("["+ACCION+"] "+error.statusText);
        }
    });
});

$('#modal_adicionar').on('show.bs.modal', () => {
    cambio_nivel();
});

$('#modal_adicionar').on('hide.bs.modal', () => {
    $('#form_adicionar_cuenta').trigger("reset");
    $('#lista_grupo').html("");
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
    let params = new URLSearchParams(location.search);
    var pagina = (params.get('page') == null ? 1 : (parseInt(params.get('page')) > 0 ? parseInt(params.get('page')) : 1 ));
    const ACCION = "LISTAR CUENTAS";
    var datos = { pagina : pagina , total : CANTIDAD_REGISTROS };
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
                document.getElementById('cuentas').innerHTML = "";;
                response.data.accounts.forEach( (cuenta) => {
                    // Creacion de filas para la tabla
                    const row = document.createElement('tr');
                    const id = document.createElement('td');
                    id.innerHTML = cuenta.idCuenta;
                    const codigo = document.createElement('td');
                    codigo.innerHTML = cuenta.codigo;
                    const descripcion = document.createElement('td');
                    descripcion.innerHTML = cuenta.descripcion;
                    const grupo = document.createElement('td');
                    grupo.innerHTML = cuenta.grupo;
                    const actions = document.createElement('td');
                    actions.classList = "text-center";
                    actions.innerHTML = `
                        <button class="btn btn-warning" onclick="editar_cuenta(`+cuenta.idCuenta+`)" title="Actualizar">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger" onclick="eliminar_cuenta(`+cuenta.idCuenta+`,'`+cuenta.descripcion+`')" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                    row.appendChild(id);
                    row.appendChild(codigo);
                    row.appendChild(descripcion);
                    row.appendChild(grupo);
                    row.appendChild(actions);
                    document.getElementById('cuentas').appendChild(row);
                });
                start_pagination( pagina , response.data.total );
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
                $('#modal_actualizar').modal('show');
                $('#id_cuenta').text(response.data.idCuenta);
                $('#cuenta_editar_id').val(response.data.idCuenta);
                $('#cuenta_editar_codigo').val(response.data.codigo);
                $('#cuenta_editar_descripcion').val(response.data.descripcion);
                $('#cuenta_editar_grupo').val(response.data.grupo);
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

function cambio_nivel(){
    var nivel = document.querySelector('input[name="nivel"]:checked').value;
    $('#lista_grupo').html('');
    $('#lista_rubro').html('');
    $('#lista_titulo').html('');
    $('#lista_compuesta').html('');
    $('#cuenta_codigo_cuenta').val("");
    $('#cuenta_adicionar_codigo').val("");
    listar_grupos();
    switch(nivel){
        case 'R':
            $('#lista_grupo').html(crear_select('cuenta_grupos','grupo',cambio_grupo));
            $('#lista_rubro').html('');
            $('#lista_titulo').html('');
            $('#lista_compuesta').html('');
            break;
        case 'T':
            $('#lista_grupo').html(crear_select('cuenta_grupos','grupo',cambio_grupo));
            $('#lista_rubro').html(crear_select('cuenta_rubros','rubro',cambio_rubro));
            $('#lista_titulo').html('');
            $('#lista_compuesta').html('');
            break;
        case 'C':
            $('#lista_grupo').html(crear_select('cuenta_grupos','grupo',cambio_grupo));
            $('#lista_rubro').html(crear_select('cuenta_rubros','rubro',cambio_grupo));
            $('#lista_titulo').html(crear_select('cuenta_titulos','titulo',cambio_grupo));
            $('#lista_compuesta').html('');
            break;
        case 'S':
            $('#lista_grupo').html(crear_select('cuenta_grupos','grupo',cambio_grupo));
            $('#lista_rubro').html(crear_select('cuenta_rubros','rubro',cambio_grupo));
            $('#lista_titulo').html(crear_select('cuenta_titulos','titulo',cambio_grupo));
            $('#lista_compuesta').html(crear_select('cuenta_compuestas','compuesta',cambio_grupo));
            break;
    }
    console.log(nivel);
}

const cambio_grupo = (e) => {
    var grupo = $('#cuenta_grupos').val();
    $('#cuenta_codigo_cuenta').val(grupo);
    $('#cuenta_adicionar_codigo').val("");
    var nivel = document.querySelector('input[name="nivel"]:checked').value;
    if(nivel != 'R'){
        const opt = document.getElementById('cuenta_rubros').firstElementChild;
        $('#cuenta_rubros').html(opt);
        listar_rubros(grupo);
    }
}

const cambio_rubro = () => {
    var grupo = $('#cuenta_grupos').val();
    var rubro = $('#cuenta_rubros').val();
    $('#cuenta_codigo_cuenta').val(grupo+rubro);
    $('#cuenta_adicionar_codigo').val("");
}

function listar_grupos(){
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
                const select = document.getElementById('cuenta_grupos');
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

function listar_rubros(id_grupo){
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
                const select = document.getElementById('cuenta_rubros');
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