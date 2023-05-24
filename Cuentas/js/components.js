function alerta(id_alerta,id_titulo,id_cuerpo,clase,titulo,mensaje){
    $('#'+id_alerta).addClass(clase);
    $('#'+id_titulo).text(titulo);
    $('#'+id_cuerpo).text(mensaje);
    $('#'+id_alerta).removeClass('hide');
    $('#'+id_alerta).addClass('show');
    setTimeout(()=>{
        $('#'+id_titulo).text('');
        $('#'+id_cuerpo).text('');
        $('#'+id_alerta).removeClass('show');
        $('#'+id_alerta).addClass('hide');
        $('#'+id_alerta).removeClass(clase);
    }, 5000);
}