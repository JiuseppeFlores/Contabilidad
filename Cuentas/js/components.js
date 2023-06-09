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

function crear_select(id,name,change,mode){
    // Creacion de Div contenedor de select
    const div = document.createElement('div');
    div.classList = "form-floating mb-3";
    // Creacion de Select
    const select = document.createElement('select');
    select.classList = "form-select";
    select.name = name;
    select.required = 'true';
    select.id = id;
    select.dataset.mode = mode;
    select.onchange = change;
    // Creación de Options
    const title = name.substring(0,1).toUpperCase() + name.substring(1,name.length);
    const opt = document.createElement('option');
    opt.value = "";
    opt.innerHTML = " - Seleccione " + title + " - ";
    opt.disabled = 'true';
    opt.selected = 'true';
    select.appendChild(opt);
    select.required = true;

    /*options.forEach( (option) => {
        const opt = document.createElement('option');
        opt.value = option.codigo;
        opt.innerHTML = option.descripcion;
        select.appendChild(opt);
    });*/
    // Creacion de Label
    const label = document.createElement('label');
    label.textContent = title;
    label.htmlFor = "cuenta_"+name;

    div.appendChild(select);
    div.appendChild(label);

    return div;
}