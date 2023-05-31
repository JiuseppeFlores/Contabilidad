function get_date(){
    const fecha = new Date();
    var year = fecha.getFullYear();
    var month = (fecha.getMonth() + 1) < 10 ? '0'+(fecha.getMonth() + 1) : (fecha.getMonth() + 1);
    var day = (fecha.getDate() < 10 ? '0'+fecha.getDate() : fecha.getDate());
    return year+"-"+month+"-"+day;
}