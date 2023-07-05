<?php

    include("../../conexion.php");
    //include_once '../../conexion_.php';
    $response = array('success' => false, 'message' => '');

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET['fecha']) && isset($_GET['tipo']) && $_GET['fecha'] != '' && $_GET['tipo'] != ''){
            // Datos a registrar en la BD
            $fecha = new DateTime($_GET['fecha']);
            $mes = $fecha->format("Y-m");
            $fechaInicial = $mes."-01";
            $aux = date('Y-m-d', strtotime("{$mes} + 1 month"));
            $fechaFinal = date('Y-m-d', strtotime("{$aux} - 1 day"));
            
            $tipo = $_GET['tipo'];
            // Consulta para insertar los nuevos registros ala tabla
            $sql = "SELECT IIF(MAX(tc.numero) IS NULL , 0 , MAX(tc.numero)) AS nro_comprobante
                    FROM tblComprobantes tc 
                    WHERE tc.tipo = ? 
                        AND tc.fecha BETWEEN ? AND ? ;";
            $params = array($tipo ,$fechaInicial, $fechaFinal);
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $con, $sql , $params, $options );
            if( $stmt ){
                $response['fecha'] = array($fechaInicial,$fechaFinal);
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
            $response['message'] = 'Los siguientes campos son necesarios: Fecha y tipo de comprobante.';
        }
    }else{
        $response['message'] = 'La solicitud no es de tipo GET.';
    }

    echo json_encode($response);
?>