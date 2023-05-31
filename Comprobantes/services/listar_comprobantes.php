<?php

    include("../../conexion.php");

    $response = array('success' => false, 'message' => '');

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET['pagina']) && isset($_GET['total'])){
            // Datos a registrar en la BD
            $pagina = intval($_GET['pagina']) - 1;
            $total = $_GET['total'];
            // Consulta para insertar los nuevos registros ala tabla
            $sql = "SELECT * 
                    FROM tblComprobantes tc
                    ORDER BY tc.idComprobante DESC
                    OFFSET $pagina ROWS
                    FETCH NEXT $total ROWS ONLY; ";
            $params = array();
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
            $response['message'] = 'Los siguientes campos son necesarios: Página y total.';
        }
    }else{
        $response['message'] = 'La solicitud no es de tipo GET.';
    }

    echo json_encode($response);
?>