<?php

    include("../../conexion.php");

    $response = array('success' => false, 'message' => '');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['id'])){
            // Datos a registrar en la BD
            $idCuenta = $_POST['id'];
            // Seleccion de informacion del dato a eliminar
            $sql = "SELECT * FROM tblCuentas WHERE idCuenta = ? ;";
            $params = array($idCuenta);
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $con, $sql , $params, $options );
            if( $stmt ){
                $data = $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC );
                $codigo = $row['codigo'];
                // Consulta para eliminar registros de la BD
                $sql = "DELETE
                        FROM tblCuentas 
                        WHERE codigo LIKE '".$codigo."%' ;";
                $params = array();
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                $stmt = sqlsrv_query( $con, $sql , $params, $options );
                if( $stmt ){
                    $response['success'] = true;
                    $response['message'] = 'Cuenta eliminada con éxito.';
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
            $response['message'] = 'El ID de la cuenta es necesario.';
        }
    }else{
        $response['message'] = 'La solicitud no es de tipo POST';
    }

    echo json_encode($response);
?>