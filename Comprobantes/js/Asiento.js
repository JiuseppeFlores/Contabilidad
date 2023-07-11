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

const seleccionarCuenta = () => {
    const ACCION = "LISTAR CUENTAS";
    var datos = {  };
    $.ajax({
        data: datos,
        url: '../Cuentas/services/listar_cuentas.php',
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){
                response.data.forEach( (cuenta) => {
                    var sp = "&nbsp;";
                    switch(cuenta.nivel){
                        case 'G':sp = '';break;
                        case 'R':sp = sp.repeat(4);break;
                        case 'T':sp = sp.repeat(8);break;
                        case 'C':sp = sp.repeat(12);break;
                        case 'S':sp = sp.repeat(16);break;
                        case 'A':sp = sp.repeat(20);break;
                    };
                    cuenta.descripcion = sp + cuenta.descripcion;
                });
                $('#t_cuentas').bootstrapTable('load', response.data);
                console.log(response);
            }else{
                show_toast(ACCION,response.message,'text-bg-danger');
            }
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            show_toast(ACCION,error.statusText,'text-bg-danger');
            console.log("["+ACCION+"] "+error.statusText);
        }
    });
};

function adicionar_asiento(){
    // Creacion de filas para la tabla
    var id = ASIENTOS.length + 1;
    const row = document.createElement('tr');
    const codigo = document.createElement('td');
    //codigo.appendChild(create_select('cuenta[]',id));
    codigo.appendChild(create_input("codigo-"+id,"form-control","","text","codigo[]",true));
    const idCuenta = document.createElement("input");
    idCuenta.type="hidden";
    idCuenta.name="cuenta[]";
    idCuenta.id="id-cuenta-"+id;
    //codigo.appendChild(create_input("codigo-"+id,"form-control","","text","referencia[]",true));
    codigo.appendChild(idCuenta);
    //codigo.onclick = seleccionarCuenta;

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
    debe_s.appendChild(create_input("debe-"+id+"-dolar","form-control","0","decimal","debe_dolar[]"));

    const haber_s = document.createElement('td');
    haber_s.appendChild(create_input("haber-"+id+"-dolar","form-control","0","decimal","haber_dolar[]"));

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

    //$("sl-"+id).select2();
}

function calcular_totales(e){
    if(e != undefined){
        var id = e.srcElement.id;
        var tc = parseFloat($('#comprobante_tipo_cambio').val());
        var conversion = (parseFloat($('#'+id).val()) ? parseFloat($('#'+id).val()) : 0) / tc;
        $('#'+id+"-dolar").val(conversion.toFixed(2));
    }
    
    var total_debe = 0;
    var total_haber = 0;

    var total_debe_s = 0;
    var total_haber_s = 0;

    for( i = 1 ; i <= ASIENTOS.length ; i++ ){
        total_debe += parseFloat($('#debe-'+i).val());
        total_haber += parseFloat($('#haber-'+i).val());

        total_debe_s += parseFloat($('#debe-'+i+'-dolar').val()) ? parseFloat($('#debe-'+i+'-dolar').val()) : 0;
        total_haber_s += parseFloat($('#haber-'+i+'-dolar').val()) ? parseFloat($('#haber-'+i+'-dolar').val()) : 0;
    }
    
    $('#total_debe').text(total_debe.toFixed(2));
    $('#total_haber').text(total_haber.toFixed(2));
    $('#total_debe_dolar').text(total_debe_s.toFixed(2));
    $('#total_haber_dolar').text(total_haber_s.toFixed(2));

}

function cerrarModal(){
    console.log("cerrar el modal")
    $("#modal_registrar_factura").modal("hide");
}

$("#modal_registrar_factura").on('shown.bs.modal', function (e) {
    let nit = '';
    let nroFact = '';
    let codAutorizacion = '';
    $("#enviarFactura").off('click').on('click',() => {
      // desvincula el anterior evento click anterior
        var button = $(e.relatedTarget)
        var recipient = button.data('id')
        let nuevaFact = '';
        if($("#fact_data").val().startsWith('https://')){
            nit = $("#nit").val();
            nroFact = $("#nroFactura").val();
            codAutorizacion = $("#codAuto").val();
            nuevaFact = $("#fact_data").val();
        }else{
            nit = $("#nit").val();
            nroFact = $("#nroFactura").val();
            codAutorizacion = $("#codAuto").val();
            nuevaFact = 'no';
        }
        FACTURAS[recipient] = {
            nit: nit,
            nroFact: nroFact,
            codAuto: codAutorizacion,
            nueva: nuevaFact
        }
    })
})

$("#modal_registrar_factura").on("hidden.bs.modal", function () {
    $("#fact_nueva").prop("checked", false);
    $('#fact_data').val("");
    $('#nit').val('');
    $('#nroFactura').val('');
    $('#codAuto').val('');
});

$("#manual").change(()=>{
    if($("#manual").is(":checked")){
        $('#nit').attr('disabled',false);
        $('#nroFactura').attr('disabled',false);
        $('#codAuto').attr('disabled',false);
        $("#fact_data").attr('disabled',true);
    }else{
        $('#nit').attr('disabled',true);
        $('#nroFactura').attr('disabled',true);
        $('#codAuto').attr('disabled',true); 
        $("#fact_data").attr('disabled',false);
    }
})

$("#fact_data").change(()=>{
    $("#help").html("Escanee el código QR");
    $("#help").css("color","black");
    const data = $("#fact_data").val();
    if(data.startsWith('https://')){//nueva
        $('#nit').val(obtenerValorParametro(data, 'nit'));
        $('#nroFactura').val(obtenerValorParametro(data, 'numero'));
        $('#codAuto').val(obtenerValorParametro(data, 'cuf'));     
        nuevoFact = encodeURIComponent(data);
    }else if(data.startsWith('httpsÑ')){
        $("#help").html("Cambie configuración de teclado");
        $("#help").css("color","red");
    }else{
        const vectFact = data.split('|');
        if(vectFact.length >= 3){
            $('#nit').val(vectFact[0])
            $('#nroFactura').val(vectFact[1])
            $('#codAuto').val(vectFact[2])
        }else{
            if(data.length > 15){
                $("#help").html("Cambie configuración de teclado");
                $("#help").css("color","red");
            }
        }
    }
})