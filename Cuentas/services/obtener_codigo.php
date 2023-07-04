<?php

    include("../../conexion.php");

    $response = array('success' => false, 'message' => '');

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET['codigo'])){
            // Datos a registrar en la BD
            $codigo = $_GET['codigo'];
            // Consulta para insertar los nuevos registros ala tabla
            $sql = "SELECT TOP(1) * 
                    FROM tblCuentas tpc
                    WHERE tpc.codigo = ? ;";
            $params = array($codigo);
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $con, $sql , $params, $options );
            if( $stmt ){
                $cantidad = sqlsrv_num_rows($stmt);
                if($cantidad > 0){
                    $response['data'] = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC );
                    $response['success'] = true;
                    $response['message'] = 'Consulta realizada con éxito.';
                }else{
                    $response['message'] = "No existen registros de la cuenta con codigo $codigo.";
                }
            }else{
                $errors = sqlsrv_errors();
                foreach( $errors as $error ) {
                    $response['message'] .= $error[ 'message']." , ";
                }
            }
        }else{
            $response['message'] = 'El codigo de la cuenta es necesario.';
        }
    }else{
        $response['message'] = 'La solicitud no es de tipo GET.';
    }

    echo json_encode($response);
?>