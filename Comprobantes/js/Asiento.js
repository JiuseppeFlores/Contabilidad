class Asiento{
    constructor(codigo_cuenta,nombre_cuenta,referencia,cc,debe,haber,banco,nro_cheque){
        this.codigo = codigo_cuenta;
        this.cuenta = nombre_cuenta;
        this.referencia = referencia;
        this.cc = cc;
        this.debe = debe;
        this.haber = haber;
        this.banco = banco;
        this.cheque = nro_cheque;
    }
}

let ASIENTOS = [];
let CUENTAS = [];
get_counts();

function adicionar_asiento(){
    // Creacion de filas para la tabla
    var id = ASIENTOS.length + 1;
    const row = document.createElement('tr');
    const codigo = document.createElement('td');
    codigo.appendChild(create_select(id));
    const cuenta = document.createElement('td');
    cuenta.appendChild(create_span(id));
    const referencia = document.createElement('td');
    var ref = document.getElementById('comprobante_glosa').value;
    referencia.appendChild(create_input("asd-12","form-control",ref,"text","Lista de Ejemplo"));
    const cc = document.createElement('td');
    cc.appendChild(create_input("asd-12","form-control","","text","Lista de Ejemplo"));
    const debe = document.createElement('td');
    debe.appendChild(create_input("asd-12","form-control","","text","Lista de Ejemplo"));
    const haber = document.createElement('td');
    haber.appendChild(create_input("asd-12","form-control","","text","Lista de Ejemplo"));
    const banco = document.createElement('td');
    banco.appendChild(create_input("asd-12","form-control","","text","Lista de Ejemplo"));
    const cheque = document.createElement('td');
    cheque.appendChild(create_input("asd-12","form-control","","text","Lista de Ejemplo"));

    row.appendChild(codigo);
    row.appendChild(cuenta);
    row.appendChild(referencia);
    row.appendChild(cc);
    row.appendChild(debe);
    row.appendChild(haber);
    row.appendChild(banco);
    row.appendChild(cheque);
    document.getElementById('asientos').appendChild(row);

    $('#sl-'+id).on('change',function(e){
        console.log(CUENTAS);
        CUENTAS.forEach( (cuenta) => {
            if(cuenta.id == $(this).val()){
                $('#sp-'+id).text(cuenta.cuenta);
            }
        });
    });

    ASIENTOS.push(new Asiento());
}

