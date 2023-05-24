<?php

    include("../../conexion.php");

    $response = array('success' => false, 'message' => '');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['id'])){
            // Datos a registrar en la BD
            $idCuenta = $_POST['id'];
            // Consulta para insertar los nuevos registros ala tabla
            $sql = "DELETE FROM tblCuentas WHERE idCuenta = ? ;";
            $params = array($idCuenta);
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $con, $sql , $params, $options );
            if($stmt === false){
                $errors = sqlsrv_errors();
                foreach( $errors as $error ) {
                    $response['message'] .= $error[ 'message']." , ";
                }
            }else{
                $response['success'] = true;
                $response['message'] = 'Cuenta eliminada con éxito.';
            }
        }else{
            $response['message'] = 'El ID de la cuenta es necesario.';
        }
    }else{
        $response['message'] = 'La solicitud no es de tipo POST';
    }

    echo json_encode($response);
?>