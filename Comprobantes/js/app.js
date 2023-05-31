// MARCANDO EL NAV-ITEM CORRESPONDIENTE
$('#nav_asientos').addClass('active');
// INICIALIZANDO EL LISTADO DE COMPROBANTES
listar_comprobantes();

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
                document.getElementById('lista_comprobantes').innerHTML = "";
                response.data.vouchers.forEach( (comprobante) => {
                    // Creacion de filas para la tabla
                    const row = document.createElement('tr');
                    const id = document.createElement('td');
                    id.innerHTML = comprobante.idComprobante;
                    const numero = document.createElement('td');
                    numero.innerHTML = comprobante.numero;
                    const tipo = document.createElement('td');
                    tipo.innerHTML = comprobante.tipo;
                    const fecha = document.createElement('td');
                    fecha.innerHTML = comprobante.fecha.date.split(' ')[0];
                    const moneda = document.createElement('td');
                    moneda.innerHTML = comprobante.moneda;
                    const actions = document.createElement('td');
                    actions.classList = "text-center";
                    actions.innerHTML = `
                        <button class="btn btn-warning" onclick="editar_cuenta(`+comprobante.idComprobante+`)" title="Actualizar">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger" onclick="eliminar_cuenta(`+comprobante.idComprobante+`)" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                    row.appendChild(id);
                    row.appendChild(numero);
                    row.appendChild(tipo);
                    row.appendChild(fecha);
                    row.appendChild(moneda);
                    row.appendChild(actions);
                    document.getElementById('lista_comprobantes').appendChild(row);
                });
                start_pagination( pagina , response.data.total );
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