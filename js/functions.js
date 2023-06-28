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