const listarCuentas = (e) => {
    console.log(e)
    var id = e.target.id.split('-')[1];
    console.log(id);
    //$('#t_cuentas').bootstrapTable('removeAll');
    //$('#modal_lista_cuentas').attr('id-asiento',id);
    $('#modal_lista_cuentas').attr('data-id-asiento', id);
    $('#modal_lista_cuentas').modal('show');
    seleccionarCuenta();
};
function create_input(id,clss,val,tp,name,sw){
    const div = document.createElement('div');
    div.classList = "form-outline";
    const input = document.createElement('input');
    input.type = tp;
    input.id = id;
    input.name = name;
    input.classList = "form-control";
    input.value = val;
    if(sw == undefined){
        input.onblur = calcular_totales;
    }else{
        input.placeholder = "Seleccione Cuenta";
        input.autocomplete = "off";
        input.onclick = listarCuentas;
        input.required = "true";
    }
    /*const label = document.createElement('label');
    label.classList = "form-label";
    label.innerText = name;
    label.htmlFor = id;*/

    /*const div2 = document.createElement('div');
    div2.classList = "form-notch";

    const div3 = document.createElement('div');
    div3.classList = "form-notch-leading";
    const div4 = document.createElement('div');
    div4.classList = "form-notch-middle";
    const div5 = document.createElement('div');
    div5.classList = "form-notch-trailing";

    div2.appendChild(div3);
    div2.appendChild(div4);
    div2.appendChild(div5);

    div.appendChild(input);*/
    //div.appendChild(label);
    div.appendChild(input);

    return div;
}

function create_select(name,id){
    const select = document.createElement('select');
    select.classList = "form-select";
    select.id = "sl-"+id;
    select.name = name;
    //select.dataset.dropdownParent = "#modal_registrar_comprobante";

    let options = CUENTAS;
    
    const opt = document.createElement('option');
    opt.value = "";
    opt.innerHTML = " - Seleccione cuenta - ";
    select.appendChild(opt);
    select.required = true;

    options.forEach( (option) => {
        const opt = document.createElement('option');
        opt.value = option.id;
        opt.innerHTML = option.codigo+" | "+option.cuenta;
        select.appendChild(opt);
    });

    return select;
}

function create_span(id){
    const span = document.createElement('span');
    span.innerText = "";
    span.id="sp-"+id;
    return span;
}