<?php

    include('../../conexion.php');
    $response = array( 'success' => false , 'message' => "" );

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['fecha_inicial']) && isset($_POST['fecha_final'])){
            $fechaInicial = $_POST['fecha_inicial'];
            $fechaFinal = $_POST['fecha_final'];
            // Preparando la consulta SQL
            $sql = "SELECT tcu.codigo, tcu.descripcion, SUM(tas.debe) debe, SUM(tas.haber) haber 
                    FROM tblCuentas tcu 
                    LEFT JOIN tblAsientos tas ON tcu.idCuenta = tas.idCuenta 
                    LEFT JOIN tblComprobantes tco ON tas.idComprobante = tco.idComprobante 
                    WHERE tco.fecha >= ?
                        AND tco.fecha <= ?
                    GROUP BY tcu.codigo, tcu.descripcion 
                    ORDER BY tcu.codigo ASC;";
            $params = array($fechaInicial,$fechaFinal);
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
            $response['message'] = "Fecha inicial y fecha final son necesarios.";
        }
    }else{
        $response['message'] = "No POST method.";
    }

    echo json_encode($response);

?>