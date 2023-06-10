<?php
    function isSessionStarted(){
        return(
            isset($_COOKIE['user']) && 
            isset($_COOKIE['server']) && 
            isset($_COOKIE['subdomain'])
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
?>