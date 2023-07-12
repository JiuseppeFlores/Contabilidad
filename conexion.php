<?php

    // VERIFICACIÓN DE CONEXIÓN A LA BASE DE DATOS
    require('php/environments.php');
    // CONEXION CON LA BASE DE DATOS
    if(isset($_SESSION['conta_base']) && isset($_SESSION['conta_server'])){
        $server = $_SESSION['conta_server'];
        $database = $_SESSION['conta_base'];
    }else{
        $server = $_COOKIE['conta_server'];
        $database = $_COOKIE['conta_base'];
    }
    
    $user_db = $USER_DB;
    $pass_db = $PASS_DB;
    $char_set = $CHARACTER_SET;
    
    $connection_info = array(
        "Database" => $database,
        "Uid" => $user_db,
        "PWD" => $pass_db,
        "CharacterSet" => $char_set
    );
    $con = sqlsrv_connect( $server , $connection_info );
    if( !$con ){
        echo 'No puede conectarse con la base de .';
    }
    
?>