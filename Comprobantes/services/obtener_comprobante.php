<?php

    include("../../conexion.php");

    $response = array('success' => false, 'message' => '');

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET['idComprobante'])){
            // Datos a registrar en la BD
            $idComprobante = intval($_GET['idComprobante']);
            // Consulta para insertar los nuevos registros ala tabla
            $sql = "SELECT * 
                    FROM tblComprobantes 
                    WHERE idComprobante = ? ;";
            $params = array($idComprobante);
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $con, $sql , $params, $options );
            if( $stmt ){
                $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC );
                // Consulta para obtener los asientos pertenecientes al comprobante
                $sql = "SELECT *
                        FROM tblAsientos ta
                        LEFT JOIN tblCuentas tc ON ta.idCuenta = tc.idCuenta
                        WHERE ta.idComprobante = ? ;";
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                $stmt = sqlsrv_query( $con, $sql , $params, $options );

                if( $stmt ){
                    $row['fecha'] = $row['fecha']->format("d/m/Y");
                    $row['asientos'] = array();
                    while( $asiento = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC ) ) {
                        array_push($row['asientos'], $asiento);
                    }
                    $response['data'] = $row;
                    $response['success'] = true;
                    $response['message'] = 'Consulta realizada con éxito.';
                }else{
                    $errors = sqlsrv_errors();
                    foreach( $errors as $error ) {
                        $response['message'] .= $error[ 'message']." , ";
                    }    
                }
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