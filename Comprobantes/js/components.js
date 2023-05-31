function create_input(id,clss,val,tp,name){
    const div = document.createElement('div');
    div.classList = "form-outline";
    const input = document.createElement('input');
    input.type = tp;
    input.id = id;
    input.classList = "form-control";
    /*const label = document.createElement('label');
    label.classList = "form-label";
    label.innerText = name;
    label.htmlFor = id;*/

    const div2 = document.createElement('div');
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

    div.appendChild(input);
    //div.appendChild(label);
    div.appendChild(div2);

    return div;
}

function create_select(id){
    const select = document.createElement('select');
    select.classList = "form-select";
    select.id = "sl-"+id;
    select.name = "sl-"+id;

    let options = CUENTAS;

    options.forEach( (option) => {
        const opt = document.createElement('option');
        opt.value = option.idCuenta;
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