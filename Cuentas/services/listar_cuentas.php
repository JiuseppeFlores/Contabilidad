<?php

    include("../../conexion.php");

    $response = array('success' => false, 'message' => '');

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        $filtro = isset($_GET["movimiento"]) ? " WHERE tc.movimiento = ".$_GET["movimiento"]." " : " ";
        // Datos a registrar en la BD
        $sql = "SELECT *
                FROM tblCuentas tc
                ".$filtro."
                ORDER BY tc.codigo ASC;";
        $params = array($filtro);
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $stmt = sqlsrv_query( $con, $sql , $params, $options );
        if( $stmt ){
            $response['data'] = array();
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC ) ) {
                array_push($response['data'], $row);
            }
            $response['success'] = true;
            $response['message'] = 'Consulta realizada con éxito.';
        }else{
            $errors = sqlsrv_errors();
            foreach( $errors as $error ) {
                $response['message'] .= $error[ 'message']." , ";
            }
        }
    }else{
        $response['message'] = 'La solicitud no es de tipo GET.';
    }

    echo json_encode($response);
?>