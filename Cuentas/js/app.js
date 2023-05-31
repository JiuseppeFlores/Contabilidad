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
            }
            $('#modal_adicionar').modal('hide');
            alerta(
                'alerta',
                'titulo_mensaje',
                'cuerpo_mensaje',
                (response.success ? 'alert-success': 'alert-danger'),
                ACCION,
                response.message
            );
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            $('#modal_adicionar').modal('hide');
            alerta('alerta','titulo_mensaje','cuerpo_mensaje','alert-danger',ACCION,error.statusText);
            console.log("["+ACCION+"] "+error.statusText);
        }
    });
});

$('#modal_adicionar').on('hide.bs.modal', () => {
    $('#form_adicionar_cuenta').trigger("reset");
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
                response.data.forEach( (cuenta) => {
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
            }else{
                alerta('alerta','titulo_mensaje','cuerpo_mensaje','alert-danger',ACCION,response.message);
            }
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            alerta('alerta','titulo_mensaje','cuerpo_mensaje','alert-danger',ACCION,error.statusText);
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
                alerta('alerta','titulo_mensaje','cuerpo_mensaje','alert-danger',ACCION,response.message);
            }
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            alerta('alerta','titulo_mensaje','cuerpo_mensaje','alert-danger',ACCION,error.statusText);
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
            listar_cuentas();
            $('#modal_actualizar').modal('hide');
            alerta(
                'alerta',
                'titulo_mensaje',
                'cuerpo_mensaje',
                (response.success ? 'alert-success' : 'alert-danger'),
                ACCION,
                response.message
            );
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            alerta('alerta','titulo_mensaje','cuerpo_mensaje','alert-danger',ACCION,error.statusText);
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
            listar_cuentas();
            $('#modal_eliminar').modal('hide');
            alerta(
                'alerta',
                'titulo_mensaje',
                'cuerpo_mensaje',
                (response.success ? 'alert-success' : 'alert-danger'),
                ACCION,
                response.message
            );
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            alerta('alerta','titulo_mensaje','cuerpo_mensaje','alert-danger',ACCION,error.statusText);
            console.log("["+ACCION+"] "+error.statusText);
        }
    });
});