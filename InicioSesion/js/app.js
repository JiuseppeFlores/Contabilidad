$('#form_iniciar_sesion').on('submit', (e) => {
    e.preventDefault();
    const ACCION = "INICIAR SESIÓN";
    var datos = $('#form_iniciar_sesion').serialize();
    $.ajax({
        data: datos,
        url: 'services/iniciar_sesion.php',
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                listar_cuentas();
                $('#modal_adicionar').modal('hide');
            }
            show_toast(ACCION,response.message,response.success?'text-bg-success':'text-bg-warning');
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            show_toast(ACCION,error.statusText,'text-bg-danger');
            console.log("["+ACCION+"] "+error.statusText);
        }
    });
})