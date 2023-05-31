function iniciar_paginador(pagina){
    
}

function show_toast(title,message,clss){
    $('#toast_title').text(title);
    $('#toast_message').text(message);
    const toastLiveExample = document.getElementById('liveToast');
    $('#liveToast').addClass(clss);
    const toast = new bootstrap.Toast(toastLiveExample);
    toast.show();

    setTimeout(()=>{
        $('#toast_title').text('');
        $('#toast_message').text('');
        $('#liveToast').removeClass(clss);
    },7000);
}