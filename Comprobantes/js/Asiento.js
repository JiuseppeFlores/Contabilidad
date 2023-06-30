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
    codigo.appendChild(create_select('cuenta[]',id));
    const cuenta = document.createElement('td');
    cuenta.appendChild(create_span(id));
    const referencia = document.createElement('td');
    var ref = document.getElementById('comprobante_glosa').value;
    referencia.appendChild(create_input("referencia-"+id,"form-control",ref,"text","referencia[]"));
    /*const cc = document.createElement('td');
    cc.appendChild(create_input("cc-"+id,"form-control","","text","cc[]"));*/
    const debe = document.createElement('td');
    debe.appendChild(create_input("debe-"+id,"form-control","0","decimal","debe[]"));
    const haber = document.createElement('td');
    haber.appendChild(create_input("haber-"+id,"form-control","0","decimal","haber[]"));
    const debe_s = document.createElement('td');
    debe_s.id = "debe-"+id+"-s";
    debe_s.textContent = "0";
    const haber_s = document.createElement('td');
    haber_s.id = "haber-"+id+"-s";
    haber_s.textContent = "0";
    const banco = document.createElement('td');
    banco.appendChild(create_input("banco-"+id,"form-control","","text","banco[]"));
    const cheque = document.createElement('td');
    cheque.appendChild(create_input("cheque-"+id,"form-control","","text","cheque[]"));

    const iva = document.createElement('td');
    const a = document.createElement('a');
    a.dataset.bsToggle = "modal";
    a.href = "#modal_registrar_factura";
    a.setAttribute('role', 'button');
    a.title = "IVA";
    a.setAttribute("data-bs-target","#modal_registrar_factura");
    a.setAttribute('data-id', id-1);
    const i = document.createElement('i');
    i.classList = "bi bi-file-earmark-post-fill text-primary";
    a.appendChild(i);
    iva.appendChild(a);

    row.appendChild(codigo);
    row.appendChild(cuenta);
    row.appendChild(referencia);

    row.appendChild(debe);
    row.appendChild(haber);
    row.appendChild(debe_s);
    row.appendChild(haber_s);
    row.appendChild(banco);
    row.appendChild(cheque);
    row.appendChild(iva);
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
    console.log(ASIENTOS)
    calcular_totales();
}

function calcular_totales(e){
    if(e != undefined){
        var id = e.srcElement.id;
        var tc = parseFloat($('#comprobante_tipo_cambio').val());
        var conversion = tc * (parseFloat($('#'+id).val()) ? parseFloat($('#'+id).val()) : 0);
        $('#'+id+"-s").text(conversion.toFixed(2));
    }
    
    var total_debe = 0;
    var total_haber = 0;

    var total_debe_s = 0;
    var total_haber_s = 0;

    for( i = 1 ; i <= ASIENTOS.length ; i++ ){
        total_debe += parseFloat($('#debe-'+i).val());
        total_haber += parseFloat($('#haber-'+i).val());

        total_debe_s += parseFloat($('#debe-'+i+'-s').text()) ? parseFloat($('#debe-'+i+'-s').text()) : 0;
        total_haber_s += parseFloat($('#haber-'+i+'-s').text()) ? parseFloat($('#haber-'+i+'-s').text()) : 0;
    }
    
    $('#total_debe').text(total_debe.toFixed(2));
    $('#total_haber').text(total_haber.toFixed(2));
    $('#total_debe_s').text(total_debe_s.toFixed(2));
    $('#total_haber_s').text(total_haber_s.toFixed(2));

}

function cerrarModal(){
    console.log("cerrar el modal")
    $("#modal_registrar_factura").modal("hide");
}

$("#modal_registrar_factura").on('shown.bs.modal', function (e) {
    let nit = '';
    let nroFact = '';
    let codAutorizacion = '';
    $("#enviarFactura").click(() => {
        const nueva = $("#fact_nueva");
        const data = $("#fact_data").val();
        nit = '';
        nroFact = '';
        codAutorizacion = '';
        if(nueva.is(":checked")){//nueva
            nit = obtenerValorParametro(data, 'nit');
            nroFact = obtenerValorParametro(data, 'numero');
            codAutorizacion = obtenerValorParametro(data, 'cuf');          
        }else{
            const vectFact = data.split('|');
            nit = vectFact[0];
            nroFact = vectFact[1];
            codAutorizacion = vectFact[2];
        }
        console.log(nit,nroFact,codAutorizacion)
        var button = $(e.relatedTarget)
        var recipient = button.data('id')
        FACTURAS.push({
            id: recipient,
            nit: nit,
            nroFact: nroFact,
            codAuto: codAutorizacion
        })
    })

    

})

$("#modal_registrar_factura").on("hidden.bs.modal", function () {
    $("#fact_nueva").prop("checked", false);
    $('#fact_data').val("");
});