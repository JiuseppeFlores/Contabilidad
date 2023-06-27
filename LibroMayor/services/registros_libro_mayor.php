<?php

    include('../../conexion.php');
    $response = array( 'success' => false , 'message' => "" );

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['codigo']) && isset($_POST['descripcion']) && isset($_POST['fecha_inicial']) && isset($_POST['fecha_final'])){
            $codigo = $_POST['codigo'];
            $descripcion = $_POST['descripcion'];
            $fechaInicial = $_POST['fecha_inicial'];
            $fechaFinal = $_POST['fecha_final'];
            // Preparando la consulta SQL
            $sql = "SELECT * FROM tblCuentas tcu 
                    LEFT JOIN tblAsientos tas ON tcu.idCuenta = tas.idCuenta 
                    LEFT JOIN tblComprobantes tco ON tas.idComprobante = tco.idComprobante 
                    WHERE tcu.codigo = ?
                        AND tco.fecha BETWEEN ? AND ? ;";
            $params = array($codigo,$fechaInicial,$fechaFinal);
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $con, $sql , $params, $options );

            if( $stmt ){
                $response['data'] = array();
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC ) ) {
                    $row['fecha'] = $row['fecha']->format('d/m/Y');
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
            $response['message'] = "El código, la descripcion de la cuenta, la fecha inicial y final son necesarios.";
        }
    }else{
        $response['message'] = "No POST method.";
    }

    echo json_encode($response);

?>