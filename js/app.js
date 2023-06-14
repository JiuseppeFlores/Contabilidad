$('#nav_cerrar_sesion').on('click',() => {
    const ACCION = "CERRAR SESIÓN";
    var datos = {};
    $.ajax({
        data: datos,
        url: '../php/services/cerrar_sesion.php',
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                window.location.href = "../index.php";
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