<?php

    include('php/functions.php');
    include('php/environments.php');
    // CONEXION CON LA BASE DE DATOS
    $server_com = $SERVER_COMPANIES;
    $database_com = $DB_NAME_COMPANIES;
    $user_db_com = $USER_DB_COMPANIES;
    $pass_db_com = $PASS_DB_COMPANIES;
    $char_set_com = $CHARACTER_SET;

    $connection_info_com = array(
        "Database" => $database_com,
        "Uid" => $user_db_com,
        "PWD" => $pass_db_com,
        "CharacterSet" => "UTF-8"
    );
    $con_com = sqlsrv_connect( $server_com , $connection_info_com );
    if( !$con_com ){
        echo 'No puede conectarse con la base de datos.';
    }

?>