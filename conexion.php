<?php

    $host = "localhost";
    $connectionInfo = array("Database" => "contabilidad", "Uid"=>"sa", "PWD"=>"stisBOLIVIA12345", "CharacterSet"=>"UTF-8");
    $con = sqlsrv_connect($host, $connectionInfo);
    
    if ( !$con ){
        echo 'No puede conectarse con la base de datos';
    }
    
?>