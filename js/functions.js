function obtenerFecha(tipo){
    const fecha = new Date();
    var year = fecha.getFullYear();
    var month = (fecha.getMonth() + 1) < 10 ? '0'+(fecha.getMonth() + 1) : (fecha.getMonth() + 1);
    var day = (fecha.getDate() < 10 ? '0'+fecha.getDate() : fecha.getDate());
    if(tipo == 'm'){
        return year+"-"+month;
    }else{
        return year+"-"+month+"-"+day;
    }
}

function obtenerUltimoDia(fecha){
    var datos = fecha.split("-");
    var dia = new Date(parseInt(datos[0]) , (parseInt(datos[1])), 0).getDate()
    return( dia < 10 ? "0"+dia : dia+"");
}

function cerrarSesion(){
    const ACCION = "CERRAR SESIÓN";
    var datos = {};
    $.ajax({
        data: datos,
        url: 'php/services/cerrar_sesion.php',
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                window.location.href = "InicioSesion/index.php";
            }
            show_toast(ACCION,response.message,response.success?'text-bg-success':'text-bg-warning');
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            show_toast(ACCION,error.statusText,'text-bg-danger');
            console.log("["+ACCION+"] "+error.statusText);
        }
    });
}