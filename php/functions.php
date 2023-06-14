<?php
    function isSessionStarted(){
        return(
            isset($_COOKIE['id_user']) && 
            isset($_COOKIE['user']) && 
            isset($_COOKIE['server']) && 
            isset($_COOKIE['base'])
        );
    }

    function isSessionActive($level = false){
        $dir = isset($level) ? $level : '';
        if(isSessionStarted()){
            return true;
        }else{
            header('Location: '.$level.'InicioSesion/');
            return;
        }
    }

    function closeSession(){
        setcookie('base',null,-1,'/',false);
        setcookie('server',null,-1,'/',false);
        setcookie('id_user',null,-1,'/',false);
        setcookie('user',null,-1,'/',false);
        if(isset($_COOKIE['base'])){
            unset($_COOKIE['base']);
        }
        if(isset($_COOKIE['server'])){
            unset($_COOKIE['server']);
        }
        if(isset($_COOKIE['id_user'])){
            unset($_COOKIE['id_user']);
        }
        if(isset($_COOKIE['user'])){
            unset($_COOKIE['user']);
        }
    }
?>