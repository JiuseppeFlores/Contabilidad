<?php

    include("../../conexion.php");

    $response = array('success' => false, 'message' => '');

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET['id_proyecto'])){
            // Datos a registrar en la BD
            $id_proyecto = intval($_GET['id_proyecto']);
            // Consulta para insertar los nuevos registros ala tabla
            $sql = "SELECT IIF(MAX(tc.numero) IS NULL , 0 , MAX(tc.numero)) AS nro_comprobante
                    FROM tblComprobantes tc
                    WHERE tc.idProyecto = ? ;";
            $params = array($id_proyecto);
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $con, $sql , $params, $options );
            if( $stmt ){
                $response['data'] = intval(sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC )['nro_comprobante']) + 1;
                $response['success'] = true;
                $response['message'] = 'Consulta realizada con éxito.';
            }else{
                $errors = sqlsrv_errors();
                foreach( $errors as $error ) {
                    $response['message'] .= $error[ 'message']." , ";
                }
            }
        }else{
            $response['message'] = 'Los siguientes campos son necesarios: ID proyecto.';
        }
    }else{
        $response['message'] = 'La solicitud no es de tipo GET.';
    }

    echo json_encode($response);
?>