$('#form_adicionar_cuenta').on('submit', function(e) {
    e.preventDefault();
    const ACCION = "ADICIONAR CUENTA";
    var datos = $('#form_adicionar_cuenta').serialize();
    $.ajax({
        data: datos,
        url: 'services/adicionar_cuenta.php',
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function(){
            console.log("["+ACCION+"] Enviando datos...");
        },
        success:function(response){
            if(response.success){

            }else{
                $('#titulo_mensaje').text('ERROR');
                $('#cuerpo_mensaje').text(response.message);
                $('#alerta').removeClass('hide');
                $('#alerta').addClass('show');
                setTimeout(()=>{
                    $('#titulo_mensaje').text('');
                    $('#cuerpo_mensaje').text('');
                    $('#alerta').removeClass('show');
                    $('#alerta').addClass('hide');
                }, 5000);
            }
            console.log("["+ACCION+"] "+response.message);
        },
        error: function(error){
            console.log("["+ACCION+"] "+error.statusText);
        }
    });
});

$('#modal_adicionar').on('hide.bs.modal', () => {
    $('#form_adicionar_cuenta').trigger("reset");
});


$('#modal_adicionar').on('show.bs.modal', () => {
    mdb.Alert.getInstance(document.getElementById('alerta')).show();
});
