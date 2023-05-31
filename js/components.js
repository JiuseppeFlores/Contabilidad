function start_pagination( page , total ){
    var pages = Math.ceil(total / CANTIDAD_REGISTROS);
    const ul = document.getElementById('pagination');
    ul.innerHTML = "";

    const li_prev = document.createElement('li');
    li_prev.classList = 'page-item'+(page == 1 ? ' disabled ' : '');
    const a_prev = document.createElement('a');
    a_prev.classList = 'page-link';
    a_prev.text = 'Ant.';
    a_prev.href = 'index.php?page='+( page - 1 );
    li_prev.appendChild(a_prev);
    ul.appendChild(li_prev);

    for( i = 1 ; i <= pages ; i++ ){
        const li = document.createElement('li');
        li.classList = 'page-item'+(page == i ? ' active ':'');
        const a = document.createElement('a');
        a.classList = 'page-link';
        a.text = i;
        a.href = 'index.php?page='+i;
        li.appendChild(a);
        ul.appendChild(li);
    }
    
    const li_next = document.createElement('li');
    li_next.classList = 'page-item'+(page == pages ? ' disabled ' : '' );
    const a_next = document.createElement('a');
    a_next.classList = 'page-link';
    a_next.text = 'Sig.';
    a_next.href = 'index.php?page='+( page + 1 );
    li_next.appendChild(a_next);
    ul.appendChild(li_next);
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
    },2000);
}